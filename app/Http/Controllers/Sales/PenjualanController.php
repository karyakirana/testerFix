<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function create()
    {
        return view('pages.sales.penjualan-transaksi');
    }
}
