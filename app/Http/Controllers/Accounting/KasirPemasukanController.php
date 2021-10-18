<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounting\KasTempDetilRepository;
use App\Http\Repositories\Accounting\KasTempRepository;
use App\Http\Repositories\Accounting\KasTransRepository;
use App\Models\Accounting\KasTrans;
use App\Models\Accounting\KasTransDetil;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KasirPemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.accounting.kasirPemasukan');
    }

    public function listData()
    {
        $data = KasTransRepository::getDataTable('pemasukan');
        return DataTables::of($data)
            ->addColumn('Action', function ($row){
                $soft = '<button type="button" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></button>';
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                return $edit.$soft;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function listDataDetil($kas_id)
    {
        $data = KasTransDetil::where('kas_id', $kas_id)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
//        $kasTempRepo = new KasTempRepository();
//        if (session('pemasukan'))
//        {
//            $idTemp = session('pemasukan');
//        } else {
//            $idTemp = $kasTempRepo->createSession('pemasukan');
//        }
        return view('pages.accounting.kasirPemasukanTrans');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataKas = (object)[
            ''
        ];
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
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $kasTempRepo = new KasTempRepository();
        $dataTemp = $kasTempRepo->editKasTemp($id, 'pemasukan');
        $deleteDetail = KasTempDetilRepository::destroy($dataTemp->id);
        return view('pages.accounting.kasirPemasukanTrans');
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
        //
    }
}
