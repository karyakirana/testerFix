<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSub extends Model
{
    use HasFactory;

    protected $table = 'account';
    protected $fillable = [
        'kode_account_sub',
        'sub_name',
        'keterangan',
        'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
