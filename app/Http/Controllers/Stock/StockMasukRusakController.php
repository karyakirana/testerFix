<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\StockMasukRusak;
use App\Models\Stock\StockTemp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StockMasukRusakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.stock.stockMasukRusak');
    }

    public function listData()
    {
        $data = StockMasukRusak::with(['customer', 'supplier'])->get();
        return DataTables::of($data)
            ->addColumn('Action', function($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                return $edit.$soft;
            })
            ->rawColumn(['Action'])
            ->make(true);
    }

    private function createSessionStock($idStockMasuk = null, $jenisTemp)
    {
        // insert stock_temp
        return StockTemp::create([
            'jenisTemp'=>$jenisTemp,
            'idUser'=>Auth::id(),
            'stockMasuk'=>$idStockMasuk
        ]);
    }

    private function checkLastCart()
    {
        // check session stock
        if (session('stockMasukRusak'))
        {
            // jika ada langsung ambil data stock
            $stock = StockTemp::find(session('stockMasukRusak'));
        } else {
            // check last temp
            $lastTemp = StockTemp::where('idUser', Auth::id())->where('jenisTemp', 'StockMasukRusak')->whereNull('stockMasuk');
            if ($lastTemp->count() > 0)
            {
                // jika ada
                $stock = $lastTemp->latest()->first();
            } else {
                $stock = $this->createSessionStock(null, 'StockMasukRusak');
            }
            session()->put(['stockMasukRusak'=>$stock->id]);
        }
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser
        ];
        return $data;
    }

    public function create()
    {
        return view('pages.stock.stockMasukRusak', $this->checkLastCart());
    }

    public function kode()
    {
        $data = StockMasukRusak::where('activeCash', session('ClosedCash'))->latest('kode')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SM/".date('Y');
        return $id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = null;
        $idTemp = null;
        DB::beginTransaction();
        try {
            // add stock masuk rusak
            // update or add inventory real rusak
            // delete stock temp
            DB::commit();
            return response()->json(['status'=>true]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
        }
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
