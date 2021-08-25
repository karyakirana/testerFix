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

    private function stockTemp($idStockOrder=null)
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

    public function checkSessionEdit($id)
    {
        //check temp
        $checkTemp = StockTemp::where('stockMasuk', $id)->where('jenisTemp', 'StockOrder')->where('idUser', Auth::id());
        if ($checkTemp->get()->count() > 0){
            // jika temp sebelumnya ada, dipakai saja
            $temp = $checkTemp->latest()->first();
            // delete detil_temp stock lama
            StockDetilTemp::where('stockTemp', $temp->id)->delete();
        } else {
            // jika temp tidak ada, buat baru
            $temp = $this->stockTemp($id);
        }
        // insert detil
        $stock_preorder = StockOrderDetil::where('stock_preorder', $id)->get();
        if ($stock_preorder->count() > 0)
        {
            foreach ($stock_preorder as $row)
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

    public function edit($id)
    {
        // get data from stock_keluar
        $stock_order = StockOrder::with(['suppliers', 'user'])->find($id);
        $stock = $this->checkSessionEdit($id);
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser,
            'id'=>$id,
            'kode'=>$stock_order->kode,
            'supplier'=>$stock_order->supplier,
            'namaSupplier'=>$stock_order->suppliers->namaSupplier,
            'tgl_keluar'=>$stock_order->tgl_order->format('d-M-Y'),
            'update'=>true
        ];
        return view('pages.stock.stockOrderTrans', $data);
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
                'activeCash'=>session('ClosedCash'),
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
                    'stock_preorder'=>$stockOrder->id,
                    'id_produk'=>$row->idProduk,
                    'jumlah'=>$row->jumlah
                ]);
            }
            // delete temp
            StockDetilTemp::where('stockTemp', $idTemp)->delete();
            StockTemp::destroy($idTemp);
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

    public function update(Request $request)
    {
        $idTemp = $request->idTemp;
        $tglOrder = date('Y-m-d', strtotime($request->tglOrder));

        $dataLama = StockOrder::find($request->id);
        DB::beginTransaction();
        try {
            // delete stock_detil_temp
            StockOrderDetil::where('stock_preorder', $dataLama->id)->delete();
            // update data
            $update = StockOrder::where('id', $request->id)->update([
                'supplier'=>$request->idSupplier,
                'tgl_order'=>$tglOrder,
                'status'=>'dibuat',
                'status_bayar'=>'belum',
                'pembuat'=>Auth::id(),
                'keterangan'=>$request->keterangan
            ]);
            // insert detil
            $stockTemp = StockDetilTemp::where('stockTemp', $idTemp)->get();
            foreach ($stockTemp as $row){
                StockOrderDetil::create([
                    'stock_preorder'=>$dataLama->id,
                    'id_produk'=>$row->idProduk,
                    'jumlah'=>$row->jumlah
                ]);
            }
            // delete temp
            StockDetilTemp::where('stockTemp', $idTemp)->delete();
            StockTemp::destroy($idTemp);
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

    public function destroy($id)
    {
        //
    }
}
