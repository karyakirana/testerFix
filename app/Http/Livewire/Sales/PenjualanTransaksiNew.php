<?php

namespace App\Http\Livewire\Sales;

use App\Models\Master\Customer;
use App\Models\Master\Produk;
use App\Models\Stock\BranchStock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PenjualanTransaksiNew extends Component
{
    public $idPenjualan, $kodePenjualan, $customerId, $customer, $diskonDariCustomer, $tglNota, $tglTempo, $gudangId, $jenis, $keterangan;
    public $jenisGudang;
    public $produkId, $produkName, $produkHarga, $produkJumlah, $produkSubTotal, $produkDiskon, $produkKodeLokal;
    // manipulasi saja
    public $hargaSudahDiskon, $hargaDiskonJumlah, $hargaSubTotal;
    public $detailPenjualan = [];

    public $update, $indexDetail;

    protected $listeners = ['getDataProduk', 'getDataCustomer'];

    public function mount()
    {
        $this->jenisGudang = BranchStock::all();
        $this->tglNota = tanggalan_format(now('ASIA/JAKARTA'));
        $this->tglTempo = tanggalan_format(now('ASIA/JAKARTA')->addMonth(2));

        // hitungan
        $this->hargaSudahDiskon = $this->hitungHargaDiskon();
    }

    public function getDataProduk($produkId)
    {
        $produk = Produk::where('id_produk', $produkId)->first();
        $this->produkId = $produk->id_produk;
        $this->produkKodeLokal = $produk->kode_lokal;
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

    public function hitungHargaDiskon()
    {
        $diskon = (float) $this->produkDiskon;
        $this->hargaSudahDiskon = $this->produkHarga - ($this->produkHarga * $diskon / 100);
    }

    public function hitungSubTotal()
    {
        $jumlah = (int) $this->produkJumlah;
        $this->hargaSubTotal = (rupiah_format($this->hargaSudahDiskon * $jumlah));
        $this->produkSubTotal = $this->hargaSudahDiskon * $jumlah;
    }

    public function resetForm()
    {
        $this->produkId = '';
        $this->produkName = '';
        $this->produkHarga = '';
        $this->produkJumlah = '';
        $this->produkSubTotal = '';
        $this->produkDiskon = '';
        $this->produkKodeLokal = '';

        $this->hargaSudahDiskon = '';
        $this->hargaDiskonJumlah = '';
        $this->hargaSubTotal = '';
    }

    public function storeItem()
    {
        $this->detailPenjualan [] = [
            'produkId'=>$this->produkId,
            'kodeLokal'=>$this->produkKodeLokal,
            'item'=>$this->produkName,
            'harga'=>$this->produkHarga,
            'jumlah'=>$this->produkJumlah,
            'diskon'=>$this->produkDiskon,
            'subTotal'=>$this->produkSubTotal,
        ];
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function editItem($index)
    {
        $this->update = true;
        $this->indexDetail = $index;
        $this->produkId = $this->detailPenjualan['produkId'];
        $this->produkKodeLokal = $this->detailPenjualan['kodeLokal'];
        $this->produkName = $this->detailPenjualan['item'];
        $this->produkHarga = $this->detailPenjualan['harga'];
        $this->produkJumlah = $this->detailPenjualan['jumlah'];
        $this->produkDiskon = $this->detailPenjualan['diskon'];
        $this->produkSubTotal = $this->detailPenjualan['subTotal'];
    }

    public function updateitem()
    {
        $index = $this->indexDetail;
        $this->detailPenjualan[$index]['produkId'] = $this->produkId;
        $this->detailPenjualan[$index]['kodeLokal'] = $this->produkKodeLokal;
        $this->detailPenjualan[$index]['item'] = $this->produkName;
        $this->detailPenjualan[$index]['harga'] = $this->produkHarga;
        $this->detailPenjualan[$index]['jumlah'] = $this->produkJumlah;
        $this->detailPenjualan[$index]['diskon'] = $this->produkDiskon;
        $this->detailPenjualan[$index]['subTotal'] = $this->produkSubTotal;
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function deleteItem($index)
    {
        unset($this->detailPenjualan[$index]);
        $this->detailPenjualan = array_values($this->detailPenjualan);
    }

    public function storeAll()
    {
        DB::beginTransaction();
        try {
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
        }
    }

    public function render()
    {
        return view('livewire.sales.penjualan-transaksi-new');
    }
}
