<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountKategori;
use App\Models\Accounting\AccountKategoriSub;
use Livewire\Component;

class SelectedAccount extends Component
{
    public $kategori;
    public $kategoriSub;
    public $account;

    public $selectedKategori = null;
    public $selectedKategoriSub = null;
    public $selectedAccount = null;

    public function mount($selectedAccount = null)
    {
        $this->kategori = AccountKategori::all();
        $this->kategoriSub = collect();
        $this->account = collect();
        $this->selectedAccount = $selectedAccount;

        if (!is_null($selectedAccount)){
            $account = Account::with('accountKategori.kategori')->find($selectedAccount);
            if ($account) {
                $this->account = Account::where('kategori_sub_id', $account->kategori_sub_id)->get();
                $this->kategoriSub = AccountKategoriSub::where('kategori_id', $account->kategori->kategori_id)->get();
                $this->selectedKategori = $account->kategori->kategori_id;
                $this->selectedKategoriSub = $account->kategori_sub_id;
            }
        }
    }

    public function render()
    {
        return view('livewire.accounting.selected-account');
    }

    public function updatedSelectedKategori($kategori)
    {
        $this->kategoriSub = AccountKategoriSub::where('kategori_id')->get();
        $this->selectedKategoriSub = null;
    }

    public function updatedSelectedKategoriSub($kategoriSub)
    {
        $this->account = Account::where('kategori_sub_id', $kategoriSub);
        $this->selectedAccount = null;
    }
}
