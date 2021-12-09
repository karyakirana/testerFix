<?php

namespace App\Models\Master;

use App\Models\Stock\StockMasukRusakDetil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'produk';
    protected $fillable = [
        'id_produk', 'id_kategori', 'kode_lokal', 'penerbit',
        'nama_produk', 'stock', 'hal', 'cover', 'id_kat_harga',
        'harga', 'size', 'deskripsi'
    ];

    public function scopeGetAllData()
    {
        $data = DB::table('produk as p')
            ->select(
                'id_produk',
                'nama_produk',
                'k.nama as kategori',
                'kh.nama_kat as kategoriHarga',
                'id_lokal',
                'kode_lokal',
                'harga',
                'hal',
                'cover',
                'size',
                'deskripsi'
            )
            ->leftJoin('kategori as k', 'p.id_kategori','=', 'k.kategori')
            ->leftJoin('kategori_harga as kh', 'p.id_kat_harga', '=', 'kh. id_kat_harga')
            ->orderBy('id_produk', 'desc');
        return $data->get();
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class,'id_kategori', 'id_kategori');
    }

    public function kategoriHarga()
    {
        return $this->belongsTo(KategoriHarga::class, 'id_kat_harga', 'id_kat_harga');
    }

    public function stockRusakMasukDetail()
    {
        return $this->hasMany(StockMasukRusakDetil::class, 'produk_id', 'id_produk');
    }
}
