<?php

namespace App\Models\Stock;

use App\Models\Master\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMasukRusak extends Model
{
    use HasFactory;

    protected $table = 'stock_masuk_rusak';
    protected $fillable = ['kode', 'retur_id', 'customer_id', 'supplier_id', 'user_id', 'tgl_masuk_rusak', 'keterangan'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_cust');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
