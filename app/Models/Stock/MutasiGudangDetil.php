<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiGudangDetil extends Model
{
    use HasFactory;
    protected $table = 'mutasi_gudang_detil';
    protected $fillable = [
        'id_mutasi_gudang',
        'id_produk',
        'jumlah'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
