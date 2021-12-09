<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountKategoriTipe extends Model
{
    use HasFactory;
    protected $table = 'account_kategori_tipe';
    protected $fillable = [
        'prefix_kategori',
        'kategori',
        'keterangan'
    ];
}
