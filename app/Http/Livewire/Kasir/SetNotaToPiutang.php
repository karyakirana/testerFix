<?php

namespace App\Http\Livewire\Kasir;

use App\Models\Sales\Penjualan;
use Livewire\Component;

class SetNotaToPiutang extends Component
{
    public $listNota = [];

    protected $listeners = ['getPenjualan'];

    public $penjualan;

    public function render()
    {
        return view('livewire.kasir.set-nota-to-piutang');
    }

    public function showNota()
    {
        $this->emit('showModalNota');
    }

    public function getPenjualan($idPenjualan)
    {
        $penjualan = Penjualan::where('id', $idPenjualan)->first();
        $this->listNota[] = [
            'id'=>$penjualan->id,
            'idJual'=>$penjualan->id_jual,
            'customerId'=>$penjualan->cust_id,
            'customer'=>$penjualan->customer->nama_cust,
            'tglNota'=>$penjualan->tgl_nota,
            'tglTempo'=>$penjualan->tgl_tempo,
            'totalBayar'=>$penjualan->total_bayar
        ];
    }

    public function showDetail($idPenjualan)
    {
        $this->emit('showDetailInfo', $idPenjualan);
    }
}
