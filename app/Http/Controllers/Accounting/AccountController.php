<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function index()
    {
        return view('pages.accounting.account');
    }

    public function listData()
    {
        $data = Account::with(['accountKategori', 'accountKategori.kategori'])->get();
        return DataTables::of($data)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!$request->id) {
            $request->validate([
                'subKategori'=>'required',
                'kode_account'=>'required|unique:accounting_account',
                'namaAkun'=>'required'
            ]);
        }

        $request->validate([
            'subKategori'=>'required',
            'namaAkun'=>'required'
        ]);

        $data = [
            'kategori_sub_id'=>$request->subKategori,
            'kode_account'=>$request->kode_account,
            'account_name'=>$request->namaAkun,
            'keterangan'=>$request->keterangan
        ];

        $store = Account::updateOrCreate(['id'=>$request->id], $data);
        return response()->json(['status'=>true, 'action'=>$store]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $data = Account::with(['accountKategori', 'accountKategori.kategori'])
            ->where('id', $id)->first();
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $destroy=Account::destroy($id);
        return response()->json(['status'=>true]);
    }
}
