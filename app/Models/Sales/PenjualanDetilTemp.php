<?php

namespace App\Models\Sales;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetilTemp extends Model
{
    use HasFactory;
    protected $table = 'detil_penjualan_temp';
    protected $fillable = [
        'idPenjualanTemp', 'idBarang', 'jumlah', 'harga', 'diskon', 'sub_total',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'idBarang', 'id_produk');
    }
}
