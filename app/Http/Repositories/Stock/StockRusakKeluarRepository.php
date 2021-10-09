<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockKeluarRusak;
use Illuminate\Support\Facades\Auth;

class StockRusakKeluarRepository
{
    public function getData()
    {
        return StockKeluarRusak::all();
    }

    public function create($data)
    {
        return StockKeluarRusak::create(
            [
                'activeCash'=>session('ClosedCash'),
                'kode',
                'supplier_id',
                'user_id'=>Auth::id(),
                'tgl_keluar_rusak',
                'keterangan',
            ]
        );
    }

    public function update($id, $data)
    {
        return StockKeluarRusak::where('id', $id)
            ->update($data);
    }

    public function destroy($id)
    {
        return StockKeluarRusak::destroy($id);
    }
}
