<?php

namespace App\Http\Datatables;

use Yajra\DataTables\DataTables;
use App\Models\Master\Supplier;

class SupplierTable {

    protected function data()
    {
        return Supplier::latest('id')->get();
    }

    public function list()
    {
        $data = $this->data();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('jenisSupplier', function($row){
                return $row->jenisSupplier->jenis ?? '';
            })
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                return $edit;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function listCrud()
    {
        $data = $this->data();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('jenisSupplier', function($row){
                return $row->jenisSupplier->jenis ?? '';
            })
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
}
