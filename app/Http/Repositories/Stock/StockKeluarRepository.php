<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  StockKeluarRepository
{
    public function kode()
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

    public function storeStockKeluar($dataStockKeluar)
    {
        return StockKeluar::create([
            'active_cash'=>session('ClosedCash'),
            'tgl_keluar'=>$dataStockKeluar->tglStockKeluar,
            'kode'=>$dataStockKeluar->kodeStockKeluar ?? $this->kode(),
            'branch'=>$dataStockKeluar->idBranch ?? null,
            'jenis_keluar'=>$dataStockKeluar->jenisStockKeluar,
            'customer'=>$dataStockKeluar->idCustomer,
            'penjualan'=>$dataStockKeluar->idPenjualan ?? '',
            'users'=>$dataStockKeluar->idUser,
        ]);
    }

    public function storeStockKeluarDetail($dataDetail)
    {
        return StockKeluarDetil::create($dataDetail);
    }

    public function commitStockKeluar($dataStockKeluar)
    {
        DB::beginTransaction();
        try {
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }
}
