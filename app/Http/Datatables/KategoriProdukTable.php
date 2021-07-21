<?php

namespace App\Http\Datatables;

use Yajra\DataTables\DataTables;
use App\Models\Master\KategoriProduk;

class KategoriProdukTable {

    public function list()
    {
        $data = KategoriProduk::latest('id_kategori')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_kategori.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_kategori.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
}
