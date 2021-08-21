<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\MutasiGudang;
use App\Models\Stock\MutasiGudangDetil;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use App\Models\Stock\StockTemp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiGudangController extends Controller
{
    public function index()
    {
        return view();
    }

    public function create()
    {
        return view();
    }

    public function kodeMutasi()
    {
        $data = MutasiGudang::where('active_cash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/MG/".date('Y');
        return $id;
    }

    public function kodeStockKeluar()
    {
        $data = StockKeluar::where('active_cash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SK/".date('Y');
        return $id;
    }

    public function kodeStockMasuk()
    {
        $data = StockMasuk::where('activeCash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SM/".date('Y');
        return $id;
    }

    // insert mutasi gudang
    // insert stock_keluar
    // insert stock_masuk
    // koreksi inventory
    public function store(Request $request)
    {
        $kodeMutasi = $this->kodeMutasi();
        $kodeStockMasuk = $this->kodeStockMasuk();
        $kodeStockKeluar = $this->kodeStockKeluar();
        $tglMutasi = date('Y-m-d', strtotime($request->tgl_mutasi));
        $idTemp = $request->idTemp;

        DB::beginTransaction();
        try {
            // insert Mutasi
            $insertMutasi = MutasiGudang::create([
                'activeCash'=>session('ClosedCash'),
                'kode'=>$kodeMutasi,
                'branchAsal'=>$request->branchAsal,
                'branchTujuan'=>$request->branchTujuan,
                'tgl_mutasi'=>$tglMutasi,
                'id_user'=>Auth::id(),
                'keterangan'=>$request->keterangan
            ]);

            // insert Stock Keluar
            $insertStockKeluar = StockKeluar::create([
                'active_cash'=>session('ClosedCash'),
                'tgl_keluar'=>$tglMutasi,
                'kode'=>$kodeStockKeluar,
                'branch'=>$request->branchAsal,
                'jenis_keluar'=>'mutasi',
                'supplier'=>null,
                'customer'=>null,
                'penjualan' =>$insertMutasi->id,
                'users'=>Auth::id(),
                'keterangan'=>$request->keterangan,
            ]);

            // insert Stock Masuk
            $insertStockMasuk = StockMasuk::create([
                'activeCash'=>session('ClosedCash'),
                'tglMasuk'=>$tglMutasi,
                'kode'=>$kodeStockMasuk,
                'idBranch'=>$request->branch,
                'idSupplier'=>$request->idSupplier,
                'keterangan'=>$request->keterangan,
                'idUser'=>Auth::id(),
                'jenis_masuk'=>'mutasi',
                'id_penjualan'=>$insertMutasi->id
            ]);

            // insert detil
            $detilTemp = StockTemp::where('stockTemp', $idTemp)->get();
            foreach ($detilTemp as $row)
            {
                // insert mutasi_gudang_detil
                MutasiGudangDetil::create([
                    'id_mutasi_gudang'=>$insertMutasi->id,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);

                // insert stock_keluar_detil
                StockKeluarDetil::create([
                    'stock_keluar'=>$insertStockKeluar->id,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);

                // insert stockmasuk_detil
                StockMasukDetil::create([
                    'idStockMasuk'=>$insertStockMasuk->id,
                    'idProduk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);

                // inventory real
                // update or create inventory real keluar
                $inventory_real_asal = InventoryReal::where('idProduk', $row->idProduk)
                    ->where('branchId', $request->branchAsal)->get();
                if ($inventory_real_asal->count() > 0){
                    // update
                    InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch)
                        ->update([
                            'stockOut'=>DB::raw('stockOut +'.$row->jumlah),
                            'stockNow'=>DB::raw('stockNow -'.$row->jumlah),
                        ]);
                } else {
                    InventoryReal::create([
                        'idProduk'=>$row->idProduk,
                        'branchId'=>$request->branch,
                        'stockOut'=>$row->jumlah,
                        'stockNow'=>DB::raw('stockNow -'.$row->jumlah),
                    ]);
                }

                $inventory_real_tujuan = InventoryReal::where('idProduk', $row->idProduk)
                    ->where('branchId', $request->branchTujuan)->get();
                if ($inventory_real_tujuan->count() > 0){
                    // update
                    InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch)
                        ->update([
                            'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                } else {
                    InventoryReal::create([
                        'idProduk'=>$row->idProduk,
                        'branchId'=>$request->branch,
                        'stockIn'=>$row->jumlah,
                        'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                    ]);
                }
            }
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        $idMutasi = $request->id;
        $stockMasuk = StockMasuk::where('id_penjualan', $idMutasi)->first();
        $stockKeluar = StockKeluar::where('penjualan', $idMutasi)->first();
        $tglMutasi = date('Y-m-d', strtotime($request->tgl_mutasi));
        $idTemp = $request->idTemp;

        DB::beginTransaction();
        try {
            // recovery stock first
            // recovery stock branch asal
            // recovery stock branch tujuan
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }
}
