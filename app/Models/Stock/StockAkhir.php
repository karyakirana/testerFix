<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAkhir extends Model
{
    use HasFactory;
    protected $table = 'stockakhir_master';

    protected $fillable =[
        'activeCash',
        'kode',
        'branchId',
        'tglInput',
        'pencatat',
        'idPembuat',
        'keterangan'
    ];

    protected $casts = [
        'tglInput'=>'date:d-M-Y'
    ];

    public function stockAkhirDetil()
    {
        return $this->hasMany(StockAkhirDetil::class, 'id_stock_akhir');
    }

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'branchId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idPembuat');
    }
}
