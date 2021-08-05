<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockAkhir;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockMasuk;
use Yajra\DataTables\DataTables;

class StockTable {

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    protected function action($data)
    {
        return DataTables::of($data)
            ->addColumn('branch', function($row){
                return $row->branch->branchName ?? '';
            })
            ->addColumn('supplier', function($row){
                return $row->supplier->namaSupplier ?? '';
            })
            ->addColumn('user', function($row){
                return $row->user->name ?? '';
            })
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    /**
     * @param null $idBranch
     * @return mixed
     * @throws \Exception
     */
    public function stockMasukList($idBranch = null)
    {
        $data = StockMasuk::with(['branch', 'supplier', 'user'])->latest('kode')->get();
        if ($idBranch)
        {
            $data = StockMasuk::with(['branch', 'supplier', 'user'])
                ->where('idBranch', $idBranch)
                ->latest('kode')->get();
        }
        return $this->action($data);
    }

    /**
     * @param null $idBranch
     * @return mixed
     * @throws \Exception
     */
    public function stockAkhirList($idBranch = null)
    {
        $data = StockAkhir::with(['branch', 'produk'])->latest('id_produk')->get();
        if ($idBranch){
            $data = StockAkhir::with(['branch', 'produk'])
                ->where('branchId', $idBranch)
                ->latest('id_produk')->get();
        }
        return $this->action($data);
    }

    /**
     * @param null $idBranch
     * @return mixed
     * @throws \Exception
     */
    public function stockKeluarList($idBranch = null)
    {
        $data = StockKeluar::with(['supplier', 'customer', 'branch','penjualan', 'users'])->latest()->get();
        if ($idBranch){
            $data = StockKeluar::with(['supplier', 'customer', 'branch','penjualan', 'users'])
                ->where('branch', $idBranch)
                ->latest()->get();
        }
        return DataTables::of($data)
            ->addColumn('branch', function($row){
                return $row->branch->branchName ?? '';
            })
            ->addColumn('supplier', function($row){
                return $row->supplier->namaSupplier ?? '';
            })
            ->addColumn('user', function($row){
                return $row->user->name ?? '';
            })
            ->addColumn('customer', function ($row){
                return $row->customer->id_cust ?? '';
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
