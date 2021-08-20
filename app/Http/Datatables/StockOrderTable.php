<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockOrder;
use Yajra\DataTables\DataTables;

class StockOrderTable {

    public function stockOrderList()
    {
        $data = StockOrder::where('activeCash', session('ClosedCash'))->get();
        return DataTables::of($data);
    }

}
