<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
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
                'active_cash'=>session('closedCash'),
                'tgl_keluar'=>$tglKeluar,
                'kode'=>$kode,
                'branch'=>$request->branch,
                'jenis_keluar'=>'nonPenjualan',
                'supplier'=>$request->supplier,
                'customer'=>null,
                'penjualan' =>null,
                'users'=>Auth::id(),
            ]);
            // insert to stock_keluar_detil from stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stock_temp', $idTemp);
            if ($stock_detil_temp->get()->count() > 0){
                foreach ($stock_detil_temp->get as $row){
                    StockKeluarDetil::create([
                        'stock_keluar'=>$stockKeluar->id,
                        'id_produk'=>$row->idProduk,
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

    /**
     * @param $id
     * @return mixed
     */
    public function checkSessionEdit($id)
    {
        //check temp
        $checkTemp = StockTemp::where('stockMasuk', $id)->where('idUser', Auth::id());
        if ($checkTemp->count() > 0){
            // jika temp sebelumnya ada, dipakai saja
            $temp = $checkTemp->latest()->first();
            // delete detil stock lama
            StockDetilTemp::where('stockTemp', $temp->id)->delete();
        } else {
            // jika temp tidak ada, buat baru
            $temp = $this->createSessionStock($id);
        }
        return $temp;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        return view('pages.stock.stockKeluar', $this->checkSessionEdit($id));
    }

    public function update(Request $request)
    {
        $idTemp = $request->idTemp;
        $id_stock_keluar = $request->id;
        $tglKeluar = date('Y-m-d', strtotime($request->tgl_keluar));

        DB::beginTransaction();
        try {

            // update stock_keluar
            $update = StockKeluar::where('id', $id_stock_keluar)
                ->update([
                    'active_cash'=>session('closedCash'),
                    'tgl_keluar'=>$tglKeluar,
                    'branch'=>$request->branch,
                    'jenis_keluar'=>'nonPenjualan',
                    'supplier'=>$request->supplier,
                    'customer'=>null,
                    'penjualan' =>null,
                    'users'=>Auth::id(),
                ]);
            // delete stock_keluar_detil
            StockKeluarDetil::where('stock_keluar', $id_stock_keluar)->delete();
            // insert stock_keluar_detil by stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp)->get();
            if ($stock_detil_temp->count() > 0){
                foreach ($stock_detil_temp as $row){
                    StockKeluarDetil::create([
                        'stock_keluar'=>$id_stock_keluar,
                        'id_produk'=>$row->idProduk,
                        'jumlah'=>$row->jumlah
                    ]);
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
}
