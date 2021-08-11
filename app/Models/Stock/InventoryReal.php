<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReal extends Model
{
    use HasFactory;

    protected $table = 'inventory_real';
    protected $fillable = [
        'idProduk',
        'branchId',
        'stockIn',
        'stockOut',
        'stockNow'
    ];

    public function branch(){
        return $this->belongsTo(BranchStock::class, 'branchId');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idProduk', 'id_produk');
    }
}
