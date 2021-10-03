<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountKategori extends Model
{
    use HasFactory;

    protected $table = 'accounting_kategori';
    protected $fillable = [
        'kode_kategori',
        'deskripsi',
        'keterangan'
    ];

    public function kategoriSub()
    {
        return $this->hasMany(AccountKategoriSub::class, 'kategori_id');
    }
}
