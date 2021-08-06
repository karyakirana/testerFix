<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockDetilTemp;
use Illuminate\Http\Request;

class StockTempController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah'=> 'required|integer',
        ]);

        $jumlah = (int) $request->jumlah;
        $action = StockDetilTemp::updateOrCreate(
            // where
            [
                'id'=>$request->idTransDetil
            ],
            [
                'stockTemp'=>$request->idTemp,
                'idProduk'=>$request->idProduk,
                'jumlah'=>$jumlah,
            ]
        );
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    public function edit($id)
    {
        return StockDetilTemp::find($id);
    }

    public function destroy($id)
    {
        $action = StockDetilTemp::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
