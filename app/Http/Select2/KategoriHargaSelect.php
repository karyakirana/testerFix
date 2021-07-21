<?php

namespace App\Http\Select2;

use Illuminate\Http\Request;
use App\Models\Master\KategoriHarga;

class KategoriHargaSelect {

    public function kategoriHarga(Request $request)
    {
        $data = KategoriHarga::where('nama_kat', 'like', '%'.$request->q.'%')->get();
        return response()->json($data);
    }
}
