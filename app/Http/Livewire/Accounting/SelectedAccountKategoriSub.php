<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\AccountKategori;
use App\Models\Accounting\AccountKategoriSub;
use Livewire\Component;

class SelectedAccountKategoriSub extends Component
{
    public $kategori;
    public $kategoriSub;

    public $selectedKategori = null;
    public $selectedKategoriSub = null;

    public function mount($selectedSub=null)
    {
        $this->kategori = AccountKategori::all();
        $this->kategoriSub = collect();
        $this->selectedKategoriSub = $selectedSub;

        if (is_null($selectedSub)) {
            $sub = AccountKategoriSub::with('kategori')->find($selectedSub);
            if ($sub) {
                $this->kategori = AccountKategoriSub::where('kategori_id', $sub->kategori_id);
                $this->selectedKategori = $sub->kategori->kategori_id;
            }
        }
    }

    public function render()
    {
        return view('livewire.accounting.selected-account-kategori-sub');
    }

    public function updatedSelectedKategori($kategori)
    {
        $this->kategoriSub = AccountKategoriSub::where('kategori_id',$kategori)->get();
        $this->selectedKategoriSub = null;
    }
}
