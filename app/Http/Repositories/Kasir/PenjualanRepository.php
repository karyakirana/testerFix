<?php

namespace App\Http\Repositories\Kasir;

use App\Http\Livewire\Sales\PenjualanBiaya;
use App\Models\Sales\Penjualan;
use Illuminate\Database\Eloquent\Builder;

class PenjualanRepository
{
    public function getPenjualanAll($search)
    {
        return Penjualan::with([
            'customer'
        ])
            ->where('activeCash', session('ClosedCash'))
            ->whereRelation('customer', 'nama_cust', 'like', '%'.$search.'%')
            ->orWhere('id_jual', 'like', '%'.$search.'%')
            ->paginate(10);
    }

    public function getPenjualanByTempo($search)
    {
        return Penjualan::with([
            'customer'
        ])
            ->where('activeCash', session('ClosedCash'))
            ->where('status_bayar', 'like', '%tempo%')
            ->whereRelation('customer', 'nama_cust', 'like', '%'.$search.'%')
            ->orWhere('id_jual', 'like', '%'.$search.'%')
            ->paginate(10);
    }

    public function penjualanById($id)
    {
        return Penjualan::where('id', $id)->first();
    }

    public function setStatusPenjualan($idPenjualan, $statusBayar)
    {
        return Penjualan::where('id', $idPenjualan)
            ->update([
                'sudahBayar'=>$statusBayar
            ]);
    }

    /**
     * @param $idPenjualan
     * @return mixed
     */
    public function getBiayaPenjualanByPenjualan($idPenjualan)
    {
        return PenjualanBiaya::withForeign()
            ->where('penjualan_id', $idPenjualan)
            ->get();
    }

    /**
     * Get Penjualan one row
     * @param $id
     * @return mixed
     */
    public function getBiayaPenjualanRow($id)
    {
        return PenjualanBiaya::withForeign()->find($id);
    }

    /**
     * @param $dataBiayaPenjualan
     * @return mixed
     */
    public function storeBiayaPenjualan($dataBiayaPenjualan)
    {
        return PenjualanBiaya::updateOrCreate(
            [
                'id'=>$dataBiayaPenjualan->idBiayaPenjualan
            ],
            [
                'penjualan_id'=>$dataBiayaPenjualan->penjualanId,
                'account_id'=>$dataBiayaPenjualan->accountId,
                'nominal'=>$dataBiayaPenjualan->nominal,
                'keterangan'=>$dataBiayaPenjualan->keterangan
            ]
        );
    }

    public function destroyBiayaPenjualanById($idBiayaPenjualan)
    {
        return PenjualanBiaya::destroy($idBiayaPenjualan);
    }

    public function destroyBiayaPenjualanByPenjualan($idPenjualan)
    {
        return PenjualanBiaya::where('penjualan_id', $idPenjualan)
            ->delete();
    }
}
