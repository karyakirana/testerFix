<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiGudangRusak extends Model
{
    use HasFactory;
    protected $table = 'mutasi_gudang_rusak';

    protected $fillable = [
        'activeCash',
        'kode',
        'gudang_asal',
        'gudang_tujuan',
        'tgl_mutasi',
        'user_id',
        'keterangan'
    ];
}
