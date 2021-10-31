<?php

namespace App\View\Components;

use App\Http\Repositories\Sales\SalesRepository;
use Illuminate\View\Component;

class TablePenjualanBiaya extends Component
{
    public $penjualan;
    public $penjualanDetail;

    public function __construct($idPenjualan)
    {
        $this->penjualan = (new SalesRepository())->getPenjualanAndDetail($idPenjualan);
        $this->penjualanDetail = $this->penjualan->detilPenjualan;
    }

    public function render()
    {
        return view('components.nano.table-biaya-penjualan');
    }
}
