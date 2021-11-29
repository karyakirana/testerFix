<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockRusakController extends Controller
{
    public function index()
    {
        return view('pages.stock.stock-rusak-index');
    }

    public function stockMasukRusakList()
    {
        return view('pages.stock.stock-rusak-masuk-list');
    }

    public function stockMasukRusak()
    {
        return view('pages.stock.stock-masuk-rusak1');
    }

    public function stockKeluarRusak()
    {
        return view('pages.stock.stock-keluar-rusak1');
    }
}
