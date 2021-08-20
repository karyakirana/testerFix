<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOrderDetil extends Model
{
    use HasFactory;
    protected $table = 'stock_preorder_detil';
    protected $fillable = [
        'stock_preorder',
        'id_produk',
        'jumlah'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
