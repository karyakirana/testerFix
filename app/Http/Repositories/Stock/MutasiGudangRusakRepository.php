<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\MutasiGudangRusak;
use App\Models\Stock\MutasiGudangRusakDetail;
class MutasiGudangRusakRepository
{
    public function getDataBySearch($search)
    {
        return MutasiGudangRusak::where('activeCash', session('ClosedCash'))
            ->paginate(10);
    }

    public function storeData(array $data)
    {
        return MutasiGudangRusakDetail::create([
            'mutasi_gudang_id'=>$data['mutasi_gudang_id'],
            'produk_id'=>$data['produk_id'],
            'jumlah'=>$data['jumlah'],
        ]);
    }
}
