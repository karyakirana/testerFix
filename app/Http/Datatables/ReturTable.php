<?php

namespace App\Http\Datatables;

use App\Models\Sales\ReturBaik;
use App\Models\Sales\ReturBaikDetil;
use App\Models\Sales\ReturRusak;
use App\Models\Sales\ReturRusakDetil;
use Yajra\DataTables\DataTables;

class ReturTable {

    private function actionCrud($data)
    {
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
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.str_replace('/', '-', $row->id_return).'" title="edit"><i class="la la-edit"></i></a>';
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.str_replace('/', '-', $row->id_return ?? $row->id_rr).'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $delete = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btndelete" data-value="'.($row->id_return ?? $row->id_rr).'" title="delete"><i class="flaticon2-trash"></i></a>';
                $print = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnPrint" data-value="'.str_replace('/', '-', $row->id_return ?? $row->id_rr).'" title="print"><i class="flaticon-technology"></i></a>';
                return $edit.$show.$delete.$print;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    private function actionList($data)
    {
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

    // Table List Retur Baik
    public function returBaik($branch = null)
    {
        $data = ReturBaik::with(['user', 'customer', 'branch']);
        if ($branch)
        {
            $data = $data->where('branch', $branch);
        }
        $data = $data->where('activeCash', session('ClosedCash'))
            ->latest('id_return')
            ->get();
        return $this->actionCrud($data);
    }

    // Table List Retur Baik Detil
    public function returBaikDetil($id_return)
    {
        $data = ReturBaikDetil::with('produk')->where('id_return', $id_return)->latest('id_return_detail')->get();
        return $this->actionList($data);
    }

    // Table List Retur Rusak
    public function returRusak($branch = null)
    {
        $data = ReturRusak::with(['user', 'customer', 'branch']);
        if ($branch)
        {
            $data = $data->where('branch', $branch);
        }
        $data = $data->where('activeCash', session('ClosedCash'))
            ->latest('id_rr')
            ->get();
        return $this->actionCrud($data);
    }

    // Table List Retur Rusak Detil
    public function returRusakDetil($id_rr)
    {
        $data = ReturRusakDetil::with(['produk'])->where('id_rr', $id_rr)->latest('id_detail_rr')->get();
        return $this->actionList($data);
    }

}
