<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\MutasiGudangRusak;

class MutasiGudangRusakRepository
{
    public function getDataBySearch($search)
    {
        return MutasiGudangRusak::where('activeCash', session('ClosedCash'))
            ->paginate(10);
    }
}
