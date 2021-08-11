<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use App\Models\Stock\StockTemp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMasukController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockMasukList');
    }

    public function indexByBranch($idBranch)
    {
        return view('pages.stock.stockMasukList', ['idBranch'=>$idBranch]);
    }

    public function kode()
    {
        $data = StockMasuk::where('activeCash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SM/".date('Y');
        return $id;
    }

    private function createSessionStock($idStockMasuk = null)
    {
        // insert stock_temp
        return StockTemp::create([
            'jenisTemp'=>'StockMasuk',
            'idUser'=>Auth::id(),
            'stockMasuk'=>$idStockMasuk
        ]);
    }

    private function checkLastCart()
    {
        // check session stock
        if (session('stockMasuk'))
        {
            // jika ada langsung ambil data stock
            $stock = StockTemp::find(session('stockMasuk'));
        } else {
            // check last temp
            $lastTemp = StockTemp::where('idUser', Auth::id())->where('jenisTemp', 'StockMasuk')->whereNull('stockMasuk');
            if ($lastTemp->count() > 0)
            {
                // jika ada
                $stock = $lastTemp->latest()->first();
            } else {
                $stock = $this->createSessionStock();
            }
            session()->put(['stockMasuk'=>$stock->id]);
        }
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser
        ];
        return $data;
    }

    public function create()
    {
        return view('pages.stock.stockMasukTrans', $this->checkLastCart());
    }

    public function store(Request $request)
    {
        $idTemp = $request->idTemp;
        $kode = $this->kode();
        $tglMasuk = date('Y-m-d', strtotime($request->tglMasuk));

        DB::beginTransaction();
        try {
            // insert to stock_keluar
            $stockMasuk = StockMasuk::create([
                'activeCash'=>session('ClosedCash'),
                'tglMasuk'=>$tglMasuk,
                'kode'=>$kode,
                'idBranch'=>$request->branch,
                'idSupplier'=>$request->idSupplier,
                'keterangan'=>$request->keterangan,
                'idUser'=>Auth::id(),
            ]);
            // insert to stock_keluar_detil from stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp);
            if ($stock_detil_temp->get()->count() > 0){
                foreach ($stock_detil_temp->get() as $row){
                    StockMasukDetil::create([
                        'idStockMasuk'=>$stockMasuk->id,
                        'idProduk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]);
                    // update or create inventory real
                    $inventory_real = InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch)->get();
                    if ($inventory_real->count() > 0){
                        // update
                        InventoryReal::where('idProduk', $row->idProduk)
                            ->where('branchId', $request->branch)
                            ->update([
                                'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                                'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                            ]);
                    } else {
                        InventoryReal::create([
                            'idProduk'=>$row->idProduk,
                            'branchId'=>$request->branch,
                            'stockIn'=>$row->jumlah,
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
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
            session()->forget('stockMasuk');
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
        $checkTemp = StockTemp::where('stockMasuk', $id)->where('jenisTemp', 'StockMasuk')->where('idUser', Auth::id());
        if ($checkTemp->get()->count() > 0){
            // jika temp sebelumnya ada, dipakai saja
            $temp = $checkTemp->latest()->first();
            // delete detil_temp stock lama
            StockDetilTemp::where('stockTemp', $temp->id)->delete();
        } else {
            // jika temp tidak ada, buat baru
            $temp = $this->createSessionStock($id);
        }
        // insert detil
        $stock_masuk_detil = StockMasukDetil::where('idStockmasuk', $id)->get();
        if ($stock_masuk_detil->count() > 0)
        {
            foreach ($stock_masuk_detil as $row)
            {
                StockDetilTemp::create([
                    'stockTemp'=>$temp->id,
                    'idProduk'=>$row->idProduk,
                    'jumlah'=>$row->jumlah
                ]);
            }
        }
        return $temp;
    }

    public function edit($id)
    {
        // get data from stock_keluar
        $stock_masuk = StockMasuk::with(['supplier', 'user', 'branch'])->find($id);
        $stock = $this->checkSessionEdit($id);
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser,
            'id'=>$id,
            'kode'=>$stock_masuk->kode,
            'supplier'=>$stock_masuk->idSupplier,
            'namaSupplier'=>$stock_masuk->supplier->namaSupplier,
            'branch'=>$stock_masuk->idBranch,
            'tgl_keluar'=>$stock_masuk->tglMasuk->format('d-M-Y'),
            'update'=>true
        ];
        return view('pages.stock.stockMasukTrans')->with($data);
    }

    public function update(Request $request)
    {
        $idTemp = $request->idTemp;
        $id_stock_masuk = $request->id;
        $tglMasuk = date('Y-m-d', strtotime($request->tglMasuk));
        $data_lama = StockMasuk::find($id_stock_masuk);

        DB::beginTransaction();
        try {
            // delete stock_keluar_detil
            $delete_detil = StockMasukDetil::where('idStockMasuk', $id_stock_masuk);
            foreach ($delete_detil->get() as $row){
                // update inventory_real
                InventoryReal::where('idProduk', $row->idProduk)
                    ->where('branchId', $data_lama->idBranch)
                    ->update([
                        'stockIn'=>DB::raw('stockIn -'.$row->jumlah),
                        'stockNow'=>DB::raw('stockNow -'.$row->jumlah),
                    ]);
            }
            // update stock_keluar
            $update = StockMasuk::where('id', $id_stock_masuk)
                ->update([
                    'tglMasuk'=>$tglMasuk,
                    'idBranch'=>$request->branch,
                    'idSupplier'=>$request->idSupplier,
                    'keterangan'=>$request->keterangan,
                    'idUser'=>Auth::id(),
                ]);
            // delete stock_masuk_detil
            StockMasukDetil::where('idStockMasuk', $id_stock_masuk)->delete();
            // insert stock_masuk_detil by stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp)->get();
            if ($stock_detil_temp->count() > 0){
                foreach ($stock_detil_temp as $row){
                    StockMasukDetil::create([
                        'idStockMasuk'=>$id_stock_masuk,
                        'idProduk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]);

                    // update inventory_real
                    $update_inventory = InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch);
                    if($update_inventory->get()->count() > 0){
                        $update_inventory->update([
                            'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                    } else {
                        InventoryReal::create([
                            'idProduk'=>$row->idProduk,
                            'branchId'=>$request->branch,
                            'stockIn'=>$row->jumlah,
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                    }
                }
            }
            // delete stock_temp
            StockTemp::destroy($idTemp);
            // delete stock_detil_temp
            StockDetilTemp::where('stockTemp', $idTemp)->delete();
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
        $action = StockMasuk::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
