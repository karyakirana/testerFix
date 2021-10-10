<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockMasuk;
use Illuminate\Support\Facades\Auth;

class StockMasukRepository
{
    public static function kode() : string
    {
        $data = StockMasuk::where('activeCash', session('ClosedCash'))->latest()->first();
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SM/".date('Y');
        return $id;
    }

    public function create($dataStockIn)
    {
        if (isset($dataStockIn->tgl_masuk)){
            $jenisMasuk = 'Stock Masuk';
        } elseif (isset($dataStockIn->tgl_retur)){
            $jenisMasuk = 'Retur Penjualan';
        } else {
            $jenisMasuk = 'Mutasi';
        }
        return StockMasuk::create([
            'activeCash'=>session('ClosedCash'),
            'kode'=>self::kode(),
            'idBranch'=>$dataStockIn->branch_id,
            'idSupplier'=>$dataStockIn->supplier_id ?? null,
            'idUser'=>Auth::id(),
            'tglMasuk'=>$dataStockIn->tgl_masuk ?? $dataStockIn->tgl_retur ?? $dataStockIn->tgl_mutasi,
            'nomorPo'=>$dataStockIn->nomor_po ?? null,
            'keterangan'=>$dataStockIn->keterangan,
            'id_penjualan'=>$dataStockIn->idMutasi ?? $dataStockIn->idRetur,
            'jenis_masuk'=>$jenisMasuk
        ]);
    }

    public function update($id, $dataStockIn)
    {
        return StockMasuk::where('id', $id)
            ->update([
                'idBranch'=>$dataStockIn->branch_id,
                'idSupplier'=>$dataStockIn->supplier_id ?? null,
                'idUser'=>Auth::id(),
                'tglMasuk'=>$dataStockIn->tgl_masuk ?? $dataStockIn->tgl_retur ?? $dataStockIn->tgl_mutasi,
                'nomorPo'=>$dataStockIn->nomor_po ?? null,
                'keterangan'=>$dataStockIn->keterangan,
                'id_penjualan'=>$dataStockIn->idMutasi ?? $dataStockIn->idRetur,
            ]);
    }

    public function destroy($id)
    {
        return StockMasuk::destroy($id);
    }
}
