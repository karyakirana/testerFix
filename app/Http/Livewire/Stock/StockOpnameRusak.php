<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\StokcOpnameRusakRepository;
use Livewire\Component;
use Livewire\WithPagination;

class StockOpnameRusak extends Component
{
    use WithPagination;
    protected $paginationTheme = 'metronics-bootstrap';


    public function render()
    {
        return view('livewire.stock.stock-opname-rusak', [
            'dataOpname'=>(new StokcOpnameRusakRepository)->getData()
        ]);
    }

    public function editItem()
    {
        return redirect()->to(route('stock.opname.rusak.transaksi'));
    }

    public function newData()
    {
        return redirect()->to(route('stock.opname.rusak.transaksi'));
    }

    public function deleteItem()
    {

    }
}
