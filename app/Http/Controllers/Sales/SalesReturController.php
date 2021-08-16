<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\Sales\ReturBaik;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesReturController extends Controller
{
    public function index()
    {
        return view('pages.sales.returBaik');
    }

    // generate id_return
    private function idRetur()
    {
        $data = ReturBaik::where('activeCash', session('ClosedCash'))->latest('id_return')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_jual, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/RB/".date('Y');
        return $id;
    }

    // generate kode stockmasuk
    private function kode()
    {
        $data = StockMasuk::where('active_cash', session('ClosedCash'))->latest()->first();
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SM/".date('Y');
        return $id;
    }

    private function createTemp($id_retur = null)
    {
        $create = PenjualanTemp::create([
            'jenisTemp' => 'Penjualan',
            'idSales' => Auth::user()->id,
            'id_jual'=> $id_retur,
        ]);

        return $create;
    }

    public function create()
    {
        return view('pages.sales.returBaikTrans');
    }

    // store penjualan Retur Baik
    private function storeReturBaik($dataRetur)
    {
        return ReturBaik::create($dataRetur);
    }

    // store Retur Baik detil from detil_penjualan_temp
    // store stock_masuk_detil from detil_penjualan_temp
    // update or create inventory_real
    private function storeFromDetilTemp($idTemp, $idJual, $idStockMasuk, $idBranch)
    {
        $detil_penjualan_temp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();
        foreach ($detil_penjualan_temp as $row)
        {
            PenjualanDetil::create([
                'id_jual'=>$idJual,
                'id_produk'=>$row->idBarang,
                'jumlah'=>$row->jumlah,
                'harga'=>$row->harga,
                'diskon'=>$row->diskon,
                'sub_total'=>$row->sub_total,
            ]);

            StockMasukDetil::create([
                'idStockMasuk'=>$idStockMasuk,
                'idProduk'=>$row->idBarang,
                'jumlah'=>$row->jumlah
            ]);

            $inventory_real = InventoryReal::where('idProduk', $row->idBarang)->where('branchId', $idBranch);
            if ($inventory_real->get()->count() > 0)
            {
                // insert
                InventoryReal::create([
                    'idProduk'=>$row->idBarang,
                    'branchId'=>$idBranch,
                    'stockIn'=>$row->jumlah,
                    'stockNow'=>DB::raw('stockNow +'.$row->jumlah)
                ]);
            } else {
                // update
                $inventory_real->update([
                    'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                    'stockNow'=>DB::raw('stockNow +'.$row->jumlah)
                ]);
            }
        }
    }

    /**
     * store Penjualan Return Baik
     * store nota_retur_baik
     * store Stock Masuk
     * update or Create Stock Real
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            $data = [
                'status'=>true,
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $data = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return response()->json($data);
    }

    public function edit($id)
    {
        return view('pages.sales.returBaikTrans');
    }

    public function update(Request $request)
    {
        return response()->json();
    }

    public function destroy($id)
    {
        //
    }
}
