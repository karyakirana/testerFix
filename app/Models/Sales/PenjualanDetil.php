<?php

namespace App\Models\Sales;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetil extends Model
{
    use HasFactory;

    protected $table='detil_penjualan';
    protected $primaryKey = 'id_detil';
    protected $fillable =[
        'id_jual', 'id_produk', 'jumlah', 'harga', 'diskon', 'subTotal'
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_jual', 'id_jual');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class,'id_produk', 'id_produk');
    }
}
