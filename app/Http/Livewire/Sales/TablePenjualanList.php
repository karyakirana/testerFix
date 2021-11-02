<?php

namespace App\Http\Livewire\Sales;

use App\Http\Repositories\Sales\SalesRepository;
use Livewire\Component;
use Livewire\WithPagination;

class TablePenjualanList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $penjualanList;
    public $penjualanPage;

    public function mount()
    {
        //
    }

    public function render()
    {
        return view('livewire.sales.table-penjualan-list', [
            'penjualan'=>(new SalesRepository())->getSalesAllByActiveCash(session('ClosedCash'))
        ]);
    }
}
