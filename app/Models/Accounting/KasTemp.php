<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTemp extends Model
{
    use HasFactory;
    protected $table = 'kas_trans';
    protected $fillable = [
        'user_id',
        'transaction_id',
        'jenis'
    ];
}
