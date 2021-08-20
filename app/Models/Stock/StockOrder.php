<?php

namespace App\Models\Stock;

use App\Models\Master\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_preorder';
    protected $fillable = [
        'kode',
        'supplier',
        'tgl_order',
        'pembuat',
        'keterangan',
        'activeCash'
    ];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'pembuat');
    }
}
