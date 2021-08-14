<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAkhirDetil extends Model
{
    use HasFactory;
    protected $table = 'stockakhir';

    protected $fillable = [
        'id_stock_akhir', 'id_produk', 'jumlah_stock'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function stockAkhir()
    {
        return $this->belongsTo(StockAkhir::class, 'id_stock_akhir');
    }
}
