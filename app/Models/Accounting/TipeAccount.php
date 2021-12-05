<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeAccount extends Model
{
    use HasFactory;
    protected $table = 'account_tipe';
    protected $fillable = [
        'tipe',
        'keterangan'
    ];
}
