<?php

namespace App\Models\Accounting;

use App\Models\Sales\Penjualan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPembayaranNotaDetil extends Model
{
    use HasFactory;

    protected $table = 'jurnal_pembayaran_detil';
    protected $fillable = [
        'kode_pembayaran',
        'penjualan_id',
        'jurnal_pembayaran_id',
    ];

    public function penjualan()
    {
        return $this->hasOne(Penjualan::class, 'penjualan_id');
    }

    public function JurnalPembayaran()
    {
        return $this->belongsTo(JurnalPembayaranNota::class, 'jurnal_pembayaran_id');
    }
}
