<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasTempDetil extends Model
{
    use HasFactory;

    protected $table = 'kas_trans_detil';
    protected $fillable = [
        'kas_temp',
        'account_id',
        'debet',
        'kredit',
        'keterangan'
    ];
}
