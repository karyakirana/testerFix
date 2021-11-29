<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\PiutangPenjualan;

class PiutangRepository
{
    public function setPiutangFromPenjualan($dataPenjualan)
    {
        return PiutangPenjualan::create([
            'penjualan_id'=>$dataPenjualan['idPenjualan'],
            'status_bayar'=>$dataPenjualan['statusBayar'],
            'nominal'=>$dataPenjualan['totalBayar'],
            'user_id'=>$dataPenjualan['userId'],
        ]);
    }
}
