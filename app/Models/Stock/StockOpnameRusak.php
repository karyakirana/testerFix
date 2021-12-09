<?php

namespace App\Models\Stock;

use App\Models\Master\Pegawai;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameRusak extends Model
{
    use HasFactory;

    protected $table = 'stock_opname_rusak';
    protected $fillable = [
        'activeCash',
        'kode',
        'user',
        'pelapor',
        'branch_id',
        'tgl_input',
        'keterangan',
    ];

    public function users()
    {
        return $this->belongsTo(User::class,'user', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pelapor', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'branch_id', 'id');
    }
}
