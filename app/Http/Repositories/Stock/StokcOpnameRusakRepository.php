<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockOpnameRusak;
use App\Models\Stock\StockOpnameRusakDetil;

class StokcOpnameRusakRepository
{

    public function getData($search = null)
    {
        return StockOpnameRusak::all();
    }

    public function kode()
    {
        $data = StockOpnameRusak::where('activeCash', session('ClosedCash'))->latest('kode')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SOR/".date('Y');
        return $id;
    }

    public function storeData(array $data)
    {
        return StockOpnameRusak::create([
            'activeCash'=>$data['activeCash'],
            'kode'=>$this->kode(),
            'user'=>$data['user'],
            'pelapor'=>$data['pelapor'],
            'branch_id'=>$data['branch_id'],
            'tgl_input'=>$data['tgl_input'],
            'keterangan'=>$data['keterangan'],
        ]);
    }

    public function storeDataDetail(array $data)
    {
        return StockOpnameRusakDetil::create([
            'stock_opname_rusak_id'=>$data['stock_opname_rusak_id'],
            'produk_id'=>$data['produk_id'],
            'jumlah'=>$data['jumlah'],
        ]);
    }
}
