<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Sales\PenjualanTemp;
use App\Models\Stock\StockOrder;
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

    public function createTemp($idStockOrder)
    {
        $create = StockTemp::create([
            'jenisTemp' => 'StockOrder',
            'idSales' => Auth::id(),
            'stockMasuk'=> $idStockOrder,
        ]);

        return $create;
    }

    private function checkLastCart()
    {
        // check session stock
        if (session('stockMasuk'))
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
                $stock = $this->createTemp();
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

    public function kodeStockOrder()
    {
        $data = StockOrder::where('activeCash', session('ClosedCash'))->latest('kode')->first();
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
        $kode = $this->kode();
        $tglOrder = date('Y-m-d', strtotime($request->tglOrder));

        DB::beginTransaction();
        try {
            // insert stock Order

            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
