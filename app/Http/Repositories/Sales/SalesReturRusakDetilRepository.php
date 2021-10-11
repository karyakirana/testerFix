<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\ReturRusakDetil;

class SalesReturRusakDetilRepository
{
    public static function create($dataDetil)
    {
        return ReturRusakDetil::create([
            'retur_rusak_id'=>$dataDetil->retur_rusak_id,
            'id_produk'=>$dataDetil->produk_id,
            'jumlah'=>$dataDetil->jumlah,
            'harga'=>$dataDetil->harga,
            'diskon'=>$dataDetil->diskon,
            'sub_total'=>$dataDetil->sub_total
        ]);
    }
}
