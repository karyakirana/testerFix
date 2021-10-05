<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMasukRusakDetil extends Model
{
    use HasFactory;
    protected $table = 'stock_masuk_rusak_detil';
    protected $fillable = ['stock_masuk_rusak_id', 'produk_id', 'jumlah'];

    public function stockMasukRusak()
    {
        return $this->belongsTo(StockMasukRusak::class, 'stock_masuk_rusak_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }
}
