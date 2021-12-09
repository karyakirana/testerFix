<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockOpnameRusak extends Controller
{
    public function index()
    {
        return view('pages.stock.stock-opname-rusak-index');
    }

    public function create()
    {
        return view('pages.stock.stock-opname-rusak-trans');
    }
}
