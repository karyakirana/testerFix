<?php

namespace App\Http\Datatables;

use App\Models\Master\Customer;
use Yajra\DataTables\DataTables;

class CustomerTable {

    public function list()
    {
        $data = Customer::latest('id_cust')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_cust.'" title="Edit"><i class="la la-edit"></i></a>';
                return $edit;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function listcrud()
    {
        $data = Customer::latest('id_cust')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_cust.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_cust.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

}
