<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounting_account';
    protected $fillable = [
        'kode_account',
        'account_name',
        'keterangan',
        'kategori_sub_id'
    ];

    public function accountKategori()
    {
        return $this->belongsTo(AccountKategoriSub::class, 'kategori_sub_id');
    }

    public function accountSub()
    {
        return $this->hasMany(AccountSub::class, 'account_id');
    }
}
