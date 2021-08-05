<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockDetilTemp extends Model
{
    use HasFactory;
    protected $table = 'stock_detil_temp';
    protected $fillable = [
        'stockTemp', 'idProduk', 'jumlah'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idProduk');
    }
}
