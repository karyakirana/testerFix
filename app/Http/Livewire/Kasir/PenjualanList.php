<?php

namespace App\Http\Livewire\Kasir;

use App\Http\Repositories\Kasir\PenjualanRepository;
use App\Http\Repositories\Sales\SalesRepository;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $dataDetail;

    public function mount()
    {
        //
    }

    public function dataDetail($id)
    {
        $data = (new PenjualanRepository())->penjualanById($id);
//        dd($data);
        $this->dataDetail = [
            'id'=>$data->id,
            'customer'=>$data->customer->nama_cust,
            'pembuat'=>$data->pengguna->name,
            'jenis'=>$data->status_bayar,
            'status'=>$data->sudahBayar,
            'gudang'=>$data->branch->branchName,
            'tglNota'=>Carbon::parse($data->tgl_nota)->locale('id_ID')->isoFormat('LL'),
            'tglTempo'=>Carbon::parse($data->tgl_tempo)->locale('id_ID')->isoFormat('LL'),
            'keterangan'=>$data->keterangan,
            // Penjualan Detail
            'penjualanDetail'=>$data->detilPenjualan,
            'ppn'=>$data->ppn,
            'biaya_lain'=>$data->biaya_lain,
            'totalBayar'=>$data->total_bayar,
        ];
    }

    public function closePegawai()
    {
        $this->dataDetail = null;
        $this->emit('closeModalDetailPegawai');
    }

    protected function datatable()
    {
        return (new PenjualanRepository())->getPenjualanAll($this->search);
    }

    public function render()
    {
        return view('livewire.kasir.penjualan-list', [
            'penjualanAll'=>$this->datatable()
        ]);
    }

    public function tambahBiaya($id)
    {
        return redirect()->to('/kasir/payment/tambahbiaya/'.$id);
    }

    public function editBiaya($id)
    {
        return redirect()->to('/kasir/payment/tambahbiaya/'.$id.'/edit');
    }

    public function setToPiutang($id)
    {
        return redirect()->to('kasir/piutang/penjualan');
    }
}
