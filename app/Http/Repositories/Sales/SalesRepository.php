<?php

namespace App\Http\Repositories\Sales;

use App\Http\Repositories\Stock\InventoryRealRepository;
use App\Http\Repositories\Stock\StockKeluarRepository;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Segala bentuk manipulasi data sebelum disimpan ke database
 */
class SalesRepository
{
    /**
     * get Penjualan and Detail
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getPenjualanAndDetail($id)
    {
        return Penjualan::with(['detilPenjualan', 'detilPenjualan.produk'])
            ->where('id', $id)
            ->first();
    }

    public function kode()
    {
        $data = Penjualan::where('activeCash', session('ClosedCash'))->latest('id_jual')->first();
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

    public function storePenjualan($dataPenjualan)
    {
        return Penjualan::create(
            [
                'activeCash' => session('ClosedCash'),
                'id_jual' => $dataPenjualan->idPenjualan ?? $this->kode(),
                'tgl_nota' => $dataPenjualan->tglPenjualan,
                'tgl_tempo' => ($dataPenjualan->jenisBayar == 'Tempo') ? $dataPenjualan->tglTempo : null,
                'status_bayar' => $dataPenjualan->jenisBayar,
                'sudahBayar'=> $dataPenjualan->sudahBayar,
                'total_jumlah' => $dataPenjualan->total_jumlah,
                'ppn' => $dataPenjualan->ppn,
                'biaya_lain' => $dataPenjualan->biayaLain,
                'total_bayar' => $dataPenjualan->total_bayar,
                'id_cust' => $dataPenjualan->idCustomer,
                'id_user' => $dataPenjualan->idUser,
                'idBranch'=> $dataPenjualan->idBranch,
                'keterangan' => $dataPenjualan->keterangan,
            ]
        );
    }

    public function storePenjualanDetail($dataDetail)
    {
        return PenjualanDetil::create($dataDetail);
    }

    /**
     * @param $data
     * @return array|bool[]
     */
    public function commitPenjualan($data)
    {
        // prepare for Detail Commit

        DB::beginTransaction();
        try {
            /**
             * Master Commit
             */
            $storePenjualan = $this->storePenjualan($data);
            $storeStockKeluar = (new StockKeluarRepository())->storeStockKeluar($data);
            /**
             * Detil Commit each detail
             */
            $forCommitDetail = null;
            $getTempPenjualan = (new SalesTempRepository())->getTempDetail($data->idTemp);
            foreach ($getTempPenjualan as $tempDetail){
                $dataDetail = [
                    'id_jual'=>$data->idPenjualan,
                    'stock_keluar'=>$storeStockKeluar->id,
                    'id_produk'=>$tempDetail->idBarang,
                    'jumlah'=>$tempDetail->jumlah,
                    'harga'=>$tempDetail->harga,
                    'diskon'=>$tempDetail->diskon,
                    'sub_total'=>$tempDetail->sub_total,
                    // branch
                    'branch_id'=>$data->idBranch,
                ];
                (new InventoryRealRepository())->insertOrUpdate((object)$dataDetail);
                $this->storePenjualanDetail($dataDetail);
                (new StockKeluarRepository())->storeStockKeluarDetail($dataDetail);
            }
            (new SalesTempRepository())->destroyAllTemp($data->idTemp);
            session()->forget('penjualan');
            DB::commit();
            $returnData = [
                'status'=>true,
                'nomorPenjualan'=>str_replace('/', '-', $data->idPenjualan)
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $returnData = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return $returnData;
    }

    public function getSalesData($id_jual)
    {
        // get data from penjualan
        $dataPenjualan = Penjualan::where('id_jual', $id_jual)->first();
    }

    public function getSalesById($id)
    {
        return Penjualan::with(['detilPenjualan', 'detilPenjualan.produk'])
            ->where('id', $id)
            ->first();
    }

    public function getSalesAllByActiveCash($activeCash, $search)
    {
        return Penjualan::with(['pengguna', 'customer'])
            ->where('activeCash', $activeCash)
            ->where(function ($query) use ($search) {
                $query->where('id_jual', 'like', '%'.$search.'%');
            })
            ->latest('id_jual')
            ->paginate(10);
    }

    public function commitUpdatePenjualan($data)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            $returnData = [
                'status'=>true,
                'nomorPenjualan'=>str_replace('/', '-', $data->idPenjualan)
            ];
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            $returnData = [
                'status'=>false,
                'keterangan'=>$e
            ];
        }
        return $returnData;
    }
}
