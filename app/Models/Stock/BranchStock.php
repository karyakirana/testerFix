<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchStock extends Model
{
    use HasFactory;

    protected $table = 'branch_stock';

    protected $fillable = [
        'branchName', 'alamat', 'kota', 'keterangan'
    ];
}
