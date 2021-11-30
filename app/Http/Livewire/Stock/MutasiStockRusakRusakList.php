<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\MutasiGudangRusakRepository;
use Livewire\Component;
use Livewire\WithPagination;

class MutasiStockRusakRusakList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function render()
    {
        return view('livewire.stock.mutasi-stock-rusak-rusak-list', [
            'dataMutasiRusak'=>(new MutasiGudangRusakRepository())->getDataBySearch($this->search)
        ]);
    }

    public function newData()
    {
        return redirect()->to(route('stock.mutasi.rusak.rusak.transaksi'));
    }
}
