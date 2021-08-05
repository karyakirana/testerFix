<?php

namespace App\Models\Stock;

use App\Models\Master\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMasuk extends Model
{
    use HasFactory;
    protected $table = 'stockmasuk';
    protected $fillable = [
        'activeCash', 'kode', 'idBranch', 'idSupplier', 'idUser', 'tglMasuk',
        'nomotPo', 'keterangan'
    ];
    protected $casts = [
        'tglMasuk'=>'date:d-m-Y'
    ];

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'idBranch');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'idSupplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}
