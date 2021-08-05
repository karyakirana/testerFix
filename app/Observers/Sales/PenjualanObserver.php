<?php

namespace App\Observers\Sales;

use App\Models\Sales\Penjualan;

class PenjualanObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true; // dijalankan setelah commit berhasil

    public function created(Penjualan $penjualan)
    {
        // insert stock keluar
    }
}
