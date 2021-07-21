<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriHarga extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kategori_harga';
    protected $keyType = 'string';
    protected $primaryKey = 'id_kat_harga';
    protected $fillable =[
        'id_kat_harga', 'nama_kat', 'keterangan'
    ];
}
