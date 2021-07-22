<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'penjualan';
    protected $keyType = 'string';
    protected $primaryKey = 'id_jual';
    protected $fillable = [
        'id_jual', 'activeCash', 'id_cust', 'idBranch', 'id_user', 'tgl_nota', 'tgl_tempo',
        'status_bayar', 'sudahBayar', 'total_jumlah', 'ppn', 'biaya_lain', 'total_bayar',
        'keterangan', 'print'
    ];

    public function customer()
    {
        //
    }
}
