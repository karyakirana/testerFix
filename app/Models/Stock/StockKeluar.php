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
        'kode', 'active_cash', 'tgl_keluar', 'branch', 'jenis_keluar', 'supplier', 'customer', 'penjualan', 'users'
    ];
    protected $casts = [
        'tgl_keluar'=> 'date:d-M-Y',
    ];

    public function branchs()
    {
        return $this->belongsTo(BranchStock::class, 'branch');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier');
    }

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer', 'id_cust');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan', 'id_jual');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users');
    }
}
