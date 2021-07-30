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
        $data = Penjualan::with(['customer', 'pengguna', 'branch'])->where('activeCash', session('ClosedCash'))->latest('id_jual')->get();
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
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.str_replace('/', '-', $row->id_jual).'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $delete = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btndelete" data-value="'.$row->id_jual.'" title="delete"><i class="flaticon2-trash"></i></a>';
                $print = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnPrint" data-value="'.$row->id_jual.'" title="print"><i class="flaticon-technology"></i></a>';
                return $edit.$show.$delete.$print;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function detilList($id)
    {
        $data = PenjualanDetil::with('produk')->where('id_jual', str_replace('-', '/', $id))->latest('id_detil')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($row){
                return $row->produk->nama_produk ?? '';
            })
            ->make(true);
    }

    public function detilTemp($id)
    {
        $data = PenjualanDetilTemp::with('produk')->where('idPenjualanTemp', $id)->latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($row){
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
