<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\InventoryReal;
use Illuminate\Support\Facades\DB;

class InventoryRealRepository
{
    // rollback stock masuk
    public function rollback($dataInventory, $branch)
    {
        InventoryReal::where('idProduk', $dataInventory->id_produk)
            ->where('branchId', $branch)
            ->update([
                'stockIn'=>DB::raw('stockIn -'.$dataInventory->jumlah),
                'stockNow'=>DB::raw('stockNow -'.$dataInventory->jumlah),
            ]);
    }

    // stock masuk
    public function insertOrUpdate($dataInventory)
    {
        // check availability
        $inventoryReal = InventoryReal::where('idProduk', $dataInventory->id_produk)
            ->where('branchId', $dataInventory->branch_id)
            ->get()->count();
        if ($inventoryReal > 0){
            // update data
            InventoryReal::where('idProduk', $dataInventory->id_produk)
                ->where('branchId', $dataInventory->branch_id)
                ->update([
                    'stockIn'=>DB::raw('stockIn +'.$dataInventory->jumlah),
                    'stockNow'=>DB::raw('stockNow +'.$dataInventory->jumlah),
                ]);
        } else {
            InventoryReal::create([
                'idProduk'=>$dataInventory->id_produk,
                'branchId'=>$dataInventory->branch_id,
                'stockIn'=>$dataInventory->jumlah,
                'stockNow'=>DB::raw('stockNow +'.$dataInventory->jumlah),
            ]);
        }
    }
}
