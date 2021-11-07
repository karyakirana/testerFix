<?php

namespace App\Models\Sales;

use App\Models\Master\Customer;
use App\Models\Stock\BranchStock;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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

    protected $casts = [
        'tgl_nota'=> 'date:d-m-Y',
        'tgl_tempo'=> 'date:d-m-Y',
    ];

    public function scopeWithForeignMe($query, $search = null)
    {
        return $query->with(['pengguna'
        ]);
    }

    protected function getTotalBayarAttribute($value)
    {
        return number_format($value, '0', ',', '.');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_cust', 'id_cust');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'idBranch');
    }

    public function detilPenjualan()
    {
        return $this->hasMany(PenjualanDetil::class, 'id_jual', 'id_jual');
    }
}
