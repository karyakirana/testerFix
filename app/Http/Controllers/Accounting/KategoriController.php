<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountKategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.accounting.kategori');
    }

    public function dataList()
    {
        $data = AccountKategori::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('Action', function ($row){
                $soft = '<button type="button" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></button>';
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
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
            ''
        ]);

        $data = [
            'kode_kategori'=>$request->kode,
            'deskripsi'=>$request->namaKategori,
            'keterangan'=>$request->keterangan
        ];

        $save = AccountKategori::updateOrInsert(['id'=>$request->id],$data);
        return response()->json(['status'=>true, 'keterangan'=>$save]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = AccountKategori::find($id);
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = AccountKategori::destroy($id);
        return response()->json(['status'=>true, 'keterangan'=>$delete]);
    }

    public function select2(Request $request)
    {
        $data = AccountKategori::where('deskripsi', 'like', '%'.$request->q.'%')->get();
        return response()->json($data);
    }
}
