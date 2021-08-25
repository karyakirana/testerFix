<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Stock\InventoryReal;
use App\Models\Stock\StockAkhir;
use App\Models\Stock\StockAkhirDetil;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockTemp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAkhirController extends Controller
{
    public function index()
    {
        return view('pages.stock.stockAkhirList');
    }

    public function kode()
    {
        $data = StockAkhir::where('activeCash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SA/".date('Y');
        return $id;
    }

    private function createSessionStock($idStockAkhir = null)
    {
        // insert stock_temp
        return StockTemp::create([
            'jenisTemp'=>'StockAkhir',
            'idUser'=>Auth::id(),
            'stockMasuk'=>$idStockAkhir
        ]);
    }

    private function checkLastCart()
    {
        // check session stock
        if (session('stockAkhir'))
        {
            // jika ada langsung ambil data stock
            $stock = StockTemp::find(session('stockAkhir'));
        } else {
            // check last temp
            $lastTemp = StockTemp::where('idUser', Auth::id())->where('jenisTemp', 'StockAkhir')->whereNull('stockMasuk');
            if ($lastTemp->count() > 0)
            {
                // jika ada
                $stock = $lastTemp->latest()->first();

                session()->put(['stockAkhir'=>$stock->id]);

            } else {
                $stock = $this->createSessionStock();
                session()->put(['stockAkhir'=>$stock->id]);
            }
        }
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser
        ];
        return $data;
    }

    public function create()
    {
        return view('pages.stock.stockAkhirTrans', $this->checkLastCart());
    }

    public function store(Request $request)
    {
        // simpan
        $idTemp = $request->idTemp;
        $kode = $this->kode();
        $tglAkhir = date('Y-m-d', strtotime($request->tgl_akhir));

        DB::beginTransaction();
        try {
            // insert to stock_keluar
            $stockKeluar = StockAkhir::create([
                'activeCash'=>session('ClosedCash'),
                'tglInput'=>$tglAkhir,
                'kode'=>$kode,
                'branchId'=>$request->branch,
                'pencatat'=>$request->pencatat,
                'idPembuat'=>Auth::id(),
                'keterangan'=>$request->keterangan,
            ]);
            // insert to stock_keluar_detil from stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp);
            if ($stock_detil_temp->get()->count() > 0){
                foreach ($stock_detil_temp->get() as $row){
                    StockAkhirDetil::create([
                        'id_stock_akhir'=>$stockKeluar->id,
                        'id_produk'=>$row->idProduk,
                        'jumlah_stock'=>$row->jumlah
                    ]);

                    // update or create inventory_real
                    $inventory_real = InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch)->get();
                    if ($inventory_real->count() > 0){
                        // update
                        InventoryReal::where('idProduk', $row->idProduk)
                            ->where('branchId', $request->branch)
                            ->update([
                                'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                                'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                            ]);
                    } else {
                        InventoryReal::create([
                            'idProduk'=>$row->idProduk,
                            'branchId'=>$request->branch,
                            'stockIn'=>$row->jumlah,
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                    }
                }
            }
            // destroy stock_temp
            StockTemp::destroy($idTemp);
            // destroy stock_detil_temp
            $stock_detil_temp->delete();
            // destroy session stock
            DB::commit();
            session()->forget('stockKeluar');
            $jsonData = [
                'status'=>true
            ];
        } catch (\ModelNotFoundException $e) {
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e->getMessage(),
            ];
        }
        return response()->json($jsonData);
    }

    public function show()
    {
        // show detil
        return view('pages.stock.stockAkhirListDetil');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function checkSessionEdit($id)
    {
        //check temp
        $checkTemp = StockTemp::where('stockMasuk', $id)->where('jenisTemp', 'StockAkhir')->where('idUser', Auth::id());
        if ($checkTemp->get()->count() > 0){
            // jika temp sebelumnya ada, dipakai saja
            $temp = $checkTemp->latest()->first();
            // delete detil stock lama
            StockDetilTemp::where('stockTemp', $temp->id)->delete();
        } else {
            // jika temp tidak ada, buat baru
            $temp = $this->createSessionStock($id);
        }
        // insert detil
        $stock_akhir_detil = StockAkhirDetil::where('id_stock_akhir', $id)->get();
        if ($stock_akhir_detil->count() > 0)
        {
            foreach ($stock_akhir_detil as $row)
            {
                StockDetilTemp::create([
                    'stockTemp'=>$temp->id,
                    'idProduk'=>$row->id_produk,
                    'jumlah'=>$row->jumlah_stock
                ]);
            }
        }
        return $temp;
    }

    public function edit($id)
    {
        $stock_akhir = StockAkhir::with(['user', 'branchs'])->find($id);
        $stock = $this->checkSessionEdit($id);
        $data = [
            'idTemp'=>$stock->id,
            'idUser'=>$stock->idUser,
            'id'=>$id,
            'kode'=>$stock_akhir->kode,
            'pembuat'=>$stock_akhir->pembuat,
            'branch'=>$stock_akhir->branchId,
            'tgl_keluar'=>$stock_akhir->tgl_keluar->format('d-M-Y'),
            'update'=>true,
            'keterangan'=>$stock_akhir->keterangan
        ];
        return view('pages.stock.stockAkhirTrans', $data);
    }

    public function update(Request $request)
    {
        // update
        $idTemp = $request->idTemp;
        $id_stock_akhir = $request->id;
        $tglAkhir = date('Y-m-d', strtotime($request->tgl_akhir));
        $data_lama = StockAkhir::find($id_stock_akhir);

        DB::beginTransaction();
        try {

            // delete stock_keluar_detil
            $delete_detil = StockAkhirDetil::where('id_stock_akhir', $id_stock_akhir);
            foreach ($delete_detil->get() as $row){
                // update inventory_real
                InventoryReal::where('idProduk', $row->id_produk)
                    ->where('branchId', $data_lama->branchId)
                    ->update([
                        'stockIn'=>DB::raw('stockIn -'.$row->jumlah_stock),
                        'stockNow'=>DB::raw('stockNow -'.$row->jumlah_stock),
                    ]);
            }
            $delete_detil->delete();
            // update stock_keluar
            $update = StockAkhir::where('id', $id_stock_akhir)
                ->update([
                    'active_cash'=>session('ClosedCash'),
                    'tglInput'=>$tglAkhir,
                    'branchId'=>$request->branch,
                    'pencatat'=>$request->pencatat,
                    'idPembuat'=>Auth::id(),
                    'keterangan'=>$request->keterangan,
                ]);
            // insert stock_keluar_detil by stock_detil_temp
            $stock_detil_temp = StockDetilTemp::where('stockTemp', $idTemp)->get();
            if ($stock_detil_temp->count() > 0){
                foreach ($stock_detil_temp as $row){
                    StockAkhirDetil::create([
                        'id_stock_akhir'=>$id_stock_akhir,
                        'id_produk'=>$row->idProduk,
                        'jumlah_stock'=>$row->jumlah
                    ]);
                    // update inventory_real
                    $update_inventory = InventoryReal::where('idProduk', $row->idProduk)
                        ->where('branchId', $request->branch);
                    if($update_inventory->get()->count() > 0){
                        $update_inventory->update([
                            'stockIn'=>DB::raw('stockIn +'.$row->jumlah),
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                    } else {
                        InventoryReal::create([
                            'idProduk'=>$row->idProduk,
                            'branchId'=>$request->branch,
                            'stockIn'=>$row->jumlah,
                            'stockNow'=>DB::raw('stockNow +'.$row->jumlah),
                        ]);
                    }
                }
            }
            // delete stock_temp
            StockTemp::destroy($idTemp);
            // delete stock_detil_temp
            StockDetilTemp::where('stockTemp', $id_stock_akhir)->delete();
            DB::commit();
            $jsonData = [
                'status'=>true
            ];

        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e->getMessage(),
            ];
        }
        return response()->json($jsonData);
    }

    public function destroy($id)
    {
        // delete
    }

    public function stockByBranch($branch)
    {
        $data = ['gudang'=>$branch];
        return view('pages.stock.stockAkhirBranchDetil', $data);
    }
}
