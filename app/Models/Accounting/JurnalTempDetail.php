<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalTempDetail extends Model
{
    use HasFactory;
    protected $table = 'jurnal_temp_detil';
    protected $fillable = [
        'journal_id',
        'account_id',
        'debit',
        'kredit'
    ];
}
