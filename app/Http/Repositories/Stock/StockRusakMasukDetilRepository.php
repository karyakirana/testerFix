<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockMasukRusakDetil;

class StockRusakMasukDetilRepository
{
    public function lisData($idStockRusakIn)
    {
        //
    }

    public static function create($dataDetil)
    {
        return StockMasukRusakDetil::create([
            'stock_masuk_rusak_id'=>$dataDetil->stock_masuk_rusak_id,
            'produk_id'=>$dataDetil->produk_id,
            'jumlah'=>$dataDetil->jumlah
        ]);
    }

    public function update($dataStockRusakInDetil)
    {
        return StockMasukRusakDetil::where('id', $dataStockRusakInDetil)
            ->update($dataStockRusakInDetil);
    }

    public function destroy($idDetilStockRusakIn)
    {
        return StockMasukRusakDetil::destroy($idDetilStockRusakIn);
    }

    public function destroyByMaster($idStockRusakIn)
    {
        return StockMasukRusakDetil::where('idStockRusakIn', $idStockRusakIn)
            ->delete();
    }
}
