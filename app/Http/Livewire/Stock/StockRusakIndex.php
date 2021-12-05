<?php

namespace App\Http\Livewire\Stock;

use App\Models\Stock\InventoryRusak;
use Illuminate\Validation\Rules\In;
use Livewire\WithPagination;
use Livewire\Component;

class StockRusakIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $idProduk, $branchId, $stockOpname, $stockIn, $stockOut, $stockNow;

    public $search = '';

    public function removeData($id)
    {
        InventoryRusak::where('id', $id)->delete();
    }


    public function render()
    {
        return view('livewire.stock.stock-rusak-index', [
            'datainventory_real_rusak'=>InventoryRusak::paginate(10),
        ]);
    }
}
