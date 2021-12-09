<?php

namespace App\Models\Kasir;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanCashDetail extends Model
{
    use HasFactory;
    protected $table = 'penerimaan_cash_detil';
    protected $fillable = [
        'penerimaan_nota_cash_id',
        'penjualan_id',
        'total_bayar'
    ];
}
