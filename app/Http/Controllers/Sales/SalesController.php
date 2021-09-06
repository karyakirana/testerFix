<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Stock\StockKeluarController;
use App\Models\Master\Produk;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.sales.penjualan');
    }

    public function idPenjualan()
    {
        $data = Penjualan::where('activeCash', session('ClosedCash'))->latest('id_jual')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_jual, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/PJ/".date('Y');
        return $id;
    }

    public function kode()
    {
        $data = StockKeluar::where('active_cash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SK/".date('Y');
        return $id;
    }

    /**
     * membuat session untuk Penjualan Baru
     *
     * @return integer session('penjualan')
     */
    protected function createTemp($id_jual = null)
    {
        $create = PenjualanTemp::create([
            'jenisTemp' => 'Penjualan',
            'idSales' => Auth::user()->id,
            'id_jual'=> $id_jual,
        ]);

        return $create;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sessionTemp = session('penjualan');
        $penjualanTemp = null;

        // check session baru atau edit, check pembuat adalah yg login saat ini
        if ($sessionTemp)
        {
            $penjualanTemp = PenjualanTemp::find($sessionTemp);
            // jika ada id_jual atau idSales tidak sesuai dengan user yg sedang login
            // maka buat session baru
            if ($penjualanTemp->id_jual || $penjualanTemp->idSales != Auth::id()){
                // jika id_jual ada, maka buat temporary baru
                $penjualanTemp = $this->createTemp();
                session()->put(['penjualan'=>$penjualanTemp->id]);
            }
        } else {
            // buat session baru, dengan check temporary yg lama
            $penjualanTemp = PenjualanTemp::whereNull('id_jual')->where('jenisTemp', 'Penjualan')->where('idSales', Auth::id())->latest()->first();
            if (!$penjualanTemp){
                // jika tidak ada data, maka buat session baru
                $penjualanTemp = $this->createTemp();
                session()->put(['penjualan'=>$penjualanTemp->id]);
            } else {
                // jika ada, maka ambil temporary yg lama dan buat session untuk itu
                $penjualanTemp = $penjualanTemp;
                session()->put(['penjualan'=>$penjualanTemp->id]);
            }
        }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idPenjualan = $this->idPenjualan();
        $idTemp = $request->idTemp;
        $tglPenjualan = date('Y-m-d', strtotime($request->tglNota));
        $tglTempo = date('Y-m-d', strtotime($request->tglTempo));

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        // array untuk datadetil
        $dataDetil = null;
        foreach ($detilTemp as $row) {
            $data = [
                'id_jual' => $idPenjualan,
                'id_produk' => $row->idBarang,
                'jumlah' => $row->jumlah,
                'harga' => $row->harga,
                'diskon' => $row->diskon,
                'sub_total' => $row->sub_total,
            ];
            $dataDetil [] = $data;
            // $hitungData++;
        }

        // Data Penjualan untuk di insert ke Tabel Penjualan
        $data = [
            'activeCash' => session('ClosedCash'),
            'id_jual' => $idPenjualan,
            'tgl_nota' => $tglPenjualan,
            'tgl_tempo' => ($request->jenisBayar == 'Tempo') ? $tglTempo : null,
            'status_bayar' => $request->jenisBayar,
            'sudahBayar'=> "belum", // pembuatan nota belum bayar
            'total_jumlah' => $detilTemp->count(), // jumlah Item
            'ppn' => $request->ppn,
            'biaya_lain' => $request->biayaLain,
            'total_bayar' => $detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain, // total semua subtotal atau $request->hiddenTotalSemuanya
            'id_cust' => $request->idCustomer,
            'id_user' => Auth::user()->id,
            'idBranch'=> $request->branch,
            'keterangan' => $request->keterangan
        ];

        // transaction start
        $jsonData = null;
        DB::beginTransaction();
        try {
            // insert detil_penjualan
            $insertDetail = PenjualanDetil::insert($dataDetil);
            // insert penjualan
            $insertMaster = Penjualan::create($data);
            // insert stock_keluar
            $insertStockKeluar = StockKeluar::create([
                'active_cash'=>session('ClosedCash'),
                'tgl_keluar'=>$tglPenjualan,
                'kode'=>$this->kode(),
                'branch'=>$request->branch ?? null,
                'jenis_keluar'=>'Penjualan',
                'customer'=>$request->idCustomer,
                'penjualan'=>$idPenjualan,
                'users'=>Auth::id(),
            ]);
            // insert stock_keluar_detil
            foreach ($detilTemp as $row) {
                StockKeluarDetil::create([
                    'stock_keluar'=>$insertStockKeluar->id,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);
                // update or create inventory_real
                $inventory_real = InventoryReal::where('idProduk', $row->idBarang)
                    ->where('branchId', $request->branch)->get();
                if ($inventory_real->count() > 0){
                    // update
                    InventoryReal::where('idProduk', $row->idBarang)
                        ->where('branchId', $request->branch)
                        ->update([
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
            // delete detil_penjualan_temp
            $deleteTempDetail = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
            // delete penjualan_temp
            $deleteTempMaster = PenjualanTemp::where('id', $idTemp)->delete();
            DB::commit();
            session()->forget('penjualan'); // hapus session temp
            $jsonData = [
                'status' => true,
                'detail' => $insertDetail,
                'master' => $insertMaster,
                'deleteDetail' => $deleteTempDetail,
                'deletemaster' => $deleteTempMaster,
                'nomorPenjualan' => str_replace('/', '-', $idPenjualan),
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $jsonData = [
                'status' => false,
                'keterangan' => $e->getMessage(),
            ];
        }

        return response()->json($jsonData);

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
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
