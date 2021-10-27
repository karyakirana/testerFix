<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\Penjualan;
use Illuminate\Support\Facades\Auth;

class SalesRepository
{
    public function kode()
    {
        $data = Penjualan::where('activeCash', session('ClosedCash'))->latest('id_jual')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_jual, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/PJ/".date('Y');
        return $id;
    }

    public function storePenjualan($dataPenjualan)
    {
        return Penjualan::create(
            [
                'activeCash' => session('ClosedCash'),
                'id_jual' => $dataPenjualan->idPenjualan ?? $this->kode(),
                'tgl_nota' => $dataPenjualan->tglPenjualan,
                'tgl_tempo' => ($dataPenjualan->jenisBayar == 'Tempo') ? $dataPenjualan->tglTempo : null,
                'status_bayar' => $dataPenjualan->jenisBayar,
                'sudahBayar'=> $dataPenjualan->sudahBayar,
                'total_jumlah' => $dataPenjualan->total_jumlah,
                'ppn' => $dataPenjualan->ppn,
                'biaya_lain' => $dataPenjualan->biayaLain,
                'total_bayar' => $dataPenjualan->total_bayar,
                'id_cust' => $dataPenjualan->idCustomer,
                'id_user' => $dataPenjualan->idUser,
                'idBranch'=> $dataPenjualan->idBranch,
                'keterangan' => $dataPenjualan->keterangan,
            ]
        );
    }
}
