<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisSupplier;
use Illuminate\Http\Request;

class JenisSupplierController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.master.jenisSupllier');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $data = JenisSupplier::find($id);
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis'=>'required'
        ]);

        $data = [
            'jenis' => $request->jenis,
            'keterangan'=>$request->keterangan
        ];

        $action = JenisSupplier::updateOrCreate(['id'=>$request->id], $data);
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $action = JenisSupplier::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
