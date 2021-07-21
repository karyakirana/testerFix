<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\KategoriHarga;
use Illuminate\Http\Request;

class KategoriHargaController extends Controller
{
    public function index()
    {
        return view('pages.master.kategoriHarga');
    }

    protected function idKatHarga()
    {
        $idKategori = KategoriHarga::orderBy('id_kat_harga', 'desc')->first();
        $num = null;
        if(!$idKategori)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idKategori->id_kat_harga, 1, 5);
            $num = $urutan + 1;
        }
        $id = "H".sprintf("%05s", $num);
        return $id;
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis'=>'required'
        ]);

        $data = [
            'nama_kat'=>$request->kategori,
            'keterangan'=>$request->keterangan
        ];

        $action = KategoriHarga::updateOrCreate(['id_kat_harga'=>$request->id ?? $this->idKatharga()], $data);
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    public function edit($id)
    {
        $data = KategoriHarga::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $action = KategoriHarga::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
