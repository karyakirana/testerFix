<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockDetilTemp;
use Yajra\DataTables\DataTables;

class StockTempTable {

    /**
     * @param String $idStockTemp
     * @return mixed
     * @throws \Exception
     */
    public function stockTrans(String $idStockTemp)
    {
        $data = StockDetilTemp::with('produk')->where('stockTemp', $idStockTemp)->get();
        return DataTables::of($data)
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action', 'produk'])
            ->make(true);
    }
}
