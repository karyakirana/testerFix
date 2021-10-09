<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\ReturBaik;
use Illuminate\Support\Facades\Auth;

class SalesReturRepository
{

    public function kode()
    {
        // generate kode Return By Active Closed Cash
    }

    public function create($dataSales)
    {
        return ReturBaik::create([
            'id_return'=>$this->kode(),
            'id_branch'=>$dataSales->branch_id,
            'id_user'=>Auth::id(),
            'id_cust'=>$dataSales->customer_id,
            'tgl_nota'=>$dataSales->tgl_nota,
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
                'tgl_nota'=>$dataSales->tgl_nota,
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
