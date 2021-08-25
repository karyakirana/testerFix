<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockAkhir;
use App\Models\Stock\StockAkhirDetil;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
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
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.$row->id.'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $show.$edit.$soft;
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

    public function stockMasukDetil($idMasuk)
    {
        $data = StockMasukDetil::with('produk')->latest()->get();
        return DataTables::of($data)
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

    /**
     * @param null $idBranch
     * @return mixed
     * @throws \Exception
     */
    public function stockAkhirList($idBranch = null)
    {
        $data = StockAkhir::with(['branch', 'user'])->latest()->get();
        if ($idBranch){
            $data = StockAkhir::with(['branch', 'user'])
                ->where('branchId', $idBranch)
                ->latest()->get();
        }
        return DataTables::of($data)
            ->addColumn('branch', function ($row){
                return $row->branch->branchName ?? '';
            })
            ->addColumn('user', function ($row){
                return $row->user->name ?? '';
            })
            ->addColumn('Action', function ($row){
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.$row->id.'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $show.$edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function stockAkhirListDetil($idStockAkhir)
    {
        $data = StockAkhirDetil::with('produk')->where('id_stock_akhir', $idStockAkhir)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

    public function stockAkhirDetil()
    {
        $data = StockAkhirDetil::leftJoin('stockakhir_master', 'stockakhir.id_stock_akhir', '=', 'stockakhir_master.id')
            ->leftJoin('branch_stock', 'branch_stock.id', '=', 'stockakhir_master.branchId')
            ->where('stockakhir.activeCash', session('ClosedCash'))
            ->with(['stockAkhir', 'stockAkhir.branch'])
            ->orderBy('id_produk', 'ASC')
            ->get();
        return DataTables::of($data)
            ->addColumn('produk', function ($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->addColumn('branch', function ($row){
                return $row->branchName;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

    public function stockAkhirByBranch($branch)
    {
        $data = StockAkhirDetil::leftJoin('stockakhir_master', 'stockakhir.id_stock_akhir', '=', 'stockakhir_master.id')
            ->leftJoin('branch_stock', 'branch_stock.id', '=', 'stockakhir_master.branchId')
            ->where('stockakhir_master.activeCash', session('ClosedCash'))
            ->where('stockakhir_master.branchId', $branch)
            ->with(['stockAkhir', 'stockAkhir.branch'])
            ->orderBy('id_produk', 'ASC')
            ->get();
        return DataTables::of($data)
            ->addColumn('produk', function ($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->addColumn('branch', function ($row){
                return $row->branchName;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

    /**
     * @param null $idBranch
     * @return mixed
     * @throws \Exception
     */
    public function stockKeluarList($idBranch = null)
    {
        $data = StockKeluar::with(['suppliers', 'customers', 'branchs', 'user'])->latest()->get();
        if ($idBranch){
            $data = StockKeluar::with(['suppliers', 'customers', 'branchs', 'user'])
                ->where('branch', $idBranch)
                ->latest()->get();
        }
        return DataTables::of($data)
            ->addColumn('branch', function($row){
                return $row->branchs->branchName ?? '';
            })
            ->addColumn('suppliers', function($row){
                return $row->supplier->namaSupplier ?? '';
            })
            ->addColumn('user', function($row){
                return $row->user->name ?? '';
            })
            ->addColumn('customers', function ($row){
                return $row->customer->id_cust ?? '';
            })
            ->addColumn('Action', function($row){
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.$row->id.'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $show.$edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function stockKeluarDetil($idStockKeluar)
    {
        $data = StockKeluarDetil::where('stock_keluar', $idStockKeluar);
        return DataTables::of($data)
            ->addColumn('produk', function($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->make(true);
    }
}
