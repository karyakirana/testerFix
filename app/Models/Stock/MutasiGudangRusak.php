<?php

namespace App\Models\Stock;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiGudangRusak extends Model
{
    use HasFactory;
    protected $table = 'mutasi_gudang_rusak';

    protected $fillable = [
        'activeCash',
        'kode',
        'gudang_asal',
        'gudang_tujuan',
        'tgl_mutasi',
        'user_id',
        'keterangan'
    ];
    public function gudangAsal()
    {
        return $this->belongsTo(BranchStock::class, 'gudang_asal', 'id');
    }
    public function gudangTujuan()
    {
        return $this->belongsTo(BranchStock::class, 'gudang_tujuan', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
