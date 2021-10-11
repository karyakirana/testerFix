<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\ReturRusak;

class SalesReturRusakRepository
{
    public static function kode() : string
    {
        $data = ReturRusak::where('activeCash', session('ClosedCash'))->latest('id_rr')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_rr, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/RR/".date('Y');
        return $id;
    }

    public function create($dataReturRusak)
    {
        return ReturRusak::create([
            'id_rr'=>$dataReturRusak->retur_id,
            'id_branch'=>$dataReturRusak->branch_id,
            'id_user'=>$dataReturRusak->user_id,
            'id_cust'=>$dataReturRusak->customer_id,
            'tgl_nota'=>$dataReturRusak->tgl_nota,
            'total_jumlah'=>0,
            'ppn'=>$dataReturRusak->ppn,
            'biaya_lain'=>$dataReturRusak->biaya_lain,
            'total_bayar'=>$dataReturRusak->total_bayar,
            'keterangan'=>$dataReturRusak->keterangan,
            'activeCash'=>session('ClosedCash')
        ]);
    }
}
