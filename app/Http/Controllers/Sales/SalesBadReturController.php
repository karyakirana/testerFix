<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Sales\SalesReturRusakRepository;
use App\Http\Repositories\Stock\StockRusakMasukRepository;
use App\Models\Sales\ReturRusakDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\Sales\ReturRusak;
use App\Models\Stock\InventoryRusak;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesBadReturController extends Controller
{
    public function index()
    {
        return view('pages.sales.returRusak');
    }

    private function createTemp($id_retur = null)
    {
        $create = PenjualanTemp::create([
            'jenisTemp' => 'ReturRusak',
            'idSales' => Auth::id(),
            'id_jual'=> $id_retur,
        ]);
        return $create;
    }

    public function create()
    {
        $sess = session('ReturBaik');
        if ($sess){
            $dataTemp = PenjualanTemp::find($sess);
        } else {
            $checkLastSession = PenjualanTemp::where('idSales', Auth::id())->where('jenisTemp', 'ReturRusak')->latest();
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
        return view('pages.sales.returRusakTrans', $data);
    }

    public function idReturRusak()
    {
        $data = ReturRusak::where('activeCash', session('ClosedCash'))->latest('id_rr')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_rr, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/RR/".date('Y');
        return $id;
    }

    public function kodeStockRusak()
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

    public function store(Request $request)
    {
        $idRetur = SalesReturRusakRepository::kode();
        $kodeStockMasuk = $this->kodeStockRusak();
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));
        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        $dataRequest = (object)[
            'retur_id'=>$idRetur,
            'branch_id'=>$request->branch,
            'user_id'=>Auth::id(),
            'customer_id'=>$request->idCustomer,
            'tgl_nota'=>$request->tglRetur,
            'total_jumlah'=>0,
            'ppn'=>$request->nota,
            'biaya_lain'=>$request->biaya_lain,
            'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain,
            'keterangan'=>$request->keterangan,
            // for Stock Masuk Rusak
            'jenis_masuk'=>'Retur Baik'
        ];

        DB::beginTransaction();
        try {
            // insert Retur Rusak
            $returRusakSave = new SalesReturRusakRepository();
            $returRusakSave->create($dataRequest);

            // insert stockMasuk
            $stockMasukRusak = new StockRusakMasukRepository();
            $stockMasuk = $stockMasukRusak->create($dataRequest);

            // insert detail
            $detil = StockDetilTemp::where('idPenjualanTemp', $idTemp);
            foreach ($detil->get() as $row){
                // insert rr_detail
                ReturRusakDetil::create([
                    'id_rr'=>$idRetur,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah,
                    'harga'=>$row->harga,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total
                ]);

                // insert stockmasuk detil
                StockMasukDetil::create([
                    'idStockMasuk'=>$stockMasuk->id,
                    'idProduk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah,
                ]);

                // insert or update inventory real
                $inventory_real = InventoryRusak::where('idProduk', $row->idBarang)
                    ->where('branchId', $request->branch)->get();
                if ($inventory_real->count() > 0){
                    // update
                    InventoryRusak::where('idProduk', $row->idBarang)
                        ->where('branchId', $request->branch)
                        ->update([
                            'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                } else {
                    InventoryRusak::create([
                        'idProduk'=>$row->idBarang,
                        'branchId'=>$request->branch,
                        'stockIn'=>$row->jumlah,
                        'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                    ]);
                }
            }
            $detil->delete();
            PenjualanTemp::destroy($idTemp);
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

    public function edit($id)
    {
        // check temp for edit
        $idRetur = str_replace('-', '/', $id);
        $checkTemp = PenjualanTemp::where('id_jual', $idRetur)->first();
        $returRusak = ReturRusak::with('customer')->find($idRetur);
        if ($checkTemp){
            // jika temp edit sebelumnya ada
            // delete detil_temp where id_temp
            PenjualanDetilTemp::where('idPenjualanTemp', $checkTemp->id)->delete();
            $idTemp = $checkTemp->id;
        } else {
            $idTemp = $this->createTemp($idRetur)->id;
        }
        $detil = ReturRusakDetil::where('id_rr', $idRetur)->get();
        // insert detil to detil_temporary
        if ($detil->count() > 0){
            foreach ($detil as $row)
            {
                // insert
                PenjualanDetilTemp::create([
                    'idPenjualanTemp'=> $idTemp,
                    'idBarang'=>$row->id_produk,
                    'harga'=>$row->harga,
                    'jumlah'=>$row->jumlah,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total,
                ]);
            }
        }
        $data = [
            'idTemp'=>$idTemp,
            'id_jual'=>$returRusak->id_jual,
            'idCustomer'=>$returRusak->id_cust,
            'nama_customer'=>$returRusak->customer->nama_cust,
            'status_bayar'=>$returRusak->status_bayar,
            'tgl_nota'=> date('d-M-Y', strtotime($returRusak->tgl_nota)),
            'tgl_tempo' => (isset($returRusak->tgl_tempo)) ? date('d-M-Y',strtotime($returRusak->tgl_tempo)) : null,
            'ppn' => $returRusak->ppn,
            'biaya_lain'=>$returRusak->biaya_lain,
            'total_bayar'=>$returRusak->total_bayar,
            'keterangan'=>$returRusak->keterangan,
            'branch'=>$returRusak->idBranch,
            'update'=>true
        ];
        return view('pages.sales.returRusakTrans', $data);
    }

    public function update(Request $request)
    {
        $idRetur = $request->id;
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));

        $stockLama = StockMasuk::where('id_penjualan', $idRetur)->first();
        $dataLama = ReturRusakDetil::where('id_rr', $idRetur);

        DB::beginTransaction();
        try {
            // delete detil
            ReturRusakDetil::where('id_rr', $idRetur)->delete();
            StockMasukDetil::where('idStockMasuk', $stockLama->id)->delete();

            // recovery stock
            foreach ($dataLama->get() as $row){
                // update inventory_real
                InventoryRusak::where('idProduk', $row->id_produk)
                    ->where('branchId', $stockLama->idBranch)
                    ->update([
                        'stockIn'=>DB::raw('stockIn -'.$row->jumlah),
                        'stockNow'=>DB::raw('stockNow -'.$row->jumlah),
                    ]);
            }

            // update Penjualan
            $updatePenjualan = ReturRusak::where('id_rr', $idRetur)->update([
                'id_rr'=>$idRetur,
                'id_branch'=>$request->branch,
                'id_user'=>Auth::id(),
                'id_cust'=>$request->idCustomer,
                'tgl_nota'=>$tglRetur,
                'total_jumlah'=>0,
                'ppn'=>$request->ppn,
                'biaya_lain'=>$request->biaya_lain,
                'total_bayar'=>$request->total_bayar,
                'keterangan'=>$request->keterangan,
            ]);
            $updateStockMasuk = StockMasuk::where('id', $stockLama->id)->update([
                'activeCash'=>session('ClosedCash'),
                'id_penjualan'=>$idRetur,
                'jenis_masuk'=>'Retur Rusak',
                'idBranch'=>$request->branch,
                'idUser'=>Auth::id(),
                'tglMasuk'=>$tglRetur
            ]);

            // insert or update inventory real
            $inventory_real = InventoryRusak::where('idProduk', $row->idBarang)
                ->where('branchId', $request->branch)->get();
            if ($inventory_real->count() > 0){
                // update
                InventoryRusak::where('idProduk', $row->idBarang)
                    ->where('branchId', $request->branch)
                    ->update([
                        'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                        'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                    ]);
            } else {
                InventoryRusak::create([
                    'idProduk'=>$row->idBarang,
                    'branchId'=>$request->branch,
                    'stockIn'=>$row->jumlah,
                    'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                ]);
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
}
