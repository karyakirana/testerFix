<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanTemp extends Model
{
    use HasFactory;
    protected $table = 'penjualan_temp';
    protected $fillable = [
        'jenisTemp', 'idJual', 'idSales'
    ];
}
