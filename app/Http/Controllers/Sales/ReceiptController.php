<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use App\Models\Sales\ReturBaik;
use App\Models\Sales\ReturBaikDetil;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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

    public function salesReceiptPdf($id)
    {
        $idPenjualan = str_replace('-', '/', $id);
        $penjualan = Penjualan::with(['customer', 'pengguna', 'detilPenjualan', 'detilPenjualan.produk'])->where('id_jual', $idPenjualan)->first();
//        return view('pages.print.salesReceiptPdf', ['data'=>$penjualan]);
        $pdf = PDF::loadView('pages.print.ReceiptPrintPDF', ['data'=>$penjualan]);
        $pdf->setOptions([
            'page-size'=>'letter',
//            'page-width'=>'215.9',
//            'page-height'=>'139.7',
        ]);
        return $pdf->inline();
    }

    public function returBaikReceipt($id)
    {
        $idPenjualan = str_replace('-', '/', $id);

        $dataPen = ReturBaik::leftJoin('user as u', 'return_bersih.id_user', '=', 'u.id_user')
            ->leftJoin('customer as c', 'return_bersih.id_cust', '=', 'c.id_cust')
            ->select(
                'return_bersih.id_return as penjualanId',
                'c.nama_cust as namaCustomer',
                'addr_cust',
                'tgl_nota',
                'total_jumlah',
                'ppn',
                'biaya_lain',
                'total_bayar',
                'return_bersih.keterangan as penket',
                'u.username as namaSales1',
                'return_bersih.updated_at as update', // last print
            )
            ->where('id_return', $idPenjualan)
            ->first();
        $dataPenjualan = [
            'penjualanId' => $dataPen->penjualanId,
            'namaCustomer' => $dataPen->namaCustomer,
            'addr_cust' => $dataPen->addr_cust,
            'tgl_nota' => date('d-m-Y', strtotime($dataPen->tgl_nota)),
            'tgl_tempo' => ( strtotime($dataPen->tgl_tempo) > 0) ? date('d-m-Y', strtotime($dataPen->tgl_tempo)) : '',
            'status_bayar' => $dataPen->status_bayar ?? '',
            'sudahBayar' => $dataPen->sudahBayar ?? '',
            'total_jumlah' => $dataPen->total_jumlah,
            'ppn' => $dataPen->ppn,
            'biaya_lain' => $dataPen->biaya_lain,
            'total_bayar' => $dataPen->total_bayar,
            'penket' => $dataPen->penket,
            'print' => $dataPen->print,
            'update' => $dataPen->update,
        ];
        // update print
//        $updatePrint = ReturBaik::where('id_return', $idPenjualan)->update(['print' => $dataPen->print + 1]);
        // $dataPenjualan = Penjualan::where('id_jual', $idPenjualan)->first();
        // $dataPenjualanDetail = PenjualanDetail::where('id_jual', $idPenjualan)->get();
        $dataPenjualanDetail = ReturBaikDetil::leftJoin('produk', 'rb_detail.id_produk', '=', 'produk.id_produk')
            ->where('id_return', $idPenjualan)
            ->get();
//        dd($dataPenjualanDetail);
        $data = [
            'dataUtama' => json_encode($dataPenjualan),
            'dataDetail' => $dataPenjualanDetail
        ];
        return view('pages.print.salesReturReceipt', $data);
    }
}
