<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MutasiBaikRusakController extends Controller
{
    public function index()
    {
        return view('pages.stock.mutasi-baik-rusak-index');
    }

    public function create()
    {
        return view('pages.stock.mutasi-baik-rusak-transaksi');
    }
}
