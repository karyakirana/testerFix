<?php

namespace App\Http\Livewire\Mikro;

use App\Http\Repositories\Kasir\PenjualanRepository;
use App\Models\Sales\PenjualanBiaya;
use Illuminate\Support\Carbon;
use Livewire\Component;

class PenjualanDetail extends Component
{
    protected $listeners = ['showDetail'=>'dataDetail'];

    public $idPenjualan;

    public function render()
    {
        return view('livewire.mikro.penjualan-detail');
    }

    public function dataDetail()
    {
        $id = $this->idPenjualan;
        $data = (new PenjualanRepository())->penjualanById($id);
        $this->biayaPenjualan = PenjualanBiaya::where('penjualan_id', $id)->get();
        if ($this->biayaPenjualan->count()>0){
            $totalBayar = $this->biayaPenjualan->sum('nominal');
        }
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
            'totalBayar'=>$data->total_bayar+$totalBayar,
        ];
    }
}
