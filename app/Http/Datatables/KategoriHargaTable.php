<?php

namespace App\Http\Datatables;

use Yajra\DataTables\DataTables;
use App\Models\Master\KategoriHarga;

class KategoriHargaTable {

    public function list()
    {
        $data = KategoriHarga::latest('id_kat_harga')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_kat_harga.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_kat_harga.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
}
