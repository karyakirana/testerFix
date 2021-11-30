<?php

namespace App\Http\Repositories\Stock;

use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  StockKeluarRepository
{
    public function getStockKeluarByIdJual($idJual)
    {
        return StockKeluar::where('penjualan', $idJual)->first();
    }

    public function kode()
    {
        $data = StockKeluar::where('active_cash', session('ClosedCash'))->latest('kode')->first();
        $num = null;
        if(!$data){
            $num = 1;
        } else {
            $urutan = (int) substr($data->kode, 0, 4);
            $num = $urutan + 1;
        }
        $id = sprintf("%04s", $num)."/SK/".date('Y');
        return $id;
    }

    public function storeStockKeluarFromPenjualan(array $data, string $kodePenjualan)
    {
        return StockKeluar::create([
            'active_cash'=>$data['activeCash'],
            'tgl_keluar'=>$data['tglNota'] ?? $data['tgl_mutasi'],
            'kode'=>$this->kode(),
            'branch'=>$data['branchId'] ?? $data['gudang_asal'],
            'jenis_keluar'=>$data['jenis_keluar'] ?? 'penjualan',
            'customer'=>$data['customerId'] ?? null,
            'penjualan'=>$kodePenjualan,
            'users'=>$data['userId'] ?? $data['user_id'],
        ]);
    }

    public function updateStockkeluarFromPenjualan(array $dataPenjualan, $id)
    {
        return StockKeluar::where('id', $id)
            ->update([
                'tgl_keluar'=>$dataPenjualan['tglNota'],
                'branch'=>$dataPenjualan['branchId'],
                'jenis_keluar'=>'penjualan',
                'customer'=>$dataPenjualan['customerId'],
                'users'=>$dataPenjualan['userId'],
            ]);
    }

    public function storeStockKeluarDetailArray(array $data)
    {
        return StockKeluarDetil::create([
            'stock_keluar'=>$data['stockKeluarId'],
            'id_produk'=>$data['produkId'] ?? $data['produk_id'],
            'jumlah'=>$data['jumlah']
        ]);
    }

    public function destroyStockKeluarByIdStockkeluar($idStockKeluar)
    {
        return StockKeluar::destroy($idStockKeluar);
    }

    public function storeStockKeluar($dataStockKeluar)
    {
        return StockKeluar::create([
            'active_cash'=>session('ClosedCash'),
            'tgl_keluar'=>$dataStockKeluar->tglStockKeluar,
            'kode'=>$dataStockKeluar->kodeStockKeluar ?? $this->kode(),
            'branch'=>$dataStockKeluar->idBranch ?? null,
            'jenis_keluar'=>$dataStockKeluar->jenisStockKeluar,
            'customer'=>$dataStockKeluar->idCustomer,
            'penjualan'=>$dataStockKeluar->idPenjualan ?? '',
            'users'=>$dataStockKeluar->idUser,
        ]);
    }

    public function destroyDetail($idStockKeluar)
    {
        return StockKeluarDetil::where('stock_keluar', $idStockKeluar)->delete();
    }

    public function storeStockKeluarDetail($dataDetail)
    {
        return StockKeluarDetil::create($dataDetail);
    }

    public function commitStockKeluar($dataStockKeluar)
    {
        DB::beginTransaction();
        try {
            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }
}
