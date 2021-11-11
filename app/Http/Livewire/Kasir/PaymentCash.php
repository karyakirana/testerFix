<?php

namespace App\Http\Livewire\Kasir;

use App\Models\Accounting\Account;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use Livewire\Component;

class PaymentCash extends Component
{
    public $idPenjualan;
    public $penjualan, $penjualanDetail;
    public $daftarPenjualanDibayar = [];
    public $metodepembayaran, $accountKas;

    public function mount($idPenjualan)
    {
        $this->accountKas = Account::whereRelation('accountKategori', 'deskripsi', 'like', '%kas%')->get();
        $this->idPenjualan = $idPenjualan;
        $this->daftarPenjualanDibayar[]=Penjualan::where('id', $idPenjualan)->first();
    }

    public function penjualanDetail($idJual)
    {
        $penjualanDetail = PenjualanDetil::where('id_jual', $idJual);
    }

    public function deletePenjualan($index)
    {
        unset($this->daftarPenjualanDibayar[$index]);
        $this->daftarPenjualanDibayar = array_values($this->daftarPenjualanDibayar);
    }

    public function render()
    {
        return view('livewire.kasir.payment-cash');
    }
}
