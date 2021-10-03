<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalRef extends Model
{
    use HasFactory;

    protected $table = 'jurnal_ref';
    protected $fillable = [
        'kode_jurnal',
        'deskripsi',
        'keterangan'
    ];
}
