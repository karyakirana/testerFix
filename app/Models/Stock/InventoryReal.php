<?php

namespace App\Models\Stock;

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
}
