<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';

    protected $fillable = [
        'id',
        'kode',
        'nama',
        'alamat',
        'kota',
        'gender',
        'kotaLahir',
        'tglLahir',
        'ktp',
        'npwp',
        'users'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'users', 'id');
    }
}
