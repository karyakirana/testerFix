<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\ReturBaikDetil;

class SalesReturDetilRepository
{
    public function kode()
    {
        //
    }

    public function create($dataReturDetil)
    {
        return ReturBaikDetil::create([
            'id_return',
            'id_produk',
            'jumlah',
            'harga',
            'diskon',
            'sub_total'
        ]);
    }

    public function update($id, $dataReturDetil)
    {
        return ReturBaikDetil::where('id', $id)
            ->update($dataReturDetil);
    }

    public function destroy($id)
    {
        return ReturBaikDetil::destroy($id);
    }

    public function destroyByRetur($idRetur)
    {
        return ReturBaikDetil::where('id_return', $idRetur)
            ->delete();
    }
}
