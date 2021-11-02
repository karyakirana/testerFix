<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Accounting\JournalTempRepository;
use Illuminate\Http\Request;

class PenjualanBiayaController extends Controller
{
    public function index()
    {
        return view('pages.sales.penjualanBiayaList');
    }

    public function penjualanList()
    {
        //
    }

    public function create($idPenjualan)
    {
        $sessionTemp = (new JournalTempRepository())->createNewSession('jurnal biaya penjualan');
        return view('pages.sales.PenjualanBiaya', [
            'id_jual'=>$idPenjualan,
            'idTemporary'=>$sessionTemp->id,
        ]);
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
