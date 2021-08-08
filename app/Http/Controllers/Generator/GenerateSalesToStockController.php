<?php

namespace App\Http\Controllers\Generator;

use App\Http\Controllers\Controller;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenerateSalesToStockController extends Controller
{
    public function store()
    {
        $penjualan = Penjualan::where('activeCash', session('closedCash'))->get();
        DB::beginTransaction();
        try {
            if ($penjualan->count() > 0) {
                foreach ($penjualan as $row){
                    $stock_keluar = StockKeluar::create([
                        'active_cash'=>session('closedCash'),
                        'tgl_keluar', //tgl_nota
                        'kode', // generate code
                        'branch', // nota keluar darimana
                        'jenis_keluar'=>'penjualan',
                        'customer', // customer penjualan
                        'penjualan', // id penjualan
                        'users', // pembuat nota
                        'keterangan'=>null
                    ]);
                    // insert stock_keluar_detil from detil_penjualan
                    $detil_penjualan = PenjualanDetil::where('id_jual', $row->id_jual)->get();
                    foreach ($detil_penjualan as $row_){
                        $stock_detil_keluar = StockKeluarDetil::create([
                            'stock_keluar'=>$stock_keluar->id,
                            'id_produk'=>$row_->id_produk,
                            'jumlah'=>$row_->jumlah
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['status'=>true, 'action'=>'berhasil']);
        } catch(ModelNotFoundException $e){
            DB::rollBack();
            return response()->json(['status'=>false, 'action'=>$e]);
        }
    }

    public function update()
    {
        $penjualan = Penjualan::where('activeCash', session('closedCash'))->get();
        DB::beginTransaction();
        try {
            // ambil stock_keluar yg match dengan id penjualan
            foreach ($penjualan as $row)
            {
                $stock_keluar = StockKeluar::where('penjualan', $row->id_jual)->first();
                if ($stock_keluar)
                {
                    //
                    // update data
                    // delete stock_keluar_detil
                    // insert stock_keluar_detil berdasarkan data yang baru
                }
            }
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }
}
