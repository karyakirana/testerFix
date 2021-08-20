<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockOrder;
use App\Models\Stock\StockOrderDetil;
use App\Models\Stock\StockTemp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOrderController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockOrder');
    }

    private function stockTemp($idStockOrder)
    {
        // insert stock_temp
        return StockTemp::create([
            'jenisTemp'=>'StockOrder',
            'idUser'=>Auth::id(),
            'stockMasuk'=>$idStockOrder
        ]);
    }

    private function checkLastCart()
    {
        // check session stock
        if (session('stockOrder'))
        {
            // jika ada langsung ambil data stock
            $stock = StockTemp::find(session('stockOrder'));
        } else {
            // check last temp
            $lastTemp = StockTemp::where('idUser', Auth::id())->where('jenisTemp', 'StockOrder')->whereNull('stockMasuk');
            if ($lastTemp->count() > 0)
            {
                // jika ada
                $stock = $lastTemp->latest()->first();
            } else {
                $stock = $this->stockTemp();
            }
            session()->put(['stockOrder'=>$stock->id]);
        }
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser
        ];
        return $data;
    }

    public function create()
    {
        return view('pages.stock.stockOrderTrans', $this->checkLastCart());
    }

    public function show($id)
    {
        $data = StockOrder::with(['suppliers', 'user'])->find($id);
        return response()->json($data);
    }

    public function print($id)
    {
        //
    }

    public function edit()
    {
        return view('pages.stock.stockOrderTrans');
    }

    private function kodeStockOrder()
    {
        $data = StockOrder::where('activeCash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SO/".date('Y');
        return $id;
    }

    public function store(Request $request)
    {
        $idTemp = $request->idTemp;
        $kode = $this->kodeStockOrder();
        $tglOrder = date('Y-m-d', strtotime($request->tglOrder));

        DB::beginTransaction();
        try {
            $stockOrder = StockOrder::create([
                'kode'=>$kode,
                'supplier'=>$request->idSupplier,
                'tgl_order'=>$tglOrder,
                'status'=>'dibuat',
                'status_bayar'=>'belum',
                'pembuat'=>Auth::id(),
                'keterangan'=>$request->keterangan
            ]);
            $stockTemp = StockDetilTemp::where('stockTemp', $idTemp)->get();
            foreach ($stockTemp as $row){
                StockOrderDetil::create([
                    'stock_preorder'=>$idTemp,
                    'id_produk'=>$row->idProduk,
                    'jumlah'=>$row->jumlah
                ]);
            }
            // delete temp
            StockDetilTemp::where('stockTemp', $idTemp)->delete();
            StockTemp::destroy($idTemp);
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        $idTemp = $request->idTemp;
        $tglOrder = date('Y-m-d', strtotime($request->tglOrder));

        $detilLama = StockDetilTemp::where('stockTemp', $idTemp);
        DB::beginTransaction();
        try {
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }

    public function destroy($id)
    {
        //
    }
}
