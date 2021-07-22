<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supplier';
    protected $fillable = [
        'kodeSupplier', 'jenisSupplier', 'namaSupplier',
        'alamatSupplier', 'tlpSupplier', 'npwpSupplier',
        'emailSupplier', 'keteranganSupplier'
    ];

    function jenis()
    {
        return $this->belongsTo(JenisSupplier::class, 'jenisSupplier', 'id');
    }
}
