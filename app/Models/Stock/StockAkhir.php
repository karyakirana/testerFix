<?php

namespace App\Models\Stock;

use App\Models\Master\Produk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAkhir extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'stockakhir';
    protected $fillable = [
        'activeCash', 'branchId', 'id_produk', 'jumlah_stock'
    ];

    public function branch()
    {
        return $this->belongsTo(BranchStock::class, 'branchId');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
