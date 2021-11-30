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

    // stock keluar array
    public function updateInventoryOut(array $data)
    {
        $inventoryReal = InventoryReal::where('idProduk', $data['produkId'] ?? $data['produk_id'])
            ->where('branchId', $data['branchId'])
            ->get()->count();
        if ($inventoryReal > 0){
            // update data
            InventoryReal::where('idProduk', $data['produkId'] ?? $data['produk_id'])
                ->where('branchId', $data['branchId'])
                ->update([
                    'stockOut'=>DB::raw('stockOut +'.$data['jumlah']),
                    'stockNow'=>DB::raw('stockNow -'.$data['jumlah']),
                ]);
        } else {
            InventoryReal::create([
                'idProduk'=>$data['produkId'] ?? $data['produk_id'],
                'branchId'=>$data['branchId'],
                'stockOut'=>$data['jumlah'],
                'stockNow'=>DB::raw('stockNow +'.$data['jumlah']),
            ]);
        }
    }

    // rollback inventoryOut by StockKeluar
    public function rollbackInventoryOut($stockKeluarDetail, $branchId)
    {
        foreach ($stockKeluarDetail as $row)
        {
            $inventoryReal = InventoryReal::where('idProduk', $row->id_produk)
                ->where('branchId', $branchId)
                ->get()->count();
            InventoryReal::where('idProduk', $row->id_produk)
                ->where('branchId', $branchId)
                ->update([
                    'stockOut'=>DB::raw('stockOut -'.$row->jumlah),
                    'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                ]);
        }
    }
}
