<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Sales\SalesReturRusakDetilRepository;
use App\Http\Repositories\Sales\SalesReturRusakRepository;
use App\Http\Repositories\Stock\InventoryRusakRealRepository;
use App\Http\Repositories\Stock\StockRusakMasukDetilRepository;
use App\Http\Repositories\Stock\StockRusakMasukRepository;
use App\Models\Sales\ReturRusakDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\Sales\ReturRusak;
use App\Models\Stock\InventoryRusak;
use App\Models\Stock\StockDetilTemp;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use App\Models\Stock\StockMasukRusak;
use App\Models\Stock\StockMasukRusakDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SalesBadReturController extends Controller
{
    public function index()
    {
        return view('pages.sales.returRusak');
    }

    public function listData()
    {
        $data = ReturRusak::relation()
            ->where('activeCash', session('ClosedCash'))
            ->get();
        return DataTables::of($data)
            ->addColumn('Action', function ($row){
                $edit = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnEdit" data-value="'.$row->id.'" title="Edit"><i class="la la-edit"></i></a>';
                $show = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnShow" data-value="'.$row->id.'" title="show"><i class="flaticon2-indent-dots"></i></a>';
                $soft = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnSoft" data-value="'.$row->id.'" title="Delete"><i class="la la-trash"></i></a>';
                $print = '<a href="#" class="btn btn-sm btn-clean btn-icon" id="btnPrint" data-value="'.$row->id.'" title="print"><i class="flaticon-technology"></i></a>';
                return $edit.$show.$soft.$print;
            })
            ->rawColumns(['Action'])
            ->make(true);
    }

    public function listDataDetil($id)
    {
        $data = ReturRusakDetil::where('retur_rusak_id', $id)
            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

    public function show($id)
    {
        return $data = ReturRusak::where('id', $id)
            ->first();
    }

    private function createTemp($id_retur = null)
    {
        $create = PenjualanTemp::create([
            'jenisTemp' => 'ReturRusak',
            'idSales' => Auth::id(),
            'id_jual'=> $id_retur,
        ]);
        return $create;
    }

    public function create()
    {
        $sess = session('ReturBaik');
        if ($sess){
            $dataTemp = PenjualanTemp::find($sess);
        } else {
            $checkLastSession = PenjualanTemp::where('idSales', Auth::id())->where('jenisTemp', 'ReturRusak')->latest();
            if ($checkLastSession->get()->count() > 0){
                $dataTemp = $checkLastSession->first();
            } else {
                $dataTemp = $this->createTemp();
            }
            session()->put(['ReturBaik'=>$dataTemp->id]);
        }
        $data = [
            'idTemp' => $dataTemp->id,
            'idSales' => $dataTemp->jenisTemp,
            'namaSales'=> $dataTemp->idSales,
        ];
        return view('pages.sales.returRusakTrans', $data);
    }

    public function idReturRusak()
    {
        $data = ReturRusak::where('activeCash', session('ClosedCash'))->latest('id_rr')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_rr, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/RR/".date('Y');
        return $id;
    }

    public function kodeStockRusak()
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

    public function store(Request $request)
    {
        $idRetur = SalesReturRusakRepository::kode();
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));
        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        $dataRequest = (object)[
            'retur_id'=>$idRetur,
            'branch_id'=>$request->branch,
            'user_id'=>Auth::id(),
            'customer_id'=>$request->idCustomer,
            'tgl_nota'=>$tglRetur,
            'total_jumlah'=>0,
            'ppn'=>$request->nota,
            'biaya_lain'=>$request->biaya_lain,
            'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain,
            'keterangan'=>$request->keterangan,
            // for Stock Masuk Rusak
            'jenis_masuk'=>'Retur Baik'
        ];

        DB::beginTransaction();
        try {
            // insert Retur Rusak
            $returRusakSave = new SalesReturRusakRepository();
            $returRusak = $returRusakSave->create($dataRequest);

            // insert stockMasuk
            $stockMasukRusak = new StockRusakMasukRepository();
            $stockMasuk = $stockMasukRusak->create($dataRequest, $returRusak->id);

            // insert detail
            $detil = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp);
            foreach ($detil->get() as $row){

                $dataDetil = (object)[
                    'retur_rusak_id'=>$returRusak->id,
                    'stock_masuk_rusak_id'=>$stockMasuk->id,
                    'produk_id'=>$row->idBarang,
                    'jumlah'=>$row->jumlah,
                    'harga'=>$row->harga,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total
                ];

                // insert rr_detail
                SalesReturRusakDetilRepository::create($dataDetil);

                // insert stockmasuk detil
                StockRusakMasukDetilRepository::create($dataDetil);

                // insert or update inventory real
                InventoryRusakRealRepository::CreateStockIn($request->branch, $dataDetil);
            }
            $detil->delete();
            PenjualanTemp::destroy($idTemp);
            session()->forget('ReturBaik');
            DB::commit();
            $jsonData = [
                'status'=>true,
                'id'=>$returRusak->id
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return response()->json($jsonData);
    }

    public function edit($id)
    {
        // check temp for edit
        $idRetur = $id;
        $checkTemp = PenjualanTemp::where('id_jual', $idRetur)->first();
        $returRusak = ReturRusak::with('customer')->find($idRetur);
        if ($checkTemp){
            // jika temp edit sebelumnya ada
            // delete detil_temp where id_temp
            PenjualanDetilTemp::where('idPenjualanTemp', $checkTemp->id)->delete();
            $idTemp = $checkTemp->id;
        } else {
            $idTemp = $this->createTemp($idRetur)->id;
        }
        $detil = ReturRusakDetil::where('retur_rusak_id', $idRetur)->get();
        // insert detil to detil_temporary
        if ($detil->count() > 0){
            foreach ($detil as $row)
            {
                // insert
                PenjualanDetilTemp::create([
                    'idPenjualanTemp'=> $idTemp,
                    'idBarang'=>$row->id_produk,
                    'harga'=>$row->harga,
                    'jumlah'=>$row->jumlah,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total,
                ]);
            }
        }
        $data = [
            'idTemp'=>$idTemp,
            'id_jual'=>$returRusak->id,
            'idCustomer'=>$returRusak->id_cust,
            'nama_customer'=>$returRusak->customer->nama_cust,
            'status_bayar'=>$returRusak->status_bayar,
            'tgl_nota'=> date('d-M-Y', strtotime($returRusak->tgl_nota)),
            'tgl_tempo' => (isset($returRusak->tgl_tempo)) ? date('d-M-Y',strtotime($returRusak->tgl_tempo)) : null,
            'ppn' => $returRusak->ppn,
            'biaya_lain'=>$returRusak->biaya_lain,
            'total_bayar'=>$returRusak->total_bayar,
            'keterangan'=>$returRusak->keterangan,
            'branch'=>$returRusak->id_branch,
            'update'=>true
        ];
        return view('pages.sales.returRusakTrans', $data);
    }

    public function update(Request $request)
    {
        $idRetur = $request->id;
        $idTemp = $request->idTemp;
        $tglRetur = date('Y-m-d', strtotime($request->tglNota));

        $stockLama = StockMasukRusak::where('retur_id', $idRetur)->first();
        $dataLama = ReturRusakDetil::where('retur_rusak_id', $idRetur);

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp);

        $dataRequest = (object)[
            'retur_id'=>$idRetur,
            'branch_id'=>$request->branch,
            'user_id'=>Auth::id(),
            'customer_id'=>$request->idCustomer,
            'tgl_nota'=>$tglRetur,
            'total_jumlah'=>0,
            'ppn'=>$request->nota,
            'biaya_lain'=>$request->biaya_lain,
            'total_bayar'=>$detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain,
            'keterangan'=>$request->keterangan,
            // for Stock Masuk Rusak
            'jenis_masuk'=>'Retur Baik'
        ];

       DB::beginTransaction();
        try {
            // recovery stock atau rollback
            foreach ($dataLama->get() as $row){
                // update inventory_real
                InventoryRusakRealRepository::rollbackStockIn($stockLama->branch_id, $row);
            }

            // delete detil
            ReturRusakDetil::where('retur_rusak_id', $idRetur)->delete();
            StockMasukRusakDetil::where('stock_masuk_rusak_id', $stockLama->id)->delete();

            // update Penjualan
            $updatePenjualan = ReturRusak::where('id_rr', $idRetur)->update([
                'id_rr'=>$idRetur,
                'id_branch'=>$request->branch,
                'id_user'=>Auth::id(),
                'id_cust'=>$request->idCustomer,
                'tgl_nota'=>$tglRetur,
                'total_jumlah'=>0,
                'ppn'=>$request->ppn,
                'biaya_lain'=>$request->biaya_lain,
                'total_bayar'=>$request->total_bayar,
                'keterangan'=>$request->keterangan,
            ]);
            $updateStockMasuk = StockRusakMasukRepository::update($stockLama->id , $dataRequest);

            $detil = $detilTemp;
            foreach ($detil->get() as $row){

                $dataDetil = (object)[
                    'retur_rusak_id'=>$idRetur,
                    'stock_masuk_rusak_id'=>$stockLama->id,
                    'produk_id'=>$row->idBarang,
                    'jumlah'=>$row->jumlah,
                    'harga'=>$row->harga,
                    'diskon'=>$row->diskon,
                    'sub_total'=>$row->sub_total
                ];

                // insert rr_detail
                SalesReturRusakDetilRepository::create($dataDetil);

                // insert stockmasuk detil
                StockRusakMasukDetilRepository::create($dataDetil);

                // insert or update inventory real
                InventoryRusakRealRepository::CreateStockIn($request->branch, $dataDetil);
            }
            $detil->delete();
            PenjualanTemp::destroy($idTemp);
            DB::commit();
            $jsonData = [
                'status'=>true,
                'id'=>$idRetur
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $jsonData = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return response()->json($jsonData);
    }

    public function destroy($id)
    {
        //
    }
}
