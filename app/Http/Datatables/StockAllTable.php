<?php

namespace App\Http\Datatables;

use App\Models\Stock\InventoryReal;
use App\Models\Stock\InventoryRusak;
use Yajra\DataTables\DataTables;

class StockAllTable {

    public function StockAllBranch(){

        $data = InventoryReal::with(['branch', 'produk'])->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->addColumn('branch', function($row){
                return $row->branch->branchName ?? '';
            })
            ->addColumn('tersedia', function($row){
                return $row->stockOpname + $row->stockIn - $row->stockOut;
            })
            ->rawColumns(['produk'])
            ->make(true);

    }

    public function StockByBranch($branch)
    {
        $data = InventoryReal::with(['branch', 'produk'])->where('branchId', $branch)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->addColumn('branch', function($row){
                return $row->branch->branchName ?? '';
            })
            ->addColumn('tersedia', function($row){
                return $row->stockOpname + $row->stockIn - $row->stockOut;
            })
            ->addColumn('stockKeluar', function($row){
                return '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnStockKeluar" data-value="'.$row->idProduk.'" title="show">'.$row->stockOut.'</a>';
            })
            ->addColumn('stockAkhir', function($row){
                return '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnStockOpname" data-value="'.$row->idProduk.'" title="show">'.$row->stockOpname.'</a>';
            })
            ->addColumn('stockMasuk', function($row){
                return '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnStockIn" data-value="'.$row->idProduk.'" title="show">'.$row->stockIn.'</a>';
            })
            ->rawColumns(['produk', 'stockKeluar', 'stockAkhir', 'stockMasuk'])
            ->make(true);
    }

    public function stockRusakAllBranch()
    {
        $data = InventoryRusak::with(['branch', 'produk'])->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->addColumn('branch', function($row){
                return $row->branch->branchName ?? '';
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

    public function StockRusakByBranch($branch)
    {
        $data = InventoryRusak::with(['branch', 'produk'])->where('branchId', $branch)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->addColumn('branch', function($row){
                return $row->branch->branchName ?? '';
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

}
