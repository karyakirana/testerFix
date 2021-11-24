<?php

namespace App\Http\Livewire\Sales;

use App\Models\Master\Customer;
use App\Models\Master\Produk;
use App\Models\Stock\BranchStock;
use Livewire\Component;

class PenjualanTransaksiNew extends Component
{
    public $idPenjualan, $kodePenjualan, $customerId, $customer, $diskonDariCustomer, $tglNota, $tglTempo, $gudangId, $jenis, $keterangan;
    public $jenisGudang;
    public $produkId, $produkName, $produkHarga, $produkJumlah, $produkSubTotal, $produkDiskon, $produkKodeLokal;
    // manipulasi saja
    public $hargaSudahDiskon, $hargaDiskonJumlah, $hargaSubTotal;
    public $detailPenjualan = [];

    protected $listeners = ['getDataProduk', 'getDataCustomer'];

    public function mount()
    {
        $this->jenisGudang = BranchStock::all();
        $this->tglNota = tanggalan_format(now('ASIA/JAKARTA'));
        $this->tglTempo = tanggalan_format(now('ASIA/JAKARTA')->addMonth(3));
    }

    public function getDataProduk($produkId)
    {
        $produk = Produk::where('id_produk', $produkId)->first();
        $this->produkId = $produk->id_produk;
        $this->produkName = $produk->nama_produk."\n".$produk->cover."\n".$produk->hal;
        $this->produkDiskon = $this->diskonDariCustomer;
        $this->produkHarga = $produk->harga;
    }

    public function getDataCustomer($customerId)
    {
        $customer = Customer::where('id_cust', $customerId)->first();
        $this->customerId = $customer->id;
        $this->customer = $customer->nama_cust;
        $this->diskonDariCustomer = $customer->diskon;
    }

    public function render()
    {
        return view('livewire.sales.penjualan-transaksi-new');
    }
}
