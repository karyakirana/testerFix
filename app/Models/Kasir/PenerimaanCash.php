<?php

namespace App\Models\Kasir;

use App\Models\Accounting\Account;
use App\Models\Master\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanCash extends Model
{
    use HasFactory;
    protected $table = 'penerimaan_nota_cash';

    protected $fillable = [
        'debet_account_id',
        'kredit_account_id',
        'nominal_penerimaan',
        'activeCash',
        'kode_penerimaan_cash',
        'user_id',
        'customer_id',
        'tgl_penerimaan_cash',
        'keterangan'
    ];

    public function debetAccount()
    {
        return $this->belongsTo(Account::class, 'debet_account_id');
    }

    public function kreditAccount()
    {
        return $this->belongsTo(Account::class, 'kredit_account_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_cust');
    }
}
