<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\MutasiGudang;
use App\Models\Stock\MutasiGudangDetil;
use App\Models\Stock\StockDetilTemp;
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
        return view('pages.stock.stockMutasiGudang');
    }

    private function createSessionStock($idMutasi = null)
    {
        // insert stock_temp
        return StockTemp::create([
            'jenisTemp'=>'Mutasi',
            'idUser'=>Auth::id(),
            'stockMasuk'=>$idMutasi
        ]);
    }

    private function checkLastCart()
    {
        // check session stock
        if (session('mutasi'))
        {
            // jika ada langsung ambil data stock
            $stock = StockTemp::find(session('mtasi'));
        } else {
            // check last temp
            $lastTemp = StockTemp::where('idUser', Auth::id())->where('jenisTemp', 'mutasi')->whereNull('stockMasuk');
            if ($lastTemp->count() > 0)
            {
                // jika ada
                $stock = $lastTemp->latest()->first();
            } else {
                $stock = $this->createSessionStock();
            }
            session()->put(['mutasi'=>$stock->id]);
        }
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser
        ];
        return $data;
    }

    public function create()
    {
        return view('pages.stock.stockMutasiGudangTrans', $this->checkLastCart());
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
                        ]);
                } else {
                    InventoryReal::create([
                        'idProduk'=>$row->idProduk,
                        'branchId'=>$request->branch,
                        'stockOut'=>$row->jumlah,
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
                        ]);
                } else {
                    InventoryReal::create([
                        'idProduk'=>$row->idProduk,
                        'branchId'=>$request->branch,
                        'stockIn'=>$row->jumlah,
                    ]);
                }
            }
            PenjualanDetilTemp::where('stockTemp',$idTemp)->delete();
            PenjualanTemp::destroy($idTemp);
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }

    public function checkSessionEdit($id)
    {
        //check temp
        $checkTemp = StockTemp::where('stockMasuk', $id)->where('jenisTemp', 'Mutasi')->where('idUser', Auth::id());
        if ($checkTemp->get()->count() > 0){
            // jika temp sebelumnya ada, dipakai saja
            $temp = $checkTemp->latest()->first();
            // delete detil_temp stock lama
            StockDetilTemp::where('stockTemp', $temp->id)->delete();
        } else {
            // jika temp tidak ada, buat baru
            $temp = $this->createSessionStock($id);
        }
        // insert detil
        $mutasi_detil = MutasiGudangDetil::where('id_mutasu_gudang', $id)->get();
        if ($mutasi_detil->count() > 0)
        {
            foreach ($mutasi_detil as $row)
            {
                StockDetilTemp::create([
                    'stockTemp'=>$temp->id,
                    'idProduk'=>$row->id_produk,
                    'jumlah'=>$row->jumlah
                ]);
            }
        }
        return $temp;
    }

    public function edit($id)
    {
        // get data from stock_keluar
        $mutasi = MutasiGudang::with(['idBranchAsal', 'idBranchTujuan', 'user'])->find($id);
        $stock = $this->checkSessionEdit($id);
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser,
            'id'=>$id,
            'kode'=>$mutasi->kode,
            'branchAsal'=>$mutasi->branchAsal,
            'branchTujuan'=>$mutasi->branchTujuan,
            'tgl_keluar'=>$mutasi->tgl_mutasi->format('d-M-Y'),
            'keterangan'=>$mutasi->keterangan,
            'update'=>true
        ];
        return view('pages.stock.stockMutasiGudangTrans', $$data);
    }

    public function update(Request $request)
    {
        $idMutasi = $request->id;
        $stockMasuk = StockMasuk::where('id_penjualan', $idMutasi)->where('jenis_masuk', 'mutasi')->first();
        $stockKeluar = StockKeluar::where('penjualan', $idMutasi)->where('jenis_keluar', 'mutasi')->first();
        $tglMutasi = date('Y-m-d', strtotime($request->tgl_mutasi));
        $idTemp = $request->idTemp;

        DB::beginTransaction();
        try {
            // recovery stock first
            // recovery stock branch asal and deleteStockMasukDetil
            $deleteStockMasukDetil = StockMasukDetil::where('idStockMasuk', $stockMasuk->id);
            foreach ($deleteStockMasukDetil->get() as $row){
                // update inventory_real
                InventoryReal::where('idProduk', $row->idProduk)
                    ->where('branchId', $stockMasuk->idBranch)
                    ->update([
                        'stockIn'=>DB::raw('stockIn -'.$row->jumlah),
                        'stockNow'=>DB::raw('stockNow -'.$row->jumlah),
                    ]);
            }
            $deleteStockMasukDetil->delete();
            // recovery stock branch tujuan adn deleteStockKeluarDetil
            $deleteStockKeluarDetil = StockKeluarDetil::where('stock_keluar', $stockKeluar)->id;
            foreach ($deleteStockKeluarDetil->get() as $row){
                // update inventory_real
                InventoryReal::where('idProduk', $row->idProduk)
                    ->where('branchId', $stockKeluar->branch)
                    ->update([
                        'stockOut'=>DB::raw('stockOut -'.$row->jumlah),
                        'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                    ]);
            }
            $deleteStockKeluarDetil->delete();

            // update data Mutasi Gudang
            MutasiGudang::where('id', $idMutasi)->update([
                'branchAsal'=>$request->branchAsal,
                'branchTujuan'=>$request->branchTujuan,
                'tgl_mutasi'=>$tglMutasi,
                'id_user'=>Auth::id(),
                'keterangan'=>$request->keterangan
            ]);

            // update data Stock Masuk
            StockMasuk::where('id', $stockMasuk->id)->update([
                'tglMasuk'=>$tglMutasi,
                'idBranch'=>$request->branch,
                'idSupplier'=>$request->idSupplier,
                'keterangan'=>$request->keterangan,
                'idUser'=>Auth::id(),
                'jenis_masuk'=>'mutasi',
            ]);

            // update data Stock Keluar
            StockKeluar::where('id', $stockKeluar->id)->update([
                'tgl_keluar'=>$tglMutasi,
                'branch'=>$request->branchAsal,
                'jenis_keluar'=>'mutasi',
                'supplier'=>null,
                'customer'=>null,
                'users'=>Auth::id(),
                'keterangan'=>$request->keterangan,
            ]);
            // insert detil
            $detilTemp = StockTemp::where('stockTemp', $idTemp)->get();
            foreach ($detilTemp as $row)
            {
                // insert mutasi_gudang_detil
                MutasiGudangDetil::create([
                    'id_mutasi_gudang'=>$idMutasi,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);

                // insert stock_keluar_detil
                StockKeluarDetil::create([
                    'stock_keluar'=>$stockKeluar->id,
                    'id_produk'=>$row->idBarang,
                    'jumlah'=>$row->jumlah
                ]);

                // insert stockmasuk_detil
                StockMasukDetil::create([
                    'idStockMasuk'=>$stockMasuk->id,
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
            PenjualanDetilTemp::where('stockTemp',$idTemp)->delete();
            PenjualanTemp::destroy($idTemp);
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }
}
