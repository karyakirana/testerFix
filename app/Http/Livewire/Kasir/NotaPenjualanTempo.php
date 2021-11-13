<?php

namespace App\Http\Livewire\Kasir;

use App\Http\Repositories\Kasir\PenjualanRepository;
use App\Models\Sales\Penjualan;
use Livewire\Component;
use Livewire\WithPagination;

class NotaPenjualanTempo extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function mount()
    {
        //
    }
    public function render()
    {
        return view('livewire.kasir.nota-penjualan-tempo', [
            'penjualanTempo'=>(new PenjualanRepository())->getPenjualanByTempo($this->search)
        ])->layout('layouts.metronics');
    }
}
