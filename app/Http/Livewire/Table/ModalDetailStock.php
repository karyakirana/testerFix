<?php

namespace App\Http\Livewire\Table;

use Livewire\Component;

class ModalDetailStock extends Component
{
    protected $listeners = ['showDetail'];

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.table.modal-detail-stock');
    }

    public function showDetail($idMaster)
    {
        //
    }
}
