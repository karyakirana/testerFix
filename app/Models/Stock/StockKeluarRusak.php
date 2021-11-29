<?php

namespace App\Models\Stock;

use App\Models\Master\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockKeluarRusak extends Model
{
    use HasFactory;
    protected $table = 'stock_keluar_rusak';
    protected $fillable = [
        'activeCash',
        'kode',
        'supplier_id',
        'user_id',
        'tgl_keluar_rusak',
        'keterangan',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
