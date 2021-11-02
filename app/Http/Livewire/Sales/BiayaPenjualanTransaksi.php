<?php

namespace App\Http\Livewire\Sales;

use App\Http\Repositories\Accounting\JournalTempRepository;
use App\Http\Repositories\Sales\SalesRepository;
use Livewire\Component;

class BiayaPenjualanTransaksi extends Component
{
    public $penjualanMaster;
    public $penjualanDetail;
    public $biayaTempMaster;
    public $biayaTemDetail;

    public function mount($idPenjualan, $idTemp)
    {
        $this->penjualanMaster = (new SalesRepository())->getSalesById($idPenjualan);
        $this->penjualanDetail = $this->penjualanMaster->detilPenjualan;
        $this->biayaTemDetail = (new JournalTempRepository())->getDetailByMaster($idTemp);
    }

    public function render()
    {
        return view('livewire.sales.biaya-penjualan-transaksi');
    }
}
