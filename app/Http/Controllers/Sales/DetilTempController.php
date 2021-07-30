<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\PenjualanDetilTemp;
use Illuminate\Http\Request;

class DetilTempController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'harga' => 'required|integer',
            'diskon' => 'required|numeric',
            'jumlah'=> 'required|integer',
        ]);

        $harga = (int) $request->harga;
        $jumlah = (int) $request->jumlah;
        $diskon = (float) $request->diskon;

        $data = [
            'idPenjualanTemp'=>$request->idTemp,
            'idBarang'=>$request->idProduk,
            'jumlah'=>$jumlah,
            'harga'=>$harga,
            'diskon'=>$diskon,
            'sub_total' =>(int) $jumlah * ($harga-($harga*$diskon/100))
        ];

        $action = PenjualanDetilTemp::updateOrCreate(['id'=>$request->idTransDetil], $data);
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    public function edit($id)
    {
        $detil = PenjualanDetilTemp::with('produk')->findOrFail($id);
        $temp = (object) [
            'id'=> $detil->id,
            'idBarang' => $detil->idBarang,
            'nama_produk'=>$detil->produk->nama_produk,
            'kode_lokal'=>$detil->produk->kode_lokal,
            'nama_kat'=>$detil->produk->kategoriHarga->nama_kat,
            'cover'=>$detil->produk->cover,
            'jumlah'=> $detil->jumlah,
            'harga'=>$detil->harga,
            'diskon'=>$detil->diskon,
            'sub_total'=>$detil->sub_total
        ];
        return response()->json($temp);
    }

    public function destroy($id)
    {
        $action = PenjualanDetilTemp::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
