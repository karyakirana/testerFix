<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
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
        //
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

                session()->put(['stockMasuk'=>$stock->id]);

            } else {
                $stock = $this->createSessionStock();
                session()->put(['stockMasuk'=>$stock->id]);
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
        //
    }

    public function store(Request $request)
    {
        $idTemp = $request->idTemp;
        $kode = $this->kode();
        $tglMasuk = date('Y-m-d', strtotime($request->tgl_masuk));

        DB::beginTransaction();
        try {
            // insert to stock_keluar
            $stockMasuk = StockMasuk::create([
                'activeCash'=>session('closedCash'),
                'tglMasuk'=>$tglMasuk,
                'kode'=>$kode,
                'idBranch'=>$request->branch,
                'idSupplier'=>$request->supplier,
                'keterangan'=>$request->keterangan,
                'users'=>Auth::id(),
            ]);
            // insert to stock_keluar_detil from stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stock_temp', $idTemp);
            if ($stock_detil_temp->get()->count() > 0){
                foreach ($stock_detil_temp->get as $row){
                    StockMasukDetil::create([
                        'idStockMasuk'=>$stockMasuk->id,
                        'idProduk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]);
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

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        $idTemp = $request->idTemp;
        $id_stock_masuk = $request->id;
        $tglMasuk = date('Y-m-d', strtotime($request->tgl_masuk));

        DB::beginTransaction();
        try {

            // update stock_keluar
            $update = StockKeluar::where('id', $id_stock_masuk)
                ->update([
                    'tglMasuk'=>$tglMasuk,
                    'idBranch'=>$request->branch,
                    'idSupplier'=>$request->supplier,
                    'keterangan'=>$request->keterangan,
                    'users'=>Auth::id(),
                ]);
            // delete stock_keluar_detil
            StockMasukDetil::where('idStockMasuk', $id_stock_masuk)->delete();
            // insert stock_keluar_detil by stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp)->get();
            if ($stock_detil_temp->count() > 0){
                foreach ($stock_detil_temp as $row){
                    StockMasukDetil::create([
                        'idStockMasuk'=>$id_stock_masuk,
                        'idProduk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]);
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
