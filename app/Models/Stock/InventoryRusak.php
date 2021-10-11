<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRusak extends Model
{
    use HasFactory;

    protected $table = 'inventory_real_rusak';
    protected $fillable = [
        'idProduk',
        'branchId',
        'stockOpname',
        'stockIn',
        'stockOut',
        'stockNow',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idProduk', 'id_produk');
    }

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'branchId');
    }
}
