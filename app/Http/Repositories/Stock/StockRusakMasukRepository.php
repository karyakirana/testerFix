<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockMasukRusak;
use App\Models\Stock\StockMasukRusakDetil;

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

    public function storeStockMasuk(array $dataStockMasuk, $idReturRusak = null, $idMutasi = null)
    {
        return StockMasukRusak::create([
            'jenis'=>$dataStockMasuk['jenis'] ?? $dataStockMasuk['jenis_keluar'],
            'activeCash'=>$dataStockMasuk['activeCash'],
            'kode'=>self::getKode(),
            'branch_id'=>$dataStockMasuk['branchId'] ?? $dataStockMasuk['gudang_tujuan'],
            'retur_id'=>$idReturRusak ?? null,
            'customer_id'=>$dataStockMasuk['customerId'] ?? null,
            'supplier_id'=>$dataStockMasuk['supplierId'] ?? null,
            'user_id'=>$dataStockMasuk['userId'] ?? $dataStockMasuk['user_id'],
            'tgl_masuk_rusak'=>$dataStockMasuk['tglMasukRusak'] ?? $dataStockMasuk['tglReturRusak'] ?? $dataStockMasuk['tgl_mutasi'],
            'mutasi_id'=>$dataStockMasuk['mutasiId'] ?? $idMutasi,
            'keterangan'=>$dataStockMasuk['keterangan'],
        ]);
    }

    public function storeStockMasukDetail(array $dataStockMasukDetail)
    {
        return StockMasukRusakDetil::create([
            'stock_masuk_rusak_id'=>$dataStockMasukDetail['stockMasukRusakId'],
            'produk_id'=>$dataStockMasukDetail['produkId'] ?? $dataStockMasukDetail['produk_id'],
            'jumlah'=>$dataStockMasukDetail['jumlah']
        ]);
    }
}
