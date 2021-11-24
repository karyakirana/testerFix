<?php

namespace App\Http\Livewire\Table;

use App\Models\Master\Produk;
use Livewire\Component;
use Livewire\WithPagination;

class ProdukTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function setProduk($id)
    {
        $this->emit('getDataProduk', $id);
        $this->emit('closeProdukModal');
    }

    public function render()
    {
        return view('livewire.table.produk-table', [
            'dataProduk'=>Produk::paginate(10, ['*'], 'produkpage')
        ]);
    }
}
