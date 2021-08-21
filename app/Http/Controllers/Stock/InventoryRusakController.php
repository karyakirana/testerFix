<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryRusakController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockRealRusak');
    }

    public function byBranch($id)
    {
        return view('pages.stock.stockrealRusakByBranch', ['idBranch'=>$id]);
    }
}
