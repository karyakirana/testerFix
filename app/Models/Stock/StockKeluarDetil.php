<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockKeluarDetil extends Model
{
    use HasFactory;
    protected $table = 'stock_keluar_detil';
    protected $fillable = [
        'stock_keluar', 'id_produk', 'jumlah'
    ];
}
