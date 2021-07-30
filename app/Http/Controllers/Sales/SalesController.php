<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.sales.penjualan');
    }

    public function idPenjualan()
    {
        $data = Penjualan::where('activeCash', session('ClosedCash'))->latest()->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->id_jual, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/PJ/".date('Y');
        return $id;
    }

    /**
     * membuat session untuk Penjualan Baru
     *
     * @return integer session('penjualan')
     */
    protected function createTemp()
    {
        $create = PenjualanTemp::create([
            'jenisTemp' => 'Penjualan',
            'idSales' => Auth::user()->id,
        ]);
        session(['penjualan'=>$create->id]);

        return $create;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sessionTemp = session('penjualan');
        $data = null;
        if ($sessionTemp) {
            $penjualanTemp = PenjualanTemp::find($sessionTemp);
            $sales = User::find($penjualanTemp)->first();
            $data = [
                'idTemp' => $sessionTemp,
                'idSales' => $penjualanTemp->idSales,
                'namaSales' => $sales->name,
            ];
        } else {
            $temporary = $this->createTemp();
            $sales = User::find($temporary->idSales);
            $data = [
                'idTemp' => $temporary->id,
                'idSales' => $temporary->idSales,
                'namaSales' => $sales->name,
            ];
        }
        return view('pages.sales.penjualanTransaksi', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idPenjualan = $this->idPenjualan();
        $idTemp = $request->idTemp;
        $tglPenjualan = date('Y-m-d', strtotime($request->tglNota));
        $tglTempo = date('Y-m-d', strtotime($request->tglTempo));

        // ambil data dari detil_penjualan_temp
        $detilTemp = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();

        // array untuk datadetil
        $dataDetil = null;
        foreach ($detilTemp as $row) {
            $data = [
                'id_jual' => $idPenjualan,
                'id_produk' => $row->idBarang,
                'jumlah' => $row->jumlah,
                'harga' => $row->harga,
                'diskon' => $row->diskon,
                'sub_total' => $row->sub_total,
            ];
            $dataDetil [] = $data;
            // $hitungData++;
        }

        // Data Penjualan untuk di insert ke Tabel Penjualan
        $data = [
            'activeCash' => session('ClosedCash'),
            'id_jual' => $idPenjualan,
            'tgl_nota' => $tglPenjualan,
            'tgl_tempo' => ($request->jenisBayar == 'Tunai') ? $tglTempo : null,
            'status_bayar' => $request->jenisBayar,
            'sudahBayar'=> "belum", // pembuatan nota belum bayar
            'total_jumlah' => $detilTemp->count(), // jumlah Item
            'ppn' => $request->ppn,
            'biaya_lain' => $request->biayaLain,
            'total_bayar' => $detilTemp->sum('sub_total') + $request->ppn + $request->biayaLain, // total semua subtotal atau $request->hiddenTotalSemuanya
            'id_cust' => $request->idCustomer,
            'id_user' => Auth::user()->id,
            'keterangan' => $request->keterangan
        ];

        // transaction start
        $jsonData = null;
        DB::beginTransaction();
        try {

            $insertDetail = PenjualanDetil::insert($dataDetil);
            $insertMaster = Penjualan::create($data);
            $deleteTempDetail = PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
            $deleteTempMaster = PenjualanTemp::where('id', $idTemp)->delete();
            DB::commit();
            session()->forget('penjualan'); // hapus session temp
            $jsonData = [
                'status' => true,
                'detail' => $insertDetail,
                'master' => $insertMaster,
                'deleteDetail' => $deleteTempDetail,
                'deletemaster' => $deleteTempMaster,
                'nomorPenjualan' => str_replace('/', '-', $idPenjualan),
            ];
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            $jsonData = [
                'status' => false,
                'keterangan' => $e->getMessage(),
            ];
        }

        return response()->json($jsonData);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Penjualan::findOrFail(str_replace('-', '/', $id));
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pages.sales.penjualanTransaksi');
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
