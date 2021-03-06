<?php

namespace App\Models\Sales;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturRusakDetil extends Model
{
    use HasFactory;
    protected $table = 'rr_detail';
    protected $fillable =[
        'retur_rusak_id',
        'id_rr',
        'id_produk',
        'jumlah',
        'harga',
        'diskon',
        'sub_total'
    ];

    public function scopeRelation($query)
    {
        return $query->with(['produk']);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
