<?php

namespace App\Http\Livewire\Table;

use App\Models\Sales\Penjualan;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public $dataPenjualan;

    public function mount()
    {
        //
    }

    public function render()
    {
        return view('livewire.table.penjualan-table', [
            'penjualan'=>Penjualan::query()
                ->where('sudahBayar', 'belum')
                ->latest('id_jual')
                ->paginate(10)
        ]);
    }

    public function setPenjualan($penjualanId)
    {
        $this->emit('getPenjualan', $penjualanId);
    }
}
