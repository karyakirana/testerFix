<?php

namespace App\Models\Sales;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturBaikDetil extends Model
{
    use HasFactory;
    protected $table = 'rb_detail';
    protected $fillable = [
        'id_return',
        'id_produk',
        'jumlah',
        'harga',
        'diskon',
        'sub_total'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
