<?php

namespace App\Models\Accounting;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTrans extends Model
{
    use HasFactory;

    protected $table = 'kas_trans';
    protected $fillable = [
        'kode',
        'activeCash',
        'tgl_buat',
        'jenis',
        'account_id',
        'user_id',
        'debet',
        'kredit',
        'keterangan'
    ];

    public function kasTransDetail()
    {
        return $this->hasMany(KasTransDetil::class, 'kas_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
