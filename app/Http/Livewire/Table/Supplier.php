<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;
use App\Http\Repositories\Master\SupplierRepository;
use Livewire\WithPagination;

class Supplier extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function render()
    {
        return view('livewire.table.supplier',[
            'dataSupplier'=>(new SupplierRepository())->getSupplierSearch($this->search)
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setSupplier($idSupp)
    {
        $this->emit('getDataSupplier', $idSupp);
        $this->emit('closeSupplierModal');
        $this->search ='';
    }

}
