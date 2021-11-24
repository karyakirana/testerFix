<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use Livewire\Component;

class MasterAccount extends Component
{
    public function render()
    {
        return view('livewire.accounting.master-account', [
            'daftarAkun'=>Account::paginate(10)
        ]);
    }
}
