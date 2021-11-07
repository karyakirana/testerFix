<?php

namespace App\Http\Livewire\Kasir;

use App\Http\Repositories\Kasir\PenjualanRepository;
use App\Http\Repositories\Sales\SalesRepository;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search='';
    public $dataDetail;

    public function mount()
    {
        //
    }

    public function search()
    {
        //
    }

    protected function datatable()
    {
        return (new PenjualanRepository())->getPenjualanAll($this->search);
    }

    public function render()
    {
//        dd((new PenjualanRepository())->getPenjualanAll($this->search));
        return view('livewire.kasir.penjualan-list', [
            'penjualanAll'=>$this->datatable()
        ]);
    }
}
