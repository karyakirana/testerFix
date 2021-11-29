<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiBaikRusakDetail extends Model
{
    use HasFactory;
    protected $table = 'mutasi_baik_rusak_detail';
    protected $fillable = [
        'mutasi_gudang_id',
        'produk_id',
        'jumlah',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class,'produk_id', 'id_produk');
    }

    public function mutasiGudang()
    {
        return $this->hasMany(MutasiBaikRusak::class, 'mutasi_gudang_id', 'id');
    }
}
