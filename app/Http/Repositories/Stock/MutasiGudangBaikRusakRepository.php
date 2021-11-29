<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\MutasiBaikRusak;
use App\Models\Stock\MutasiBaikRusakDetail;

class MutasiGudangBaikRusakRepository
{
    public function getDataBySearch($search = null)
    {
        return MutasiBaikRusak::where('activeCash', session('ClosedCash'))
            ->Where(function ($query) use($search){
                $query->whereRelation('gudangAsal', 'branchName', 'like', '%'.$search.'%')
                    ->whereRelation('gudangTujuan', 'branchName', 'like', '%'.$search.'%')
                    ->whereRelation('user', 'name', '%'.$search.'%');
            })
            ->paginate(10);
    }

    public function kode()
    {
        $data = MutasiBaikRusak::where('activeCash', session('ClosedCash'))->latest('kode')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/MBR/".date('Y');
        return $id;
    }

    public function storeData(array $data)
    {
        return MutasiBaikRusak::create([
            'activeCash'=>$data['activeCash'],
            'kode'=>$this->kode(),
            'gudang_asal'=>$data['gudang_asal'],
            'gudang_tujuan'=>$data['gudang_tujuan'],
            'tgl_mutasi'=>$data['tgl_mutasi'],
            'user_id'=>$data['user_id'],
            'keterangan'=>$data['keterangan']
        ]);
    }

    public function storeDetaildata(array $data)
    {
        return MutasiBaikRusakDetail::create([
            'mutasi_gudang_id'=>$data['mutasi_gudang_id'],
            'produk_id'=>$data['produk_id'],
            'jumlah'=>$data['jumlah'],
        ]);
    }
}
