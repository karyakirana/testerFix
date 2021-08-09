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

    private function kode()
    {
        $data = StockKeluar::where('active_cash', session('ClosedCash'))->latest('kode')->first();
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
    public function store()
    {
        $penjualan = Penjualan::where('activeCash', session('ClosedCash'))->get();
        DB::beginTransaction();
        try {
            if ($penjualan->count() > 0) {
                foreach ($penjualan as $row){
                    // check stock_keluar yang tidak ada penjualannya
                    $check_stock_keluar = StockKeluar::where('penjualan', $row->id_jual)->get()->count();
                    if ($check_stock_keluar == 0){
                        // buat stock_keluar baru
                        $stock_keluar_baru = StockKeluar::create([
                            'active_cash'=>session('ClosedCash'),
                            'tgl_keluar'=>$row->tgl_nota,
                            'kode'=>$this->kode(),// generate kode baru
                            'branch'=>$row->idBranch,
                            'jenis_keluar'=>'penjualan',
                            'customer'=>$row->id_cust,
                            'penjualan'=>$row->id_jual,
                            'users'=>$row->id_user
                        ]);
                        // insert stock_keluar_detil from detil_penjualan
                        $detil_penjualan = PenjualanDetil::where('id_jual', $row->id_jual)->get();
                        if ($detil_penjualan->count() > 0)
                        {
                            foreach ($detil_penjualan as $row_)
                            {
                                StockKeluarDetil::create([
                                    'stock_keluar'=>$stock_keluar_baru->id,
                                    'id_produk'=>$row_->id_produk,
                                    'jumlah'=>$row_->jumlah
                                ]);
                            }
                        }
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
