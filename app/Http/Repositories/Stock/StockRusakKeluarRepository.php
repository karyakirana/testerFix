<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockKeluarRusak;
use Illuminate\Support\Facades\Auth;

class StockRusakKeluarRepository
{
    public function kode()
    {
        $data = StockKeluarRusak::where('activeCash', session('ClosedCash'))->latest()->first();
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SKR/".date('Y');
        return $id;
    }

    public function getData()
    {
        return StockKeluarRusak::all();
    }

    public function create($data)
    {
        return StockKeluarRusak::create(
            [
                'activeCash'=>session('ClosedCash'),
                'kode',
                'supplier_id',
                'user_id'=>Auth::id(),
                'tgl_keluar_rusak',
                'keterangan',
            ]
        );
    }

    public function update($id, $data)
    {
        return StockKeluarRusak::where('id', $id)
            ->update($data);
    }

    public function destroy($id)
    {
        return StockKeluarRusak::destroy($id);
    }

    public function storeStockKeluar(array $dataStockkeluar)
    {
        return StockKeluarRusak::create([
            'activeCash'=>$dataStockkeluar['activeCash'],
            'kode'=>$this->kode(),
            'supplier_id'=>$dataStockkeluar['supplierId'],
            'user_id'=>$dataStockkeluar['userId'],
            'tgl_keluar_rusak'=>$dataStockkeluar['tglKeluar'],
            'keterangan'=>$dataStockkeluar['keterangan'],
        ]);
    }
}
