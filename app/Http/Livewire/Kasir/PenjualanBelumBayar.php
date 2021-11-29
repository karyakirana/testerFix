<?php

namespace App\Http\Livewire\Kasir;

use App\Http\Repositories\Accounting\PiutangRepository;
use App\Http\Repositories\Sales\PenjualanRepository;
use App\Models\Sales\Penjualan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanBelumBayar extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public $penjualanId, $primaryKeyPenjualan, $customer, $gudang, $pembuat, $tglNota, $tglTempo, $jenisBayar;
    public $statusBayar, $keterangan, $totalJumlah, $ppn, $biayaLain, $totalBayar;
    public $penjualanDetail;

    public $hasilKonfirmasi;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showDetailInfo($idPenjualan)
    {
        $this->penjualanId = $idPenjualan;
        $this->emit('showDetailInfo', $idPenjualan);
    }

    public function hideDetailInfo()
    {
        $this->emit('hideDetailInfo');
    }

    public function konfirmasi($konfirmasi)
    {
        switch ($konfirmasi){
            case "setPiutang":
                $this->hasilKonfirmasi = 'setPiutang';
                break;
            case "setLunas":
                $this->hasilKonfirmasi = 'setLunas';
                break;
            case "tambahBiaya":
                $this->hasilKonfirmasi = 'tambahBiaya';
                break;
            default:
                $this->hasilKonfirmasi = 'cancelKonfirmasi';
        }
        $this->hideDetailInfo();
        $this->emit('showModalKonfirmasi');
    }

    public function modalKonfirmasi()
    {
        $this->{$this->hasilKonfirmasi}();
        $this->emit('hideModalKonfirmasi');
    }

    public function setPiutang()
    {
        $penjualan = Penjualan::where('id_jual', $this->penjualanId)->first();
        $dataPenjualan = [
            'idPenjualan'=>$penjualan->id,
            'totalBayar'=>$penjualan->total_bayar,
            'userId'=>auth()->id(),
            'statusBayar'=>'piutang'
        ];
        DB::beginTransaction();
        try {
            (new PiutangRepository())->setPiutangFromPenjualan($dataPenjualan);
            (new PenjualanRepository())->setStatusBelumBayar($this->penjualanId, 'piutang');
            DB::commit();
            session()->flash('message', 'Piutang sudah dibuat');
            $this->emit('hideModalKonfirmasi');
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            session()->flash('message', 'Piutang gagal dibuat <br> '. $e);
            $this->emit('hideModalKonfirmasi');
        }
    }

    public function setLunas()
    {
        dd('setLunas');
    }

    public function tambahBiaya()
    {
        $penjualan = Penjualan::where('id_jual', $this->penjualanId)->first();
        return redirect()->to('kasir/tambahbiaya/'.$penjualan->id);
    }

    public function cancelKonfirmasi()
    {
        dd('cancelKonfirmasi');
    }

    public function render()
    {
        return view('livewire.kasir.penjualan-belum-bayar', [
            'dataPenjualan'=>(new PenjualanRepository())->getPenjualanBelumBayar($this->search)
        ]);
    }
}
