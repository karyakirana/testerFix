<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'accounting_account';
    protected $fillable = [
        'kode_account',
        'account_name',
        'accounting_tipe_id',
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

    public static function getJoinSubKategori($idKategori = null, $deskripsiKategori = null)
    {
        //
    }

    public function tipe()
    {
        return $this->belongsTo(TipeAccount::class, 'accounting_tipe_id', 'id');
    }
}
