<?php

namespace App\Http\Livewire\Kasir;

use Livewire\Component;

class TransaksiBiayaPenjualan extends Component
{
    public $sessionBiaya, $idPenjualan;
    public $itemBiaya = [];
    public $acoount_id, $account, $tagihan, $nominal, $keterangan;

    public function mount()
    {
        //
    }

    public function setSessionBiaya()
    {
        //
    }

    public function addBiaya()
    {
        $this->itemBiaya[] = [
            'account_id'=>$this->account_id,
            'account'=>$this->account,
            'tagihan'=>$this->tagihan,
            'nominal'=>$this->nominal,
            'keterangan'=>$this->keterangan,
        ];
        $this->resetFormBiaya();
    }

    public function resetFormBiaya()
    {
        $this->account = '';
        $this->tagihan = '';
        $this->nominal = '';
        $this->keterangan = '';
    }

    public function render()
    {
//        session()->pull('biayaPenjualan');
//        $this->setSessionBiaya();
        return view('livewire.kasir.transaksi-biaya-penjualan');
    }
}
