<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesBadReturController extends Controller
{
    public function index()
    {
        return view();
    }

    public function idReturRusak()
    {
        return;
    }

    public function kodeStockRusak()
    {
        return;
    }

    public function store(Request $request)
    {
        $idRetur = $this->idReturRusak();
        $kodeStockMasuk = $this->kodeStockRusak();
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));

        DB::beginTransaction();
        try {
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }
}
