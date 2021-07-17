<?php

namespace App\Http\Datatables;

use App\Models\Master\Produk;
use Yajra\DataTables\DataTables;

class ProdukTable {

    public function list()
    {
        $data = Produk::latest('id_produk')->get();
        return DataTables::of($data)
            ->addColumn('kategori', function($row){
                return $row->kategori->nama ?? "";
            })
            ->addColumn('kategoriHarga', function($row){
                return $row->kategoriharga->nama_kat ?? "";
            })
            ->addColumn('idLokal', function ($row){
                return $row->kategoriharga->id_lokal ?? "";
            })
            ->make(true);
    }

    public function listCrud()
    {
        $data = Produk::latest('id_produk')->get();
        return DataTables::of($data)
            ->addColumn('kategori', function($row){
                return $row->kategori->nama ?? "";
            })
            ->addColumn('kategoriHarga', function($row){
                return $row->kategoriharga->nama_kat ?? "";
            })
            ->addColumn('idLokal', function ($row){
                return $row->kategoriharga->id_lokal ?? "";
            })
            ->addColumn('Action', function($row){
                $edit= '';
                $delete = '';
                return $edit.$delete;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

}
