<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\MutasiGudangRusak;
use App\Models\Stock\MutasiGudangRusakDetail;
class MutasiGudangRusakRepository
{
    public function getDataBySearch($search)
    {
        return MutasiGudangRusak::where('activeCash', session('ClosedCash'))
            ->paginate(10);
    }
    public function kode()
    {
        $data = MutasiGudangRusak::where('activeCash', session('ClosedCash'))->latest('kode')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/MRR/".date('Y');
        return $id;
    }

    public function storeData(array $data)
    {
        return MutasiGudangRusak::create([
           'activeCash'=>$data['activeCash'],
           'kode'=>$this->kode(),
           'gudang_asal'=>$data['gudang_asal'],
           'gudang_tujuan'=>$data['gudang_tujuan'],
           'tgl_mutasi'=>$data['tgl_mutasi'],
           'user_id'=>$data['user_id'],
           'keterangan'=>$data['keterangan'],
        ]);
    }

    public function storeDetailData(array $data)
    {
        return MutasiGudangRusakDetail::create([
            'mutasi_gudang_id'=>$data['mutasi_gudang_id'],
            'produk_id'=>$data['produk_id'],
            'jumlah'=>$data['jumlah'],
        ]);
    }
}
