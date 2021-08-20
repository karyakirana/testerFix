<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockOrder;
use Illuminate\Http\Request;

class StockOrderController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockOrder');
    }

    public function create()
    {
        return view('pages.stock.stockOrderTrans');
    }

    public function show($id)
    {
        $data = StockOrder::with(['suppliers', 'user'])->find($id);
        return response()->json($data);
    }

    public function print($id)
    {
        //
    }

    public function edit()
    {
        return view('pages.stock.stockOrderTrans');
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
