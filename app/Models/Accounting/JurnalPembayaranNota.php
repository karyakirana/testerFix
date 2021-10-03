<?php

namespace App\Models\Accounting;

use App\Models\Master\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPembayaranNota extends Model
{
    use HasFactory;

    protected $table = 'jurnal_pembayaran_master';
    protected $fillable = [
        'kode_pembayaran',
        'user_id',
        'jenis_pembayaran',
        'tgl_pembayaran',
        'customer_id'.
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_cust');
    }
}
