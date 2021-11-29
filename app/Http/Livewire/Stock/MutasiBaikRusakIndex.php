<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\MutasiGudangBaikRusakRepository;
use Livewire\Component;
use Livewire\WithPagination;

class MutasiBaikRusakIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function render()
    {
        return view('livewire.stock.mutasi-baik-rusak-index', [
            'dataStockMutasi'=>(new MutasiGudangBaikRusakRepository)->getDataBySearch($this->search)
        ]);
    }

    public function newOrder()
    {
        return redirect()->to(route('stock.mutasi.baik.rusak.transaksi'));
    }
}
