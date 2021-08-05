<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTemp extends Model
{
    use HasFactory;
    protected $table = 'stock_temp';
    protected $fillable = [
        'activeCash', 'kode', 'idBranch', 'idSupplier', 'idUser', 'tglMasuk', 'nomorPo', 'keterangan'
    ];
}
