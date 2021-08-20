<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockOrder;
use App\Models\Stock\StockOrderDetil;
use Yajra\DataTables\DataTables;

class StockOrderTable {

    public function stockOrderList()
    {
        $data = StockOrder::with(['suppliers'])->where('activeCash', session('ClosedCash'))->get();
        return DataTables::of($data)
            ->addColumn('suppliers', function($row){
                return $row->suppliers->namaSupplier ?? '';
            })
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.str_replace('/', '-', $row->id_jual).'" title="edit"><i class="la la-edit"></i></a>';
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.str_replace('/', '-', $row->id_jual).'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $delete = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btndelete" data-value="'.$row->id_jual.'" title="delete"><i class="flaticon2-trash"></i></a>';
                $print = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnPrint" data-value="'.str_replace('/', '-', $row->id_jual).'" title="print"><i class="flaticon-technology"></i></a>';
                return $edit.$show.$delete.$print;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function stockOrderDetilList($idStockOrder)
    {
        $data = StockOrderDetil::with('produk')->latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

}
