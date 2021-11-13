<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * List Penjualan
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function tambahanBiayaPenjualan()
    {
        return view('pages.kasir.payment-listBiayaPenjualan');
    }

    public function transaksiTambahanBiayaPenjualan($idPenjualan)
    {
        return view('pages.kasir.payment-transaksi-biaya-penjualan',
        [
            'idPenjualan'=>$idPenjualan
        ]);
    }

    /**
     * Daftar Nota Penjualan
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function notaPenjualan()
    {
        return view('pages.kasir.payment-listBiayaPenjualan');
    }

    public function notaPenjualanTempo()
    {
//        return view('livewire.kasir.nota-penjualan-tempo');
    }

    public function notaPenjualanCash()
    {
        //
    }

    public function notaPenjualanBelumBayar()
    {
        //
    }

    public function penjualanToPiutang()
    {
        //
    }

    public function pembayaranPenjualanCash($idPenjualan)
    {
        return view('pages.kasir.payment-nota-cash', ['idPenjualan'=>$idPenjualan]);
    }
}
