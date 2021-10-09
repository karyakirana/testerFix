<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Stock\InventoryRusakRealRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InventoryRusakController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockRealRusak');
    }

    public function datatable($branch = null)
    {
        $inventory = new InventoryRusakRealRepository();
        $dataInventory = $inventory->getData($branch);

        return DataTables::of($dataInventory)
            ->addIndexColumn()
            ->make(true);
    }

    public function byBranch($id)
    {
        return view('pages.stock.stockrealRusakByBranch', ['idBranch'=>$id]);
    }
}
