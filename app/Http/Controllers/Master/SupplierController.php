<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('pages.master.supplier');
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        $data = [
            'id'=>$supplier->id,
            'kodeSupplier'=>$supplier->kodeSupplier,
            'jenisSupplier'=>$supplier->jenisSupplier,
            'namaSupplier'=>$supplier->namaSupplier,
            'alamatSupplier'=>$supplier->alamatSupplier,
            'tlpSupplier'=>$supplier->tlpSupplier,
            'npwpSupplier'=>$supplier->npwpSupplier,
            'emailSupplier'=>$supplier->emailSupplier,
            'keteranganSupplier'=>$supplier->keteranganSupplier,
            'jenis'=>$supplier->jenis->jenis ?? ''
        ];
        return response()->json($data);
    }

    protected function kodeSupplier()
    {
        $idSupplier = Supplier::latest('kodeSupplier')->first();
        $num = null;
        if(!$idSupplier)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idSupplier->kodeSupplier, 1, 5);
            $num = $urutan + 1;
        }
        $id = "S".sprintf("%05s", $num);
        return $id;
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenisSupplier'=>'required',
            'nama' =>'required'
        ]);

        $data = [
            'jenisSupplier'=>$request->jenisSupplier,
            'namaSupplier'=>$request->nama,
            'alamatSupplier'=>$request->alamat,
            'tlpSupplier'=>$request->telepon,
            'npwpSupplier'=>$request->npwp,
            'emailSupplier'=>$request->email,
            'keteranganSupplier'=>$request->keterangan
        ];

        $where = [
            'id'=>$request->id,
            'kodeSupplier'=>$request->idSupplier ?? $this->kodeSupplier(),
        ];

        $action = Supplier::updateOrCreate($where, $data);
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    public function destroy($id)
    {
        $action = Supplier::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
