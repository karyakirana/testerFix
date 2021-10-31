<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenjualanBiayaController extends Controller
{
    public function index()
    {
        //
    }

    public function create($idPenjualan)
    {
        return view('pages.sales.PenjualanBiaya', ['id_jual'=>$idPenjualan]);
    }

    public function store(Request $request)
    {
        //
    }

    public function edit($idPenjualan)
    {
        //
    }

    public function storeLine(Request $request)
    {
        //
    }

    public function editLine($id)
    {
        //
    }

    public function destroyLine($id)
    {
        //
    }
}
