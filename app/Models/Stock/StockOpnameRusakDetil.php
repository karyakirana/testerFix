<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameRusakDetil extends Model
{
    use HasFactory;

    protected $table = 'stock_opname_rusak_detil';
    protected $fillable = ['stock_opname_rusak_id', 'produk_id', 'jumlah'];

    public function stockOpnameRusak()
    {
        return $this->belongsTo(StockOpnameRusak::class, 'stock_opname_rusak_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id_produk');
    }
}
