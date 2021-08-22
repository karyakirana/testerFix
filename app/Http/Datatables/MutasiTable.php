<?php

namespace App\Http\Datatables;

use App\Models\Stock\MutasiGudang;
use Yajra\DataTables\DataTables;

class MutasiTable {

    public function mutasiList()
    {
        $data = MutasiGudang::where('activeCash', session('ClosedCash'))
            ->with(['idBranchAsal', 'idBranchTujuan', 'user'])
            ->latest('id')->get();
        return DataTables::of($data)
            ->addColumn('branchAsal', function($row){
                return $row->idBranchAsal->branchName;
            })
            ->addColumn('branchTujuan', function($row){
                return $row->idBranchTujuan->branchName;
            })
            ->addColumn('user', function($row){
                return $row->user->username;
            })
            ->make(true);
    }

}
