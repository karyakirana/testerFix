<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountKategori;
use App\Models\Accounting\AccountKategoriSub;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KategoriSubController extends AccountingController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.accounting.kategoriSub');
    }

    public function dataList()
    {
        $data = AccountKategoriSub::with('kategori')->get();
        return $this->listDatatables($data);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode'=>'required|unique:accounting_kategori_sub,kode_kategori_sub',
            'kategori'=> 'required',
            'namaSubKategori'=>'required'
        ]);

        $data = [
            'kode_kategori_sub'=>$request->kode,
            'deskripsi'=>$request->namaSubKategori,
            'keterangan'=>$request->keterangan,
            'kategori_id'=>$request->kategori
        ];

        $store = AccountKategoriSub::updateOrCreate(['id'=>$request->id], $data);
        return response()->json(['status'=>true, 'keterangan'=>$store]);
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
        $data = AccountKategoriSub::find($id);
        return response()->json($data);
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
        $delete = AccountKategoriSub::destroy($id);
        return response()->json(['status'=>true, 'keterangan'=>$delete]);
    }
}
