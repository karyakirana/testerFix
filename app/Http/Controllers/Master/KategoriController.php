<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\KategoriProduk;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.kategoriProduk');
    }

    protected function idKategori()
    {
        $idKategori = KategoriProduk::orderBy('id_kategori', 'desc')->first();
        $num = null;
        if(!$idKategori)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idKategori->id_kategori, 1, 5);
            $num = $urutan + 1;
        }
        $id = "K".sprintf("%05s", $num);
        return $id;
    }

    public function edit($id)
    {
        $data = KategoriProduk::where('id_kategori', $id)->first();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idLokal' => 'required',
            'jenis'=>'required',
        ]);

        $data = [
            'id_lokal'=>$request->idLokal,
            'nama'=>$request->jenis,
            'keterangan'=>$request->keterangan
        ];

        $action = KategoriProduk::updateOrCreate(['id_kategori'=>$request->id ?? $this->idKategori()], $data);
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    public function destroy($id)
    {
        $action = KategoriProduk::where('id_kategori', $id)->delete();
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
