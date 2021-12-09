<?php

namespace App\Http\Livewire\Kasir;

use Livewire\Component;

class TransaksiPenerimaanCash extends Component
{
    public $transaksiId;
    public $account;

    public function mount($transaksiId = null)
    {
        //
    }

    public function render()
    {
        return view('livewire.kasir.transaksi-penerimaan-cash');
    }
}
