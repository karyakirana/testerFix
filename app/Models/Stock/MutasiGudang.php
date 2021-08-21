<?php

namespace App\Models\Stock;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MutasiGudang extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'mutasi_gudang';
    protected $fillable = [
        'activeCash',
        'kode',
        'branchAsal',
        'branchTujuan',
        'tgl_mutasi',
        'id_user',
        'keterangan'
    ];

    public function idBranchAsal()
    {
        return $this->belongsTo(BranchStock::class, 'branchAsal');
    }

    public function idBranchTujuan()
    {
        return $this->belongsTo(BranchStock::class, 'branchTujuan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
