<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockMasukRusak;

class StockRusakMasukRepository
{
    public function listData()
    {
        //
        return;
    }

    public static function getKode() : string
    {
        $data = StockMasukRusak::where('activeCash', session('ClosedCash'))->latest()->first();
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SMR/".date('Y');
        return $id;
    }

    public function create($dataStockRusakIn, $idReturRusak=null)
    {
        return StockMasukRusak::create([
            'jenis'=>$dataStockRusakIn->jenis_masuk,
            'activeCash'=>session('ClosedCash'),
            'kode'=>$this->getKode(),
            'branch_id'=>$dataStockRusakIn->branch_id,
            'retur_id'=>$idReturRusak,
            'customer_id'=>$dataStockRusakIn->customer_id ?? null,
            'user_id'=>$dataStockRusakIn->user_id,
            'tgl_masuk_rusak'=>$dataStockRusakIn->tgl_nota,
            'mutasi_id'=>null,
            'keterangan'=>$dataStockRusakIn->keterangan
        ]);
    }

    public static function update($id, $dataStockRusakIn)
    {
        return StockMasukRusak::where('id', $id)
            ->update([
            'branch_id'=>$dataStockRusakIn->branch_id,
            'customer_id'=>$dataStockRusakIn->customer_id ?? null,
            'user_id'=>$dataStockRusakIn->user_id,
            'tgl_masuk_rusak'=>$dataStockRusakIn->tgl_nota,
            'mutasi_id'=>null,
            'keterangan'=>$dataStockRusakIn->keterangan
        ]);
    }

    public function destroy($idStockRusakIn)
    {
        //
    }
}
