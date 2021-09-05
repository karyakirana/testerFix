<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use App\Models\Stock\StockTemp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockKeluarController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockKeluarList');
    }

    private function createSessionStock($idStockMasuk = null)
    {
        // insert stock_temp
        return StockTemp::create([
            'jenisTemp'=>'StockKeluar',
            'idUser'=>Auth::id(),
            'stockMasuk'=>$idStockMasuk
        ]);
    }

    private function checkLastCart()
    {
        // check session stock
        if (session('stockKeluar'))
        {
            // jika ada langsung ambil data stock
            $stock = StockTemp::find(session('stockKeluar'));
        } else {
            // check last temp
            $lastTemp = StockTemp::where('idUser', Auth::id())->where('jenisTemp', 'StockKeluar')->whereNull('stockMasuk');
            if ($lastTemp->count() > 0)
            {
                // jika ada
                $stock = $lastTemp->latest()->first();

                session()->put(['stockKeluar'=>$stock->id]);

            } else {
                $stock = $this->createSessionStock();
                session()->put(['stockKeluar'=>$stock->id]);
            }
        }
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser
        ];
        return $data;
    }

    public function create()
    {
        return view('pages.stock.stockKeluarTrans', $this->checkLastCart());
    }

    private function kode()
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

    public function store(Request $request)
    {
        $idTemp = $request->idTemp;
        $kode = $this->kode();
        $tglKeluar = date('Y-m-d', strtotime($request->tgl_keluar));

        DB::beginTransaction();
        try {
            // insert to stock_keluar
            $stockKeluar = StockKeluar::create([
                'active_cash'=>session('ClosedCash'),
                'tgl_keluar'=>$tglKeluar,
                'kode'=>$kode,
                'branch'=>$request->branch,
                'jenis_keluar'=>'nonPenjualan',
                'supplier'=>$request->idSupplier,
                'customer'=>null,
                'penjualan' =>null,
                'users'=>Auth::id(),
                'keterangan'=>$request->keterangan,
            ]);
            // insert to stock_keluar_detil from stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp);
            if ($stock_detil_temp->get()->count() > 0){
                foreach ($stock_detil_temp->get() as $row){
                    StockKeluarDetil::create([
                        'stock_keluar'=>$stockKeluar->id,
                        'id_produk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]);

                    // update or create inventory_real
                    $inventory_real = InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch)->get();
                    if ($inventory_real->count() > 0){
                        // update
                        InventoryReal::where('idProduk', $row->idProduk)
                            ->where('branchId', $request->branch)
                            ->update([
                                'stockOut'=>DB::raw('stockOut +'.$row->jumlah),
                            ]);
                    } else {
                        InventoryReal::create([
                            'idProduk'=>$row->idProduk,
                            'branchId'=>$request->branch,
                            'stockOut'=>$row->jumlah,
                        ]);
                    }
                }
            }
            // destroy stock_temp
            StockTemp::destroy($idTemp);
            // destroy stock_detil_temp
            $stock_detil_temp->delete();
            // destroy session stock
            DB::commit();
            session()->forget('stockKeluar');
            $jsonData = [
                'status'=>true
            ];
        } catch (\ModelNotFoundException $e) {
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e->getMessage(),
            ];
        }
        return response()->json($jsonData);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function checkSessionEdit($id)
    {
        //check temp
        $checkTemp = StockTemp::where('stockMasuk', $id)->where('jenisTemp', 'StockKeluar')->where('idUser', Auth::id());
        if ($checkTemp->get()->count() > 0){
            // jika temp sebelumnya ada, dipakai saja
            $temp = $checkTemp->latest()->first();
            // delete detil stock lama
            StockDetilTemp::where('stockTemp', $temp->id)->delete();
        } else {
            // jika temp tidak ada, buat baru
            $temp = $this->createSessionStock($id);
        }
        // insert detil
        $stock_keluar_detil = StockKeluarDetil::where('stock_keluar', $id)->get();
        if ($stock_keluar_detil->count() > 0)
        {
            foreach ($stock_keluar_detil as $row)
            {
                StockDetilTemp::create([
                    'stockTemp'=>$temp->id,
                    'idProduk'=>$row->id_produk,
                    'jumlah'=>$row->jumlah
                ]);
            }
        }
        return $temp;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        // get data from stock_keluar
        $stock_keluar = StockKeluar::with(['suppliers', 'customers', 'user', 'branchs'])->find($id);
        $stock = $this->checkSessionEdit($id);
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser,
            'id'=>$id,
            'kode'=>$stock_keluar->kode,
            'supplier'=>$stock_keluar->supplier,
            'namaSupplier'=>$stock_keluar->suppliers->namaSupplier,
            'branch'=>$stock_keluar->branch,
            'tgl_keluar'=>$stock_keluar->tgl_keluar->format('d-M-Y'),
            'update'=>true
        ];
        return view('pages.stock.stockKeluarTrans')->with($data);
    }

    public function update(Request $request)
    {
        $idTemp = $request->idTemp;
        $id_stock_keluar = $request->id;
        $tglKeluar = date('Y-m-d', strtotime($request->tgl_keluar));
        $data_lama = StockKeluar::find($id_stock_keluar);

        DB::beginTransaction();
        try {

            // delete stock_keluar_detil
            $delete_detil = StockKeluarDetil::where('stock_keluar', $id_stock_keluar);
            foreach ($delete_detil->get() as $row){
                // update inventory_real
                InventoryReal::where('idProduk', $row->id_produk)
                    ->where('branchId', $data_lama->branch)
                    ->update([
                        'stockOut'=>DB::raw('stockOut -'.$row->jumlah),
                    ]);
            }
            $delete_detil->delete();
            // update stock_keluar
            $update = StockKeluar::where('id', $id_stock_keluar)
                ->update([
                    'active_cash'=>session('closedCash'),
                    'tgl_keluar'=>$tglKeluar,
                    'branch'=>$request->branch,
                    'jenis_keluar'=>'nonPenjualan',
                    'supplier'=>$request->idSupplier,
                    'customer'=>null,
                    'penjualan' =>null,
                    'keterangan'=>$request->keterangan,
                    'users'=>Auth::id(),
                ]);
            // insert stock_keluar_detil by stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp)->get();
            if ($stock_detil_temp->count() > 0){
                foreach ($stock_detil_temp as $row){
                    StockKeluarDetil::create([
                        'stock_keluar'=>$id_stock_keluar,
                        'id_produk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]);
                    // update inventory_real
                    $update_inventory = InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch);
                    if($update_inventory->get()->count() > 0){
                        $update_inventory->update([
                            'stockOut'=>DB::raw('stockOut +'.$row->jumlah),
                        ]);
                    } else {
                        InventoryReal::create([
                            'idProduk'=>$row->idProduk,
                            'branchId'=>$request->branch,
                            'stockOut'=>$row->jumlah,
                        ]);
                    }
                }
            }
            // delete stock_temp
            StockTemp::destroy($idTemp);
            // delete stock_detil_temp
            StockDetilTemp::where('stockTemp', $id_stock_keluar)->delete();
            DB::commit();
            $jsonData = [
                'status'=>true
            ];

        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e->getMessage(),
            ];
        }
        return response()->json($jsonData);
    }

    public function destroy($id)
    {
        $action = StockKeluar::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    public function resetStockKeluarToReal()
    {
        $update = InventoryReal::whereNotNull('idProduk')->update(['stockOut'=>0]);
    }

    public function stockKeluarToReal()
    {
        // get stock opname with branch
        $stockAll = StockKeluarDetil::with(['stockKeluar'=>function($query){
            $query->where('active_cash', session('ClosedCash'));
        }])
            ->get();
        DB::beginTransaction();
        try {
            // update or insert to stock real
            $this->resetStockKeluarToReal();
            foreach ($stockAll as $row){
                InventoryReal::updateOrInsert(
                    [
                        'idProduk'=>$row->id_produk,
                        'branchId'=>$row->stockKeluar->branch,
                    ],
                    [
                        'stockOut'=>DB::raw('stockOut +'.$row->jumlah),
                    ]
                );
            }
            DB::commit();
            $hasil = ['status'=>true];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $hasil = ['status'=>false, 'keterangan'=>$e];
        }
        return $hasil;
    }
}
