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

    public function transaksiTambahanBiayaPenjualan()
    {
        //
    }

    public function penjualanToPiutang()
    {
        //
    }

    public function pembayaranPenjualanCash()
    {
        //
    }
}
