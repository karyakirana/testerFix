<?php

namespace App\Http\Select2;

use App\Models\Master\KategoriProduk;
use Illuminate\Http\Request;

class KategoriSelect {

    public function kategori(Request $request)
    {
        $data = KategoriProduk::where('nama', 'like', '%'.$request->q.'%')->get();
        return $data;
    }
}
