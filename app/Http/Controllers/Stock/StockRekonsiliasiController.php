<?php

/**
 * Perpindahan Stock dari Cabang 1 ke Cabang lain
 */

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\BranchStock;
use App\Models\Stock\StockAkhir;
use App\Models\Stock\StockAkhirDetil;
use App\Models\Stock\StockKeluarDetil;
use App\Models\Stock\StockMasukDetil;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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

    public function stockKeluarByProduk($id_produk, $branch)
    {
        $data = StockKeluarDetil::leftJoin('stock_keluar', 'stock_keluar_detil.stock_keluar', '=', 'stock_keluar.id')
            ->where('stock_keluar.branch', $branch)
            ->where('active_cash', session('ClosedCash'))
            ->where('id_produk', $id_produk)
            ->orderBy('kode', 'DESC')
            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function stockOpnameByProduk($id_produk, $branch)
    {
        $data = StockAkhirDetil::leftJoin('stockakhir_master', 'stockakhir.id_stock_akhir', '=', 'stockakhir_master.id')
            ->where('stockakhir_master.branchId', $branch)
            ->where('activeCash', session('ClosedCash'))
            ->where('id_produk', $id_produk)
            ->orderBy('kode', 'DESC')
            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function stockMasukByProduk($id_produk, $branch)
    {
        $data = StockMasukDetil::leftJoin('stockmasuk', 'stockmasukdetil.idStockMasuk', '=', 'stockmasuk.id')
            ->where('stockmasuk.idBranch', $branch)
            ->where('activeCash', session('ClosedCash'))
            ->where('idProduk', $id_produk)
            ->orderBy('kode', 'DESC')
            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
