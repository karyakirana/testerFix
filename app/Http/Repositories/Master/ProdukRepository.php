<?php

namespace App\Http\Repositories\Master;

use App\Models\Master\Produk;

class ProdukRepository
{
    public function getProdukSearch($search)
    {
        return Produk::where('id_produk', 'like','%'.$search.'%')
            ->orWhere('kode_lokal', 'like','%'.$search.'%')
            ->orWhere('nama_produk', 'like','%'.$search.'%')
            ->latest('id_produk')
            ->paginate(10, ['*'], 'produkpage');
    }
}
