<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.master.produk');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    protected function idProduk()
    {
        $idProduk = Produk::orderBy('id_produk', 'desc')->first();
        $num = null;
        if(!$idProduk)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idProduk->id_produk, 1, 5);
            $num = $urutan + 1;
        }
        $id = "P".sprintf("%05s", $num);
        return $id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori'=>'required',
            'kategoriHarga'=>'required',
            'nama'=>'required',
            'harga'=>'required',
        ]);

        $idProduk = $request->id ?? $this->idProduk();

        $data = [
            'nama_produk'=>$request->nama,
            'id_kategori'=>$request->kategori,
            'id_kat_harga'=>$request->kategoriHarga,
            'kode_lokal'=>$request->kodeLokal,
            'harga'=>$request->harga,
            'penerbit'=>$request->penerbit,
            'hal'=>$request->hal,
            'cover'=>$request->cover,
            'size'=>$request->size,
            'deskripsi'=>$request->keterangan,
        ];

        if ($idProduk == null){
            $action = Produk::create($data);
        } else {
            $action = Produk::where('id_produk', $idProduk)->update($data);
        }

        $action = Produk::updateOrCreate(['id_produk'=>$idProduk], $data); // insert
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Produk::where('id_produk', $id)->first();
        $produk = [
            'id_produk'=>$data->id_produk,
            'nama_produk'=>$data->nama_produk,
            'kode_lokal'=>$data->kode_lokal,
            'penerbit'=>$data->penerbit,
            'hal'=>$data->hal,
            'cover'=>$data->cover,
            'id_kategori'=>$data->id_kategori,
            'id_lokal'=>$data->kategori->id_lokal ?? '',
            'nama_kategori'=>$data->kategori->nama ?? '',
            'id_kat_harga'=>$data->id_kat_harga,
            'nama_kat'=>$data->kategoriHarga->nama_kat ?? '',
            'harga'=>$data->harga,
            'size'=>$data->size,
            'deskripsi'=>$data->deskripsi,
        ];
        return response()->json($produk);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = Produk::where('id_produk', $id)->destroy();
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
