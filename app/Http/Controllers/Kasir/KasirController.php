<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    /**
     * Daftar semua nota yang belum dibayar
     * atau yang belum digolongkan pada piutang
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.kasir.kasir-penjualan-belumbayar');
    }

    public function tambahBiaya($idPenjualan)
    {
        return view('pages.kasir.kasir-penjualan-tambah-biaya', [
            'idPenjualan'=>$idPenjualan
        ]);
    }

    public function daftarCash($sudahBayar = null)
    {
        return view('pages.kasir.kasir-daftar-cash', [
            'sudahBayar'=>$sudahBayar
        ]);
    }

    public function daftarPiutang()
    {
        //
    }

    public function setPiutangTransaksi()
    {
        return view('pages.kasir.payment-set-piutang');
    }

    public function penerimaanCash()
    {
        return view('pages.kasir.penerimaan-cash');
    }

    public function penerimaanCashTransaksi($penerimaanCashId = null)
    {
        return view('pages.kasir.penerimaan-cash-transaksi', [
            'penerimaanCashId'=>$penerimaanCashId
        ]);
    }

    public function penerimaanTempo()
    {
        //
    }

    public function penerimaanTempoTransaksi()
    {
        //
    }
}
