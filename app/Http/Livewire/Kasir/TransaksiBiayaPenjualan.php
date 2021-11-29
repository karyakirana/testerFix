<?php

namespace App\Http\Livewire\Kasir;

use App\Models\Accounting\Account;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanBiaya;
use Livewire\Component;

class TransaksiBiayaPenjualan extends Component
{
    public $sessionBiaya, $idPenjualan, $dataPenjualan, $dataDetailPenjualan, $totalBayar, $dataBayar;
    public $indexBiaya;
    public $update;
    public $itemBiaya = [];
    public $acoount_id, $account, $tagihan, $nominal, $keterangan;
    public $accountName;
    public $selectBiaya, $selectTagihan;

    public function mount($idPenjualan)
    {
        $this->update = false;
        $this->idPenjualan = $idPenjualan;
        $this->dataPenjualan = Penjualan::where('id', $this->idPenjualan)->first();
        $this->dataBayar = $this->dataPenjualan->total_bayar;
        $this->selectBiaya = Account::whereRelation('accountKategori', 'deskripsi', 'like', '%biaya operasional%')
                        ->get();
    }

    public function setSessionBiaya()
    {
        //
    }

    public function selectedtagihan($namaTagihan)
    {
        $this->selectTagihan = $namaTagihan;
    }

    public function addBiaya()
    {
        $this->validate([
            'tagihan'=>'required',
            'nominal'=>'required'
        ]);

        $rowBiaya = Account::find($this->tagihan);

        $this->itemBiaya[] = [
            'penjualan'=>$this->idPenjualan,
            'tagihan'=>$this->tagihan,
            'namaTagihan'=>$rowBiaya->account_name,
            'nominal'=>$this->nominal,
            'keterangan'=>$this->keterangan,
        ];
        $this->totalBayar = $this->dataBayar + collect($this->itemBiaya)->sum('nominal');
        $this->resetFormBiaya();
    }

    public function editBiaya($index)
    {
        $this->update = true;
        $this->indexBiaya = $index;
        $this->tagihan = $this->itemBiaya[$index]['tagihan'];
        $this->nominal = $this->itemBiaya[$index]['nominal'];
        $this->keterangan = $this->itemBiaya[$index]['keterangan'];
    }

    public function updateBiaya()
    {
        $index = $this->indexBiaya;
        $this->itemBiaya[$index]['tagihan'] = $this->tagihan;
        $this->itemBiaya[$index]['nominal'] = $this->nominal;
        $this->itemBiaya[$index]['keterangan'] = $this->keterangan;
        $this->totalBayar = $this->dataBayar + collect($this->itemBiaya)->sum('nominal');
        $this->resetFormBiaya();
    }

    public function resetFormBiaya()
    {
        $this->tagihan = '';
        $this->nominal = '';
        $this->selectTagihan = '';
        $this->keterangan = '';
    }

    public function deleteBiaya($index)
    {
        unset($this->itemBiaya[$index]);
        $this->itemBiaya = array_values($this->itemBiaya);
        $this->totalBayar = $this->dataBayar + collect($this->itemBiaya)->sum('nominal');
    }

    public function simpanPenjualan()
    {
        foreach ($this->itemBiaya as $row)
        {
            PenjualanBiaya::create([
                'penjualan_id'=>$this->idPenjualan,
                'account_id'=>$row['tagihan'],
                'nominal'=>$row['nominal'],
                'keterangan'=>$row['keterangan']
            ]);
        }
        return redirect()->to('/kasir');
    }

    public function render()
    {
//        session()->pull('biayaPenjualan');
//        $this->setSessionBiaya();
        return view('livewire.kasir.transaksi-biaya-penjualan');
    }
}
