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
            $urutan = (int) substr($data->id_jual, 0, 4);
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

    // store nota_retur_baik
    // satu nota_retur lebih dari satu nota jual
    public function storeNotaReturBaik(Request $request)
    {
        //
    }

    // store Retur Baik detil from detil_penjualan_temp
    // store stock_masuk_detil from detil_penjualan_temp
    // update or create inventory_real
    private function storeFromDetilTemp($idTemp, $idRetur, $idStockMasuk, $idBranch)
    {
        $detil_penjualan_temp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();
        foreach ($detil_penjualan_temp as $row)
        {
            ReturBaikDetil::create([
                'id_return'=>$idRetur,
                'id_produk'=>$row->idBarang,
                'jumlah'=>$row->jumlah,
                'harga'=>$row->harga,
                'diskon'=>$row->diskon,
                'sub_total'=>$row->sub_total,
            ]);

            StockMasukDetil::create([
                'idStockMasuk'=>$idStockMasuk,
                'idProduk'=>$row->idBarang,
                'jumlah'=>$row->jumlah
            ]);

            $inventory_real = InventoryReal::where('idProduk', $row->idBarang)->where('branchId', $idBranch);
            if ($inventory_real->get()->count() > 0)
            {
                // insert
                InventoryReal::create([
                    'idProduk'=>$row->idBarang,
                    'branchId'=>$idBranch,
                    'stockIn'=>$row->jumlah,
                    'stockNow'=>DB::raw('stockNow +'.$row->jumlah)
                ]);
            } else {
                // update
                $inventory_real->update([
                    'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                    'stockNow'=>DB::raw('stockNow +'.$row->jumlah)
                ]);
            }
        }
    }


    // belum kelar
    public function storeStockMasuk($idRetur, $dataStockMasuk,$idStockMasuk = null)
    {
        try {
            // insert atau update
            if ($idStockMasuk)
            {
                // update
                $stockMasuk = StockMasuk::where('id_penjualan', $idRetur)->first();
                $update = StockMasuk::where('id', $stockMasuk->id)->update([
                    'idBranch'=>$dataStockMasuk->id_branch,
                    'idUser'=>Auth::id(),
                    'tglMasuk'=>$dataStockMasuk->tgl_nota,
                ]);
            } else {
                // create
            }
        } catch (ModelNotFoundException $e) {
            //
        }
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
            $returBaik = ReturBaik::create([
                'id_return'=>$idRetur,
                'id_branch'=>$request->branch,
                'id_user'=>Auth::id(),
                'id_cust'=>$request->idCustomer,
                'tgl_nota'=>$tglRetur,
                'total_jumlah'=>0,
                'ppn'=>$request->ppn,
                'biaya_lain'=>$request->biayaLain,
                'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain, // total semua subtotal atau $request->hiddenTotalSemuanya,
                'keterangan'=>$request->keterangan
            ]);
            $this->storeFromDetilTemp($idTemp, $idRetur, $kodeStockMasuk, $request->branch);
            PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
            PenjualanTemp::destroy($idTemp);
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
            $stockMasuk = StockMasuk::where('id_penjualan', $idRetur)->update([
                'id_branch'=>$request->branch,
                'id_user'=>Auth::id(),
                'id_cust'=>$request->idCustomer,
                'tglMasuk'=>$tglRetur,
                'keterangan'=>$request->keterangan
            ]);
            // delete stockmasuk_detil
            StockMasukDetil::where('idStockMasuk', $stockMasuk->id)->delete();
            // simpan dari detil Temp
            $this->storeFromDetilTemp($idTemp, $idRetur, $kodeStockMasuk, $request->branch);
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
        return response()->json();
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
