<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Sales\SalesReturDetilRepository;
use App\Http\Repositories\Sales\SalesReturRepository;
use App\Http\Repositories\Stock\InventoryRealRepository;
use App\Http\Repositories\Stock\StockMasukDetilRepository;
use App\Http\Repositories\Stock\StockMasukRepository;
use App\Models\Sales\ReturBaikDetil;
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
use Yajra\DataTables\DataTables;

class SalesReturController extends Controller
{
    public function index()
    {
        return view('pages.sales.returBaik');
    }

    public function listData()
    {
        //
    }

    public function listDetilData($idRetur)
    {
        //
    }

    // generate kode stockmasuk
    private function kode(): string
    {
        $salesReturRepo = new SalesReturRepository();
        return $salesReturRepo->kode();
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
        $idRetur = $this->kode();
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        // data Request
        $dataRequest = (object) [
            'idRetur'=>$idRetur,
            'branch_id'=>$request->branch,
            'customer_id'=>$request->idCustomer,
            'tgl_retur'=>$tglRetur,
            'ppn'=>$request->ppn,
            'biaya_lain'=>$request->biayaLain,
            'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain,
            'keterangan'=>$request->keterangan,
        ];
//        dd($dataRequest);

        DB::beginTransaction();
        try {
            // insert return_bersih
            $salesReturRepo = new SalesReturRepository();
            $insertRetur = $salesReturRepo->create($dataRequest);

            // insert stockmasuk
            $stockMasukRepo = new StockMasukRepository();
            $insertStockMasuk = $stockMasukRepo->create($dataRequest);

            // insert detail from detil_penjualan_temp
            foreach ($detilTemp as $row)
            {
                $dataDetilTemp = (object)[
                    'id_return'=>$idRetur,
                    'id_stock_masuk'=>$insertStockMasuk->id,
                    'branch_id'=>$request->branch,
                    'id_produk'=>$row->idBarang,
                    'harga'=>$row->harga,
                    'jumlah'=>$row->jumlah,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total,
                ];
                // insert rb_detail
                $returDetil = new SalesReturDetilRepository();
                $returDetil->create($dataDetilTemp);

                // insert stockmasuk_detil
                $stockMasukDetil = new StockMasukDetilRepository();
                $stockMasukDetil->create($dataDetilTemp);

                // insert or update inventory real
                $inventoryRealRepo = new InventoryRealRepository();
                $inventoryRealRepo->insertOrUpdate($dataDetilTemp);
            }
            // delete temp
            PenjualanTemp::where('id', $idTemp)->delete();
            PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
            // forget session
            session()->forget('ReturBaik');
            DB::commit();
            $data = [
                'status'=>true,
                'nomorRetur' => str_replace('/', '-', $idRetur)
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
        // check temp for edit
        $id_jual = str_replace('-', '/', $id);
        $checkTemp = PenjualanTemp::where('id_jual', $id_jual)->first();
        $penjualan = ReturBaik::with('customer')->find($id_jual);
        if ($checkTemp){
            // jika temp edit sebelumnya ada
            // delete detil_temp where id_temp
            PenjualanDetilTemp::where('idPenjualanTemp', $checkTemp->id)->delete();
            $idTemp = $checkTemp->id;
        } else {
            $idTemp = $this->createTemp($id_jual)->id;
        }
        $detil = ReturBaikDetil::where('id_return', $id_jual)->get();
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
            'id_jual'=>$penjualan->id_return,
            'idCustomer'=>$penjualan->id_cust,
            'nama_customer'=>$penjualan->customer->nama_cust,
            'status_bayar'=>$penjualan->status_bayar,
            'tgl_nota'=> date('d-M-Y', strtotime($penjualan->tgl_nota)),
            'ppn' => $penjualan->ppn,
            'biaya_lain'=>$penjualan->biaya_lain,
            'total_bayar'=>$penjualan->total_bayar,
            'keterangan'=>$penjualan->keterangan,
            'branch'=>$penjualan->id_branch,
            'update'=>true
        ];
        return view('pages.sales.returBaikTrans', $data);
    }

    public function update(Request $request)
    {
        // variable dasar
        $idRetur = $request->id;
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        $dataLama = ReturBaik::where('id_return', $idRetur)->first();
        $dataLamaStockMasuk = StockMasuk::where('id_penjualan', $idRetur)->first();

        $dataRequest = (object)[
            'idRetur'=>$idRetur,
            'branch_id'=>$request->branch,
            'customer_id'=>$request->idCustomer,
            'tgl_retur'=>$tglRetur,
            'ppn'=>$request->ppn,
            'biaya_lain'=>$request->biayaLain,
            'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain,
            'keterangan'=>$request->keterangan,
        ];

        DB::beginTransaction();
        try {
            // hapus detil_retur
            $deleteDetil = ReturBaikDetil::where('id_return', $idRetur);
            // mengembalikan inventory sebelumnya
            foreach ($deleteDetil->get() as $row){
                // update inventory_real
                $inventoryRollback = new InventoryRealRepository();
                $inventoryRollback->rollback($row, $dataLamaStockMasuk->idBranch);
            }
            // eksekusi hapus detil_retur
            $deleteDetil->delete();
            // delete stockmasuk_detil
            StockMasukDetil::where('idStockMasuk', $dataLamaStockMasuk->id)->delete();
            // update Retur
            $updateRetur = new SalesReturRepository();
            $updateRetur->update($idRetur, $dataRequest);
            // update stock masuk
            $updateStock = new StockMasukRepository();
            $updateStock->update($dataLamaStockMasuk->id, $dataRequest);
            foreach ($detilTemp as $row)
            {
                $dataDetilTemp = (object)[
                    'id_return'=>$idRetur,
                    'id_stock_masuk'=>$dataLamaStockMasuk->id,
                    'branch_id'=>$request->branch,
                    'id_produk'=>$row->idBarang,
                    'harga'=>$row->harga,
                    'jumlah'=>$row->jumlah,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total,
                ];
                // insert rb_detail
                $returDetil = new SalesReturDetilRepository();
                $returDetil->create($dataDetilTemp);

                // insert stockmasuk_detil
                $stockMasukDetil = new StockMasukDetilRepository();
                $stockMasukDetil->create($dataDetilTemp);

                // insert or update inventory real
                $inventoryRealRepo = new InventoryRealRepository();
                $inventoryRealRepo->insertOrUpdate($dataDetilTemp);
            }
            PenjualanTemp::where('id', $idTemp)->delete();
            PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
            DB::commit();
            $jsonData = [
                'status'=>true,
                'nomorRetur' => str_replace('/', '-', $idRetur)
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
