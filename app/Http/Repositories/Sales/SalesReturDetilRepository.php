<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\ReturBaikDetil;

class SalesReturDetilRepository
{
    public function create($dataReturDetil)
    {
        return ReturBaikDetil::create([
            'id_return'=>$dataReturDetil->id_return,
            'id_produk'=>$dataReturDetil->id_produk,
            'jumlah'=>$dataReturDetil->jumlah,
            'harga'=>$dataReturDetil->harga,
            'diskon'=>$dataReturDetil->diskon,
            'sub_total'=>$dataReturDetil->sub_total
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
