<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockAkhirController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockAkhirList');
    }

    public function kode()
    {
        //
    }

    public function store(Request $request)
    {
        // simpan
    }

    public function show()
    {
        // show detil
    }

    public function edit($id)
    {
        return view('pages.stock.stockAkhirTrans');
    }

    public function update(Request $request)
    {
        // update
    }

    public function destroy($id)
    {
        // delete
    }
}
