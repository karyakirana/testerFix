<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\InventoryRusak;
use Illuminate\Support\Facades\DB;

class InventoryRusakRealRepository
{

    public function getData($branch = null)
    {
        if ($branch == null){
            $data = InventoryRusak::with(['produk', 'branch'])
            ->orderBy('idProduk')
            ->get();
        } else {
            $data = InventoryRusak::where('branchId', $branch)
                ->orderBy('idProduk')->get();
        }
        return $data;
    }

    public function getDataSumJumlah()
    {
        return InventoryRusak::select(DB::raw('sum(stockOpname) as stockOpname, sum(stockIn) as stockInAll, sum(stockOut) as stockOutAll'))
            ->groupBy('idProduk')
            ->get();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createOrUpdate($data)
    {
        return InventoryRusak::updateOrInsert(
            [
                'idProduk'=>$data->idProduk,
                'branchId'=>$data->branch,
            ],
            [
                'stockOut'=>DB::raw('stockOut +'.$data->jumlah),
            ]
        );
    }
}
