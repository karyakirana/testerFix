<?php

namespace App\Http\Livewire\Sales;

use App\Http\Repositories\Sales\SalesRepository;
use Livewire\Component;

class PenjualanBiaya extends Component
{
    public $penjualan;
    public $penjualanDetail;

    public function mount($idPenjualan)
    {
        $this->penjualan = (new SalesRepository())->getPenjualanAndDetail($idPenjualan);
    }

    public function render()
    {
        return view('livewire.sales.penjualan-biaya');
    }
}
