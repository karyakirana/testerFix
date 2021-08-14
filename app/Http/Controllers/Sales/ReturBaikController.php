<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturBaikController extends Controller
{
    public function index()
    {
        return view('pages.sales.returBaik');
    }

    public function kode()
    {
        //
    }

    public function create()
    {
        return view('pages.sales.returBaikTrans');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
