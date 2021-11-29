<?php

namespace App\Http\Livewire\Table;

use App\Http\Repositories\Master\ProdukRepository;
use App\Models\Master\Produk;
use Livewire\Component;
use Livewire\WithPagination;

class ProdukTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function setProduk($id)
    {
        $this->emit('getDataProduk', $id);
        $this->emit('closeProdukModal');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.table.produk-table', [
            'dataProduk'=>(new ProdukRepository())->getProdukSearch($this->search)
        ]);
    }
}
