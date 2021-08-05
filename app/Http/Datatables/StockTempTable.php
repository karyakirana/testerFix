<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockDetilTemp;
use Yajra\DataTables\DataTables;

class StockTempTable {

    public function stockTrans(String $idStockTemp)
    {
        $data = StockDetilTemp::with('produk')->where('stockTemp', $idStockTemp)->get();
        //
    }
}
