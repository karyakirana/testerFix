<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockOrder;
use App\Models\Stock\StockOrderDetil;
use Dflydev\DotAccessData\Data;
use Yajra\DataTables\DataTables;

class StockOrderTable {

    public function stockOrderList()
    {
        $data = StockOrder::with(['suppliers', 'user'])->where('activeCash', session('ClosedCash'))->get();
        return DataTables::of($data)
            ->addColumn('suppliers', function ($row){
                return $row->suppliers->namaSupplier ?? '';
            })
            ->addColumn('user', function ($row){
                return $row->user->name ?? '';
            })
            ->addColumn('Action', function($row){
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.$row->id.'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $show.$edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function stockOrderDetilList($idStockOrder)
    {
        $data = StockOrderDetil::with('produk')
            ->where('kodePreorder', $idStockOrder)
            ->get();
        return DataTables::of($data)
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

}
