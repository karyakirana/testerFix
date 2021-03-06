<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockMasukDetil;

class StockMasukDetilRepository
{
    public function create($dataStockDetil)
    {
        return StockMasukDetil::create([
            'idStockMasuk'=>$dataStockDetil->id_stock_masuk,
            'idProduk'=>$dataStockDetil->id_produk,
            'jumlah'=>$dataStockDetil->jumlah
        ]);
    }

    public function update($id, $dataStockDetil)
    {
        return StockMasukDetil::where('id', $id)
            ->update($dataStockDetil);
    }

    public function destroy($id)
    {
        return StockMasukDetil::destroy($id);
    }

    public function destroyByStockMasuk($idStockMasuk)
    {
        return StockMasukDetil::where('idStockMasuk', $idStockMasuk)
            ->delete();
    }
}
