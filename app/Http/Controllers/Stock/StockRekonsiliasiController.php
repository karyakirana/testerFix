<?php

/**
 * Perpindahan Stock dari Cabang 1 ke Cabang lain
 */

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\BranchStock;
use Illuminate\Http\Request;

class StockRekonsiliasiController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockRealAll');
    }

    public function indexyByBranch($branchId)
    {
        $branch = BranchStock::find($branchId);
        $data = [
            'branchName' => $branch->branchName,
            'idBranch'=> $branchId,
        ];
        return view('pages.stock.stockRealByBranch', $data);
    }
}
