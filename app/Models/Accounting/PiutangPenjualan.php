<?php

namespace App\Models\Accounting;

use App\Models\Sales\Penjualan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangPenjualan extends Model
{
    use HasFactory;
    protected $table = 'piutang_penjualan';
    protected $fillable = [
        'penjualan_id',
        'status_bayar',
        'nominal',
        'user_id',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id_jual');
    }
}
