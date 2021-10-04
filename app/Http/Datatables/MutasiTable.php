<?php

namespace App\Http\Datatables;

use App\Models\Stock\MutasiGudang;
use App\Models\Stock\MutasiGudangDetil;
use Yajra\DataTables\DataTables;

class MutasiTable {

    public function mutasiList()
    {
        $data = MutasiGudang::where('activeCash', session('ClosedCash'))
            ->with(['idBranchAsal', 'idBranchTujuan', 'user'])
            ->latest('id')->get();
        return DataTables::of($data)
            ->addColumn('branchAsal', function($row){
                return $row->idBranchAsal->branchName ?? '';
            })
            ->addColumn('branchTujuan', function($row){
                return $row->idBranchTujuan->branchName ?? '';
            })
            ->addColumn('user', function($row){
                return $row->user->username ?? '';
            })
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.$row->id.'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$show.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function mutasiDetilList($idMutasi)
    {
        $data = MutasiGudangDetil::where('id_mutasi_gudang', $idMutasi)
            ->with('produk')->latest('id')->get();
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
