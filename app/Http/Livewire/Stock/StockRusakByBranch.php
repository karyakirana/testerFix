<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\InventoryRusakRealRepository;
use App\Models\Stock\BranchStock;
use App\Models\Stock\InventoryRusak;
use Livewire\Component;
use Livewire\WithPagination;

class StockRusakByBranch extends Component
{
    use WithPagination;
    protected $paginationTheme = 'metronics-bootstrap';

    public $search = '';
    public $paginate = 10;

    public $idBranch;
    public $dataInventoryRusak;
    public $gudang;

    // stock In Detail
    public $dataIn, $jumlahIn;

    public function mount($idBranch)
    {
        $this->idBranch = $idBranch;
        $this->gudang = BranchStock::find($this->idBranch)->branchName;
    }

    public function render()
    {
        return view('livewire.stock.stock-rusak-by-branch', [
            'inventoryRusak'=>(new InventoryRusakRealRepository)->searchInventoryRusakByBrach($this->idBranch, $this->search, $this->paginate)
        ]);
    }

    public function stockInByProduk($idProduk)
    {
        $data = (new InventoryRusakRealRepository())->detilInInventoryReal($this->idBranch, $idProduk);
        $this->dataIn = $data;
        $this->emit('showStockInProduk');
    }
}
