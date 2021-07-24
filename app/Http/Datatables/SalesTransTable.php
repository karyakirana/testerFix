<?php

namespace App\Http\Datatables;

use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\PenjualanTemp;
use App\Models\Sales\PenjualanDetilTemp;
use Yajra\DataTables\DataTables;

class SalesTransTable {

    public function penjualanList()
    {
        $data = Penjualan::where('activeCash', session('ClosedCash'))->latest('id_jual')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('customer', function ($row){
                return $row->customer->nama_cust ?? '';
            })
            ->addColumn('user', function ($row){
                return $row->pengguna->name ?? '';
            })
            ->addColumn('branch', function ($row){
                return $row->branch->branchName ?? '';
            })
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_jual.'" title="edit"><i class="la la-edit"></i></a>';
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.$row->id_jual.'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $delete = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btndelete" data-value="'.$row->id_jual.'" title="delete"><i class="flaticon2-trash"></i></a>';
                $print = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnPrint" data-value="'.$row->id_jual.'" title="print"><i class="flaticon-technology"></i></a>';
                return $edit.$show.$delete.$print;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function detilList($id)
    {
        $data = PenjualanDetil::where('id_jual', $id)->latest('id_detil')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($row){
                return $row->produk->nama_produk ?? '';
            })
            ->make(true);
    }

    public function detilTemp($id)
    {
        $data = PenjualanDetilTemp::where('idPenjualanTemp')->latest('id_detil')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id_detil.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id_detil.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }
}
