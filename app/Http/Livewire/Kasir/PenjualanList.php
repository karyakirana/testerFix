<?php

namespace App\Http\Livewire\Kasir;

use App\Http\Repositories\Sales\SalesRepository;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '1772';

    public function mount()
    {
        //
    }

    public function search()
    {
        //
    }

    public function render()
    {
        return view('livewire.kasir.penjualan-list', [
            'penjualanAll'=>(new SalesRepository())->getSalesAllByActiveCash(session('ClosedCash'), $this->search)
        ]);
    }
}
