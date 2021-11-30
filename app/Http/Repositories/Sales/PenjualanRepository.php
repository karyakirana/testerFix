<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;

class PenjualanRepository
{
    public function getPenjualanForSearch($search=null)
    {
        return Penjualan::where('activeCash', session('ClosedCash'))
            ->Where(function ($query) use($search){
                $query->whereRelation('customer', 'nama_cust', 'like', '%'.$search.'%')
                    ->OrWhere('id_jual', 'like', '%'.$search.'%');
            })
            ->latest('id_jual')
            ->paginate(10);
    }

    public function getDatapenjualanById($id)
    {
        return Penjualan::find($id);
    }

    public function getDataPenjualanByIdJual($id_jual)
    {
        return Penjualan::where('id_jual', $id_jual)->first();
    }

    public function kodePenjualan()
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

    public function storePenjualan(array $dataPenjualan)
    {
        return Penjualan::create([
            'id_jual'=>$this->kodePenjualan(),
            'activeCash'=>$dataPenjualan['activeCash'],
            'id_cust'=>$dataPenjualan['customerId'],
            'idBranch'=>$dataPenjualan['branchId'],
            'id_user'=>$dataPenjualan['userId'],
            'tgl_nota'=>$dataPenjualan['tglNota'],
            'tgl_tempo'=>$dataPenjualan['tglTempo'],
            'status_bayar'=>$dataPenjualan['statusBayar'],
            'sudahBayar'=>$dataPenjualan['sudahBayar'],
            'total_jumlah'=>$dataPenjualan['totalJumlah'],
            'ppn'=>$dataPenjualan['ppn'],
            'biaya_lain'=>$dataPenjualan['biayaLain'],
            'total_bayar'=>$dataPenjualan['totalBayar'],
            'keterangan'=>$dataPenjualan['keterangan'],
        ]);
    }

    public function updatePenjualan(array $dataPenjualan)
    {
        return Penjualan::where('id_jual', $dataPenjualan['id_jual'])
            ->update([
                'id_cust'=>$dataPenjualan['customerId'],
                'idBranch'=>$dataPenjualan['branchId'],
                'id_user'=>$dataPenjualan['userId'],
                'tgl_nota'=>$dataPenjualan['tglNota'],
                'tgl_tempo'=>$dataPenjualan['tglTempo'],
                'status_bayar'=>$dataPenjualan['statusBayar'],
                'sudahBayar'=>$dataPenjualan['sudahBayar'],
                'total_jumlah'=>$dataPenjualan['totalJumlah'],
                'ppn'=>$dataPenjualan['ppn'],
                'biaya_lain'=>$dataPenjualan['biayaLain'],
                'total_bayar'=>$dataPenjualan['totalBayar'],
                'keterangan'=>$dataPenjualan['keterangan'],
            ]);
    }

    public function storePenjualanDetail(array $data)
    {
        return PenjualanDetil::create([
            'id_jual'=>$data['penjualanId'],
            'id_produk'=>$data['produkId'],
            'jumlah'=>$data['jumlah'],
            'harga'=>$data['harga'],
            'diskon'=>$data['diskon'],
            'sub_total'=>$data['subTotal'],
        ]);
    }

    public function destroyPenjualanDetailByIdJual($idJual)
    {
        return PenjualanDetil::where('id_jual', $idJual)->delete();
    }

    /**
     * Ambil data penjualan yang belum bayar
     * ditampilkan untuk dipindahkan ke
     * piutang atau cash
     * @param $search
     */
    public function getPenjualanBelumBayar($search = null)
    {
        return Penjualan::where('activeCash', session('ClosedCash'))
            ->where('sudahBayar', 'belum')
            ->where(function ($query) use($search){
                $query->whereRelation('customer', 'nama_cust', 'like', '%'.$search.'%')
                    ->OrWhere('id_jual', 'like', '%'.$search.'%');
            })
            ->latest('id_jual')
            ->paginate(10);
    }

    /**
     * Set status belumBayar fields
     * menjadi (lunas atau piutang)
     * @param $idPenjualan
     * @param $status
     */
    public function setStatusBelumBayar($idPenjualan, $status)
    {
        return Penjualan::where('id_jual', $idPenjualan)
            ->update([
                'sudahBayar'=>$status
            ]);
    }
}
