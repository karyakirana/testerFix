<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalTempMaster extends Model
{
    use HasFactory;

    protected $table = 'jurnal_temp_master';
    protected $fillable =[
        'jenis_jurnal',
        'jurnal_id',
        'user_id'
    ];
}
