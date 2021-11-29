<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockKeluarRusakDetil extends Model
{
    use HasFactory;
    protected $table = 'stock-keluar_rusak_detil';
    protected $fillable = [
        'stock_keluar_rusak_id',
        'produk_id',
        'jumlah',
    ];

    public function stockKeluarRusak()
    {
        return $this->belongsTo(StockKeluarRusak::class, 'stock_keluar_rusak_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }
}
