<?php

namespace App\Models\Sales;

use App\Models\Master\Customer;
use App\Models\Stock\BranchStock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturBaik extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'return_bersih';
    protected $primaryKey = 'id_return';

    protected $fillable = [
        'id_return',
        'id_branch',
        'id_user',
        'id_cust',
        'tgl_nota',
        'total_jumlah',
        'ppn',
        'biaya_lain',
        'total_bayar',
        'keterangan',
        'activeCash'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_cust', 'id_cust');
    }

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'id_branch');
    }
}
