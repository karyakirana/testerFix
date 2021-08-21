<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\ReturBaikDetil;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\Sales\ReturBaik;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesReturController extends Controller
{
    public function index()
    {
        return view('pages.sales.returBaik');
    }

    // generate id_return
    private function idReturBaik()
    {
        $data = ReturBaik::where('activeCash', session('ClosedCash'))->latest('id_return')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_return, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/RB/".date('Y');
        return $id;
    }

    // generate kode stockmasuk
    private function kode()
    {
        $data = StockMasuk::where('active_cash', session('ClosedCash'))->latest()->first();
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SM/".date('Y');
        return $id;
    }

    private function createTemp($id_retur = null)
    {
        $create = PenjualanTemp::create([
            'jenisTemp' => 'ReturBaik',
            'idSales' => Auth::user()->id,
            'id_jual'=> $id_retur,
        ]);
        return $create;
    }

    public function create()
    {
        // checkk session first
        $sess = session('ReturBaik');
        if ($sess){
            $dataTemp = PenjualanTemp::find($sess);
        } else {
            $checkLastSession = PenjualanTemp::where('idSales', Auth::id())->where('jenisTemp', 'ReturBaik')->latest();
            if ($checkLastSession->get()->count() > 0){
                $dataTemp = $checkLastSession->first();
            } else {
                $dataTemp = $this->createTemp();
            }
            session()->put(['ReturBaik'=>$dataTemp->id]);
        }
        $data = [
            'idTemp' => $dataTemp->id,
            'idSales' => $dataTemp->jenisTemp,
            'namaSales'=> $dataTemp->idSales,
        ];
        return view('pages.sales.returBaikTrans', $data);
    }

    public function show($id_return)
    {
        //
    }

    /**
     * store Penjualan Return Baik
     * store nota_retur_baik
     * store Stock Masuk
     * update or Create Stock Real
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // variable dasar
        $idRetur = $this->idReturBaik();
        $kodeStockMasuk = $this->kode();
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        DB::beginTransaction();
        try {
            // insert return_bersih
            $insertRetur = ReturBaik::create([
                'id_return'=>$idRetur,
                'id_branch'=>$request->branch,
                'id_user'=>Auth::id(),
                'id_cust'=>$request->idCustomer,
                'tgl_nota'=>$tglRetur,
                'total_jumlah'=>0,
                'ppn'=>$request->ppn,
                'biaya_lain'=>$request->biayaLain,
                'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLainß,
                'keterangan'=>$request->keterangan,
                'activeCash'=>session('ClosedCash')
            ]);

            // insert stockmasuk
            $insertStockMasuk = StockMasuk::create([
                'activeCash'=>session('ClosedCash'),
                'kode'=>$kodeStockMasuk,
                'idBranch'=>$request->branch,
                'idSupplier'=>null,
                'idUser'=>Auth::id(),
                'tglMasuk'=>$tglRetur,
                'keterangan'=>$request->keterangan,
                'id_penjualan'=>$idRetur,
                'jenis_masuk'=>'Retur'
            ]);

            // insert detail from detil_penjualan_temp
            foreach ($detilTemp as $row)
            {
                // insert rb_detail
                ReturBaikDetil::create([
                    'id_return'=>$idRetur,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah,
                    'harga'=>$row->harga,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total
                ]);

                // insert stockmasuk_detil
                StockMasukDetil::create([
                    'idStockMasuk'=>$insertStockMasuk->id,
                    'idProduk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);

                // insert or update inventory real
                $inventory_real = InventoryReal::where('idProduk', $row->idBarang)
                    ->where('branchId', $request->branch)->get();
                if ($inventory_real->count() > 0){
                    // update
                    InventoryReal::where('idProduk', $row->idBarang)
                        ->where('branchId', $request->branch)
                        ->update([
                            'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                } else {
                    InventoryReal::create([
                        'idProduk'=>$row->idBarang,
                        'branchId'=>$request->branch,
                        'stockIn'=>$row->jumlah,
                        'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                    ]);
                }
            }
            // delete temp
            PenjualanTemp::where('id', $idTemp)->delete();
            PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
            DB::commit();
            $data = [
                'status'=>true,
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $data = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return response()->json($data);
    }

    public function edit($id)
    {
        return view('pages.sales.returBaikTrans');
    }

    public function update(Request $request)
    {
        // variable dasar
        $idRetur = $request->id;
        $kodeStockMasuk = $this->kode();
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        $dataLama = ReturBaik::where('id_return', $idRetur)->first();
        $dataLamaStockMasuk = StockMasuk::where('id_penjualan', $idRetur)->first();

        DB::beginTransaction();
        try {
            // hapus detil_retur
            $deleteDetil = ReturBaikDetil::where('id_return', $idRetur);
            // mengembalikan inventory sebelumnya
            foreach ($deleteDetil->get() as $row){
                // update inventory_real
                InventoryReal::where('idProduk', $row->id_produk)
                    ->where('branchId', $dataLama->id_branch)
                    ->update([
                        'stockIn'=>DB::raw('stockIn -'.$row->jumlah),
                        'stockNow'=>DB::raw('stockNow -'.$row->jumlah),
                    ]);
            }
            // eksekusi hapus detil_retur
            $deleteDetil->delete();
            ReturBaik::where('id_return', $idRetur)->update([
                'id_branch'=>$request->branch,
                'id_user'=>Auth::id(),
                'id_cust'=>$request->idCustomer,
                'tgl_nota'=>$tglRetur,
                'total_jumlah'=>0,
                'ppn'=>$request->ppn,
                'biaya_lain'=>$request->biayaLain,
                'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain,
                'keterangan'=>$request->keterangan
            ]);
            $stockMasuk = StockMasuk::where('id', $dataLamaStockMasuk->id)->update([
                'id_branch'=>$request->branch,
                'id_user'=>Auth::id(),
                'id_cust'=>$request->idCustomer,
                'tglMasuk'=>$tglRetur,
                'keterangan'=>$request->keterangan
            ]);
            // delete stockmasuk_detil
            StockMasukDetil::where('idStockMasuk', $dataLamaStockMasuk->id)->delete();
            // simpan dari detil Temp
            // insert detail from detil_penjualan_temp
            foreach ($detilTemp as $row)
            {
                // insert rb_detail
                ReturBaikDetil::create([
                    'id_return'=>$idRetur,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah,
                    'harga'=>$row->harga,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total
                ]);

                // insert stockmasuk_detil
                StockMasukDetil::create([
                    'idStockMasuk'=>$dataLamaStockMasuk->id,
                    'idProduk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);

                // insert or update inventory real
                $inventory_real = InventoryReal::where('idProduk', $row->idBarang)
                    ->where('branchId', $request->branch)->get();
                if ($inventory_real->count() > 0){
                    // update
                    InventoryReal::where('idProduk', $row->idBarang)
                        ->where('branchId', $request->branch)
                        ->update([
                            'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                } else {
                    InventoryReal::create([
                        'idProduk'=>$row->idBarang,
                        'branchId'=>$request->branch,
                        'stockIn'=>$row->jumlah,
                        'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                    ]);
                }
            }
            DB::commit();
            $jsonData = [
                'status'=>true
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return response()->json($jsonData);
    }

    public function destroy($id)
    {
        //
    }

    public function print($id)
    {
        //
    }
}
