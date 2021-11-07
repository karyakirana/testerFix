<?php

namespace App\Models\Sales;

use App\Models\Accounting\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBiaya extends Model
{
    use HasFactory;
    protected $table = 'penjualan_biaya';

    protected $fillable = [
        'penjualan_id',
        'account_id',
        'nominal',
        'keterangan'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
