<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('pages.sales.penjualan-index');
    }

    public function create()
    {
        return view('pages.sales.penjualan-transaksi');
    }

    public function edit($id_jual)
    {
        return view('pages.sales.penjualan-transaksi', [
            'id_jual'=>$id_jual
        ]);
    }
}
