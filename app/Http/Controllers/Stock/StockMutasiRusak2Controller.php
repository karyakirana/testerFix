<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockMutasiRusak2Controller extends Controller
{
    public function index()
    {
        return view('pages.stock.mutasi-stock-rusak-rusak-list ');
    }

    public function transaksiMutasiRusakRusak()
    {
        return view('pages.stock.stock-mutasi-rusak2');
    }
}
