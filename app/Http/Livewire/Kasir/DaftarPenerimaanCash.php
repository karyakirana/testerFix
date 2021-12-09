<?php

namespace App\Http\Livewire\Kasir;

use App\Models\Kasir\PenerimaanCash;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarPenerimaanCash extends Component
{
    use WithPagination;
    protected $paginationTheme = 'metronics-bootstrap';

    public $search; // search
    public $paginate = 10; // paginate

    public function render()
    {
        return view('livewire.kasir.daftar-penerimaan-cash', [
            'dataPenerimaanCash'=>PenerimaanCash::query()
                ->where('activeCash', session('ClosedCash'))
                ->where('kode_penerimaan_cash', 'like', '%'.$this->search.'%')
                ->orWhereRelation('users', 'name','like', '%'.$this->search.'%')
                ->orWhereRelation('customer', 'nama_cust','like', '%'.$this->search.'%')
                ->paginate($this->paginate)
        ]);
    }
}
