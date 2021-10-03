<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountKategoriSub extends Model
{
    use HasFactory;

    protected $table = 'accounting_kategori_sub';
    protected $fillable = [
        'kode_kategori_sub',
        'deskripsi',
        'keterangan',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(AccountKategori::class, 'kategori_id');
    }

    public function account()
    {
        return $this->hasMany(Account::class, 'kategori_sub_id');
    }
}
