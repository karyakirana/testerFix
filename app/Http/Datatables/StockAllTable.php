<?php

namespace App\Http\Datatables;

use App\Models\Stock\InventoryReal;
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
            ->rawColumns(['produk'])
            ->make(true);
    }

}
