<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "customer";
    protected $fillable = [
        'id_cust', 'nama_cust', 'diskon',
        'telp_cust', 'addr_cust', 'keterangan'
    ];
}
