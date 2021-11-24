<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Stock\StockKeluarController;
use App\Http\Repositories\Sales\SalesRepository;
use App\Http\Repositories\Sales\SalesTempRepository;
use App\Http\Repositories\Stock\StockKeluarRepository;
use App\Models\Master\Produk;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('pages.sales.penjualan');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $sessionTemp = session('penjualan');
        $penjualanTemp = null;

        // get data sales Temp
        $penjualanTemp = (new SalesTempRepository())->checkSession('Penjualan', $sessionTemp);

        $data = [
            'idTemp' => $penjualanTemp->id,
            'idSales' => $penjualanTemp->jenisTemp,
            'namaSales'=> $penjualanTemp->idSales,
        ];
        return view('pages.sales.penjualanTransaksi', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $penjualanRepo = new SalesRepository();
        $idPenjualan = $penjualanRepo->kode();
        $idTemp = $request->idTemp;
        $tglPenjualan = date('Y-m-d', strtotime($request->tglNota));
        $tglTempo = date('Y-m-d', strtotime($request->tglTempo));
        $detilTemp = (new SalesTempRepository())->getTempDetail($idTemp);

        // Data Penjualan untuk di insert ke Tabel Penjualan
        $data = (object) [
            'idTemp'=>$idTemp,
            'idPenjualan' => $idPenjualan,
            'tglPenjualan' => $tglPenjualan,
            'tglTempo' => $tglTempo,
            'jenisBayar' => $request->jenisBayar,
            'sudahBayar'=> "belum", // pembuatan nota belum bayar
            'total_jumlah' => $detilTemp->count(), // jumlah Item
            'ppn' => $request->ppn,
            'biayaLain' => $request->biayaLain,
            'total_bayar' => $detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain, // total semua subtotal atau $request->hiddenTotalSemuanya
            'idCustomer' => $request->idCustomer,
            'idUser' => Auth::user()->id,
            'idBranch'=> $request->branch,
            'keterangan' => $request->keterangan,
            // for Stock Keluar
            'tglStockKeluar' => $tglPenjualan,
            'jenisStockKeluar'=>'penjualan'
        ];

        $commit = (new SalesRepository())->commitPenjualan($data);
        return response()->json([$commit]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Penjualan::findOrFail(str_replace('-', '/', $id));
        return response()->json($data);
    }

    protected function createTemp($id_jual)
    {
        return (new SalesTempRepository())->newSessionSalesTemp($id_jual);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function edit($id)
    {
        // check temp for edit
        $id_jual = str_replace('-', '/', $id);
        $checkTemp = PenjualanTemp::where('id_jual', $id_jual)->first();
        $penjualan = Penjualan::with('customer')->find($id_jual);
        if ($checkTemp){
            // jika temp edit sebelumnya ada
            // delete detil_temp where id_temp
            PenjualanDetilTemp::where('idPenjualanTemp', $checkTemp->id)->delete();
            $idTemp = $checkTemp->id;
        } else {
            $idTemp = $this->createTemp($id_jual)->id;
        }
        $detil = PenjualanDetil::where('id_jual', $id_jual)->get();
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
            'id_jual'=>$penjualan->id_jual,
            'idCustomer'=>$penjualan->id_cust,
            'nama_customer'=>$penjualan->customer->nama_cust,
            'status_bayar'=>$penjualan->status_bayar,
            'tgl_nota'=> date('d-M-Y', strtotime($penjualan->tgl_nota)),
            'tgl_tempo' => (isset($penjualan->tgl_tempo)) ? date('d-M-Y',strtotime($penjualan->tgl_tempo)) : null,
            'ppn' => $penjualan->ppn,
            'biaya_lain'=>$penjualan->biaya_lain,
            'total_bayar'=>$penjualan->total_bayar,
            'keterangan'=>$penjualan->keterangan,
            'branch'=>$penjualan->idBranch,
            'update'=>true
        ];
        return view('pages.sales.penjualanTransaksi', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $idPenjualan = $request->id;
        $idTemp = $request->idTemp;
        $tglPenjualan = date('Y-m-d', strtotime($request->tglNota));
        $tglTempo = date('Y-m-d', strtotime($request->tglTempo));

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp);

        // data lama
        $data_lama = Penjualan::find($idPenjualan);

        $jsonData = null;
        DB::beginTransaction();

        try {
            // delete detil_penjualan dan stock_keluar_detil
            $deleteDetil = PenjualanDetil::where('id_jual', $idPenjualan);
            foreach ($deleteDetil->get() as $row){
                // update inventory_real
                InventoryReal::where('idProduk', $row->id_produk)
                    ->where('branchId', $data_lama->idBranch)
                    ->update([
                        'stockOut'=>DB::raw('stockOut -'.$row->jumlah),
                    ]);
            }
            $deleteDetil->delete();
            $simpanMaster = [
                'id_cust'=>$request->idCustomer,
                'idBranch'=>$request->branch,
                'id_user'=>Auth::id(),
                'tgl_nota'=>$tglPenjualan,
                'tgl_tempo'=>($request->jenisBayar == 'Tempo') ? $tglTempo : null,
                'status_bayar'=>$request->jenisBayar,
                'sudahBayar'=>'Belum',
                'ppn'=>$request->ppn,
                'biaya_lain'=>$request->biayaLain,
                'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain,
                'keterangan'=>$request->keterangan
            ];
            // update penjualan
            $update = Penjualan::where('id_jual', $idPenjualan)->update($simpanMaster);

            // insert detil_penjualan
            if ($detilTemp->count() > 0)
            {
                foreach ($detilTemp->get() as $row)
                {
                    PenjualanDetil::create([
                        'id_jual'=>$idPenjualan,
                        'id_produk'=>$row->idBarang,
                        'jumlah'=>$row->jumlah,
                        'harga'=>$row->harga,
                        'diskon'=>$row->diskon,
                        'sub_total'=>$row->sub_total
                    ]);
                }
            }
            // operation stock
            $stock_keluar = StockKeluar::where('penjualan', $idPenjualan)->first();
            if ($stock_keluar){
                StockKeluar::where('id', $stock_keluar->id)->update([
                    'tgl_keluar'=>$tglPenjualan,
                    'branch'=>$request->branch,
                    'customer'=>$request->idCustomer,
                    'users'=>Auth::id(),
                ]);
                // delete stock_keluar_detil
                StockKeluarDetil::where('stock_keluar', $stock_keluar->id)->delete();
                // insert stock_keluar_detil
                if ($detilTemp->count() > 0)
                {
                    foreach ($detilTemp->get() as $row)
                    {
                        StockKeluarDetil::create([
                            'stock_keluar'=>$stock_keluar->id,
                            'id_produk'=>$row->idBarang,
                            'jumlah'=>$row->jumlah,
                        ]);

                        // update inventory_real
                        $update_inventory = InventoryReal::where('idProduk', $row->idBarang)
                            ->where('branchId', $request->branch);
                        if($update_inventory->get()->count() > 0){
                            $update_inventory->update([
                                'stockOut'=>DB::raw('stockOut +'.$row->jumlah),
                            ]);
                        } else {
                            InventoryReal::create([
                                'idProduk'=>$row->idBarang,
                                'branchId'=>$request->branch,
                                'stockOut'=>$row->jumlah,
                            ]);
                        }
                    }
                }
            }
            // delete detil temp
            PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
            // delete temp
            PenjualanTemp::destroy($idTemp);
            // destroy session penjualan
            session()->forget('penjualan');
            DB::commit();
            $jsonData = [
                'status'=>true,
                'nomorPenjualan'=>str_replace('/', '-', $idPenjualan)
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return response()->json($jsonData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = Penjualan::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
