<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\InventoryRusak;
use App\Models\Stock\StockMasukRusak;
use App\Models\Stock\StockMasukRusakDetil;
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

    public static function CreateStockIn($branch, $dataDetil)
    {
        $inventory = InventoryRusak::where('idProduk', $dataDetil->produk_id)
            ->where('branchId', $branch)->get()->count();
        if ($inventory > 0){
            InventoryRusak::where('idProduk', $dataDetil->produk_id)
                ->where('branchId', $branch)
                ->update([
                    'stockIn'=>DB::raw('stockIn +'.$dataDetil->jumlah),
                ]);
        } else {
            InventoryRusak::create([
                'idProduk'=>$dataDetil->produk_id,
                'branchId'=>$branch,
                'stockOpname'=>0,
                'stockIn'=>$dataDetil->jumlah
            ]);
        }
    }

    public static function rollbackStockIn($branch, $dataDetil)
    {
        InventoryRusak::where('idProduk', $dataDetil->id_produk)
            ->where('branchId', $branch)
            ->update([
                'stockIn'=>DB::raw('stockIn -'.$dataDetil->jumlah),
            ]);
    }

    public function storeStockIn($branch, array $dataDetil)
    {
        $inventory = InventoryRusak::where('idProduk', $dataDetil['produk_id'])
            ->where('branchId', $branch)->get()->count();
        if ($inventory > 0){
            InventoryRusak::where('idProduk', $dataDetil['produk_id'])
                ->where('branchId', $branch)
                ->update([
                    'stockIn'=>DB::raw('stockIn +'.$dataDetil['jumlah']),
                ]);
        } else {
            InventoryRusak::create([
                'idProduk'=>$dataDetil['produk_id'],
                'branchId'=>$branch,
                'stockOpname'=>0,
                'stockIn'=>$dataDetil['jumlah']
            ]);
        }
    }

    public function searchInventoryRusakByBrach($branch, $search, $paginate)
    {
        $data = InventoryRusak::query()
            ->where('branchId', $branch)
            ->Where('idProduk', 'like', '%'.$search.'%')
            ->orWhereRelation('produk', 'nama_produk', 'like', '%'.$search.'%');
        if($paginate > 0){
            return $data->paginate($paginate);
        } else {
            return $data->get();
        }
    }

    public function detilInInventoryReal($idbranch, $idProduk)
    {
        return StockMasukRusakDetil::query()
            ->where('produk_id', $idProduk)
            ->whereRelation('stockMasukRusak', 'branch_id', '=', $idbranch)
            ->get();
    }
}

