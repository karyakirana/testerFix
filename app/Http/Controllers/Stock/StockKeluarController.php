<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockDetilTemp;
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
            if ($lastTemp->count > 0)
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
        //
    }

    public function store(Request $request)
    {
        $idTemp = $request->idTemp;
        $kode = $this->kode();

        DB::beginTransaction();
        try {
            // insert to stock_keluar
            // insert to stock_keluar_detil
            // destroy stock_temp
            // destroy stock_detil_temp
            // destroy session stock
            DB::commit();
        } catch (\ModelNotFoundException $e) {
            DB::rollBack();
        }
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
        //
    }
}
