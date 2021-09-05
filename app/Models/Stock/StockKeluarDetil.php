<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockKeluarDetil extends Model
{
    use HasFactory;
    protected $table = 'stock_keluar_detil';
    protected $fillable = [
        'stock_keluar', 'id_produk', 'jumlah'
    ];

    public function produk(){
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function stockKeluar()
    {
        return $this->belongsTo(StockKeluar::class, 'stock_keluar');
    }
}
