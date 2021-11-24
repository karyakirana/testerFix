<?php

namespace App\Http\Livewire\Table;

use App\Models\Master\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.table.customer-table', [
            'dataCustomer'=>Customer::paginate(10, ['*'], 'customerpage')
        ]);
    }

    public function setCustomer($idCust)
    {
        $this->emit('getDataCustomer', $idCust);
        $this->emit('closeCustomerModal');
    }
}
