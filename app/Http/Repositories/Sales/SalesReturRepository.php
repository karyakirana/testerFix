<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\ReturBaik;
use Illuminate\Support\Facades\Auth;

class SalesReturRepository
{

    public function kode() : string
    {
        // generate kode Return By Active Closed Cash
        $data = ReturBaik::where('activeCash', session('ClosedCash'))->latest('id_return')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_return, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/RBB/".date('Y');
        return $id;
    }

    public function create($dataSales)
    {
        return ReturBaik::create([
            'id_return'=>$this->kode(),
            'id_branch'=>$dataSales->branch_id,
            'id_user'=>Auth::id(),
            'id_cust'=>$dataSales->customer_id,
            'tgl_nota'=>$dataSales->tgl_retur,
            'total_jumlah'=>0,
            'ppn'=>$dataSales->ppn ?? 0,
            'biaya_lain'=>$dataSales->biaya_lain ?? 0,
            'total_bayar'=>$dataSales->total_bayar,
            'keterangan'=>$dataSales->keterangan,
            'activeCash'=>session('ClosedCash')
        ]);
    }

    public function update($id, $dataSales)
    {
        return ReturBaik::where('id_return', $id)
            ->update([
                'id_branch'=>$dataSales->branch_id,
                'id_user'=>Auth::id(),
                'id_cust'=>$dataSales->customer_id,
                'tgl_nota'=>$dataSales->tgl_retur,
                'total_jumlah'=>0,
                'ppn'=>$dataSales->ppn ?? 0,
                'biaya_lain'=>$dataSales->biaya_lain ?? 0,
                'total_bayar'=>$dataSales->total_bayar,
                'keterangan'=>$dataSales->keterangan,
                'activeCash'=>session('ClosedCash')
            ]);
    }

    public function destroy($id)
    {
        return ReturBaik::destroy($id);
    }
}
