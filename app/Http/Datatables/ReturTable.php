<?php

namespace App\Http\Datatables;

use App\Models\Sales\ReturBaik;

class ReturTable {

    // Table List Retur Baik
    public function returBaik($branch = null)
    {
        $data = ReturBaik::with();
    }

}
