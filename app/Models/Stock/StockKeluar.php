<?php

namespace App\Models\Stock;

use App\Models\Master\Customer;
use App\Models\Master\Supplier;
use App\Models\Sales\Penjualan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockKeluar extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'stock_keluar';
    protected $fillable = [
        'kode', 'tgl_keluar', 'branch', 'jenis_keluar', 'supplier', 'customer', 'penjualan', 'users'
    ];
    protected $casts = [
        'tgl_keluar'=> 'date:d-m-Y',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer', 'id_cust');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan', 'id_jual');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'users');
    }
}
