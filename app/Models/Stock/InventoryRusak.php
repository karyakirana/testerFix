<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRusak extends Model
{
    use HasFactory;

    protected $table = 'inventory_real_rusak';
    protected $fillable = [
        'idProduk',
        'branchId',
        'stockIn',
        'stockOut',
        'stockNow',
    ];
}
