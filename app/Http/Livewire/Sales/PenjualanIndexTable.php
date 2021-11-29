<?php

namespace App\Http\Livewire\Sales;

use App\Http\Repositories\Sales\PenjualanRepository;
use App\Models\Sales\Penjualan;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanIndexTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public $penjualanId, $customer, $gudang, $pembuat, $tglNota, $tglTempo, $jenisBayar;
    public $statusBayar, $keterangan, $totalJumlah, $ppn, $biayaLain, $totalBayar;
    public $penjualanDetail;

    public function render()
    {
        return view('livewire.sales.penjualan-index-table',[
            'dataPenjualan'=>(new PenjualanRepository())->getPenjualanForSearch($this->search)
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openPreview($idJual)
    {
        $penjualan = Penjualan::where('id_jual', $idJual)->first();

        // field general
        $this->penjualanId = $penjualan->id_jual;
        $this->customer = $penjualan->customer->nama_cust;
        $this->gudang = ucfirst($penjualan->branch->branchName);
        $this->pembuat = $penjualan->pengguna->name;
        $this->tglNota = tanggalan_format($penjualan->tgl_nota);
        $this->tglTempo = tanggalan_format($penjualan->tgl_tempo);
        $this->jenisBayar = ucfirst($penjualan->status_bayar);
        $this->statusBayar = $penjualan->sudahBayar;
        $this->keterangan = $penjualan->keterangan;

        // field biaya
        $this->biayaLain = $penjualan->biaya_lain;
        $this->ppn = $penjualan->ppn;
        $this->totalBayar = $penjualan->total_bayar;

        // field Detail
        $this->penjualanDetail = $penjualan->detilPenjualan;
        $this->emit('openPreview');
    }

    public function closePreview()
    {
        $this->emit('closePreview');
    }

    public function edit($id)
    {
        return redirect()->to('/sales/penjualan/'.str_replace('/', '-', $id));
    }

    public function printPreview($id)
    {
        return redirect()->to('/sales/print/'.str_replace('/', '-', $id));
    }
}
