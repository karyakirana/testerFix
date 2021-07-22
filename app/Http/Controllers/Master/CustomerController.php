<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('pages.master.customer');
    }

    public function idCustomer()
    {
        $idCustomer = Customer::latest('id_cust')->first();
        $num = null;
        if(!$idCustomer)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idCustomer->id_cust, 1, 5);
            $num = $urutan + 1;
        }
        $id = "C".sprintf("%05s", $num);
        return $id;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'=>'required'
        ]);

        $data = [
            'nama_cust'=>$request->nama,
            'diskon'=>$request->diskon,
            'telp_cust'=>$request->telepon,
            'addr_cust'=>$request->alamat,
            'keterangan'=>$request->keterangan
        ];

        $action = Customer::updateOrCreate(['id_cust'=>$request->id ?? $this->idCustomer()], $data);
        return response()->json(['status'=>true, 'action'=>$action]);
    }

    public function edit($id)
    {
        $data = Customer::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        $action = Customer::destroy($id);
        return response()->json(['status'=>true, 'action'=>$action]);
    }
}
