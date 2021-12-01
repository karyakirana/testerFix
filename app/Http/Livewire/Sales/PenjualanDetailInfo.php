<?php

namespace App\Http\Livewire\Sales;

use App\Models\Sales\Penjualan;
use Livewire\Component;

class PenjualanDetailInfo extends Component
{
    protected $listeners = ['showDetailInfo'];

    public $penjualan, $penjualanDetail;

    public function mount($idPenjualan = null)
    {
        //
    }

    public function showDetailInfo($idPenjualan)
    {
        $this->penjualan = Penjualan::query()
            ->where('id_jual', $idPenjualan)
            ->orWhere('id', $idPenjualan)
            ->first();
        $this->penjualanDetail = $this->penjualan->detilPenjualan;
    }

    public function render()
    {
        return view('livewire.sales.penjualan-detail-info');
    }
}
