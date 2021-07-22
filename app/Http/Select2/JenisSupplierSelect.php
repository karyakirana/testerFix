<?php

namespace App\Http\Select2;

use App\Models\Master\JenisSupplier;
use Illuminate\Http\Request;

class JenisSupplierSelect {

    public function jenisSupplier(Request $request)
    {
        $data = JenisSupplier::where('jenis', 'like', '%'.$request->q.'%')->get();
        return response()->json($data);
    }
}
