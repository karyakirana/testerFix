<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountSub;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AccountSubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.accounting.accountSub');
    }

    public function listData()
    {
        $data = AccountSub::all();
        return DataTables::of($data)
            ->addColumn('Action', function ($row){
                $soft = '<button type="button" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></button>';
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
            })
            ->rawColumns(['Action'])
            ->make(true);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $store = AccountSub::updateOrCreate([
            'kode_account_sub'=>$request->kodeAccount,
            'sub_name'=>$request->subName,
            'keterangan'=>$request->keterangan,
            'account_id'=>$request->accoundId
        ]);
        $return = ['status'=>true, 'action'=>$store];
        return response()->json($return);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $data = AccountSub::find($id);
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
        $destroy = AccountSub::destroy($id);
        $return = ['status'=>true, 'action'=>$destroy];
        return response()->json($return);
    }
}
