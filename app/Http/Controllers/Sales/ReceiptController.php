<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function printRaw($id)
    {
        $id_jual = str_replace('-', '/', $id);
        $master = Penjualan::find($id_jual);
        $detil = PenjualanDetil::where('id_jual', $id_jual)->get();
        $data = [
            'master'=>$master,
            'detil'=>$detil
        ];
        return view('pages.print.salesReceipt', $data);
    }

    public function salesReceipt($id_jual)
    {
        $idPenjualan = str_replace('-', '/', $id_jual);

        $dataPen = Penjualan::leftJoin('user as u', 'penjualan.id_user', '=', 'u.id_user')
            ->leftJoin('users', 'penjualan.id_user', '=', 'users.idUserOld')
            ->leftJoin('customer as c', 'penjualan.id_cust', '=', 'c.id_cust')
            ->select(
                'penjualan.id_jual as penjualanId',
                'c.nama_cust as namaCustomer',
                'addr_cust',
                'tgl_nota',
                'tgl_tempo',
                'status_bayar',
                'sudahBayar',
                'total_jumlah',
                'ppn',
                'biaya_lain',
                'total_bayar',
                'penjualan.keterangan as penket',
                'u.username as namaSales1',
                'users.name as namaSales2',
                'print', // jumlah print
                'penjualan.updated_at as update', // last print
            )
            ->where('id_jual', $idPenjualan)
            ->first();
        $dataPenjualan = [
            'penjualanId' => $dataPen->penjualanId,
            'namaCustomer' => $dataPen->namaCustomer,
            'addr_cust' => $dataPen->addr_cust,
            'tgl_nota' => date('d-m-Y', strtotime($dataPen->tgl_nota)),
            'tgl_tempo' => ( strtotime($dataPen->tgl_tempo) > 0) ? date('d-m-Y', strtotime($dataPen->tgl_tempo)) : '',
            'status_bayar' => $dataPen->status_bayar,
            'sudahBayar' => $dataPen->sudahBayar,
            'total_jumlah' => $dataPen->total_jumlah,
            'ppn' => $dataPen->ppn,
            'biaya_lain' => $dataPen->biaya_lain,
            'total_bayar' => $dataPen->total_bayar,
            'penket' => $dataPen->penket,
            'print' => $dataPen->print,
            'update' => $dataPen->update,
        ];
        // update print
        $updatePrint = Penjualan::where('id_jual', $idPenjualan)->update(['print' => $dataPen->print + 1]);
        // $dataPenjualan = Penjualan::where('id_jual', $idPenjualan)->first();
        // $dataPenjualanDetail = PenjualanDetail::where('id_jual', $idPenjualan)->get();
        $dataPenjualanDetail = PenjualanDetil::leftJoin('produk', 'detil_penjualan.id_produk', '=', 'produk.id_produk')
            ->where('id_jual', $idPenjualan)
            ->get();
//        dd($dataPenjualanDetail);
        $data = [
            'dataUtama' => json_encode($dataPenjualan),
            'dataDetail' => $dataPenjualanDetail
        ];
        return view('pages.print.salesReceipt', $data);
    }
}
