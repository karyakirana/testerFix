<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Account;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.accounting.account');
    }

    public function listData()
    {
        $data = Account::all();
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategoriSubId'=>'required',
            'kodeAccount'=>'required|unique:accounting_account',
            'namaAkun'=>'required'
        ]);

        $data = [
            'kategori_sub_id'=>$request->kategoriSubId,
            'kode_account'=>$request->kodeAccount,
            'account_name'=>$request->namaAkun,
            'keterangan'=>$request->keterangan
        ];

        $store = Account::updateOrInsert([$request->id], $data);
        return response()->json(['status'=>true, 'action'=>$store]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Account::find($id);
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
        $destroy=Account::destroy($id);
        return response()->json(['status'=>true]);
    }
}
