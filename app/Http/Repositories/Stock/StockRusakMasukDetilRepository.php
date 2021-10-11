<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockMasukRusak;

class StockRusakMasukDetilRepository
{
    public function lisData($idStockRusakIn)
    {
        //
    }

    public static function create($dataDetil)
    {
        return StockMasukRusak::create([
            'stock_masuk_rusak_id'=>$dataDetil->stock_masuk_rusak_id,
            'produk_id'=>$dataDetil->produk_id,
            'jumlah'=>$dataDetil->jumlah
        ]);
    }

    public function update($dataStockRusakInDetil)
    {
        //
    }

    public function destroy($idDetilStockRusakIn)
    {
        //
    }

    public function destroyByMaster($idStockRusakIn)
    {
        //
    }
}
