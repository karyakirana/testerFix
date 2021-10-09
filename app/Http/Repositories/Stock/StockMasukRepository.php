<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockMasuk;

class StockMasukRepository
{
    public function kode()
    {
        //
    }

    public function create($dataStockIn)
    {
        return StockMasuk::create([
            'activeCash',
            'kode',
            'idBranch',
            'idSupplier',
            'idUser',
            'tglMasuk',
            'nomotPo',
            'keterangan',
            'id_penjualan',
            'jenis_masuk'
        ]);
    }

    public function update($id, $dataStockIn)
    {
        return StockMasuk::where('id', $id)
            ->update($dataStockIn);
    }

    public function destroy($id)
    {
        return StockMasuk::destroy($id);
    }
}
