<?php

namespace App\Http\Livewire\Table;

use App\Http\Repositories\Master\CustomerRepository;
use App\Models\Master\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public function render()
    {
        return view('livewire.table.customer-table', [
            'dataCustomer'=>(new CustomerRepository())->getCustomerSearch($this->search)
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setCustomer($idCust)
    {
        $this->emit('getDataCustomer', $idCust);
        $this->emit('closeCustomerModal');
        $this->search ='';
    }
}
