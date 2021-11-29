<?php

namespace App\Http\Livewire\Sales;

use App\Http\Repositories\Sales\PenjualanRepository;
use App\Http\Repositories\Stock\InventoryRealRepository;
use App\Http\Repositories\Stock\StockKeluarRepository;
use App\Models\Master\Customer;
use App\Models\Master\Produk;
use App\Models\Stock\BranchStock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PenjualanTransaksiNew extends Component
{
    public $idPenjualan, $kodePenjualan, $customerId, $customer, $diskonDariCustomer, $tglNota, $tglTempo, $gudangId, $jenis, $keterangan;
    public $jenisGudang;
    public $total, $ppn, $biayaLain, $totalBayar;
    public $totalRupiah, $totalBayarRupiah;
    public $produkId, $produkName, $produkHarga, $produkJumlah, $produkSubTotal, $produkDiskon, $produkKodeLokal;
    // manipulasi saja
    public $hargaSudahDiskon, $hargaDiskonJumlah, $hargaSubTotal;
    public $detailPenjualan = [];

    public $update, $indexDetail;

    protected $listeners = ['getDataProduk', 'getDataCustomer'];

    public function mount($idPenjualan = null)
    {
        $this->jenisGudang = BranchStock::all();
        $this->tglNota = tanggalan_format(now('ASIA/JAKARTA'));
        $this->tglTempo = tanggalan_format(now('ASIA/JAKARTA')->addMonth(2));

        // hitungan
        $this->hargaSudahDiskon = $this->hitungHargaDiskon();

        // untuk interface edit
        if ($idPenjualan)
        {
            $this->idPenjualan = str_replace('-', '/', $idPenjualan);
            $dataPenjualan = (new PenjualanRepository())->getDataPenjualanByIdJual($this->idPenjualan);

            // set to field
            $this->customerId = $dataPenjualan->id_cust;
            $this->customer = $dataPenjualan->customer->nama_cust;
            $this->jenis = $dataPenjualan->status_bayar;
            $this->tglNota = tanggalan_format($dataPenjualan->tgl_nota);
            $this->tglTempo = tanggalan_format($dataPenjualan->tgl_tempo);
            $this->gudangId = $dataPenjualan->idBranch;
            $this->keterangan = $dataPenjualan->keterangan;

            // set to detail
            $dataPenjualanDetail = $dataPenjualan->detilPenjualan;
            foreach ($dataPenjualanDetail as $index => $item){
                $this->detailPenjualan [] = [
                    'produkId'=>$item->id_produk,
                    'kodeLokal'=>$item->produk->kode_lokal,
                    'item'=>$item->produk->nama_produk,
                    'harga'=>$item->harga,
                    'jumlah'=>$item->harga,
                    'diskon'=>$item->diskon,
                    'subTotal'=>$item->sub_total,
                ];
            }

            // detail bayar
            $this->hitungTotal();
            $this->hitungTotalBayar();
        }
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
        $this->customerId = $customer->id_cust;
        $this->customer = $customer->nama_cust;
        $this->diskonDariCustomer = $customer->diskon;
    }

    public function hitung()
    {
        $this->hitungHargaDiskon();
        $this->hitungSubTotal();
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

    public function hitungTotal()
    {
        $this->total = array_sum(array_column($this->detailPenjualan, 'subTotal'));
        $this->totalRupiah = rupiah_format($this->total);
    }

    public function hitungTotalBayar()
    {
        $this->totalBayar = $this->total + $this->biayaLain + $this->ppn;
        $this->totalBayarRupiah = rupiah_format($this->totalBayar);
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
        $this->validate([
            'produkId'=>'required',
            'produkJumlah'=>'required'
        ]);
        $this->detailPenjualan [] = [
            'produkId'=>$this->produkId,
            'kodeLokal'=>$this->produkKodeLokal,
            'item'=>$this->produkName,
            'harga'=>$this->produkHarga,
            'jumlah'=>$this->produkJumlah,
            'diskon'=>$this->produkDiskon,
            'subTotal'=>$this->produkSubTotal,
        ];
        $this->hitungTotal();
        $this->hitungTotalbayar();
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function editItem($index)
    {
        $this->update = true;
        $this->indexDetail = $index;
        $this->produkId = $this->detailPenjualan[$index]['produkId'];
        $this->produkKodeLokal = $this->detailPenjualan[$index]['kodeLokal'];
        $this->produkName = $this->detailPenjualan[$index]['item'];
        $this->produkHarga = $this->detailPenjualan[$index]['harga'];
        $this->produkJumlah = $this->detailPenjualan[$index]['jumlah'];
        $this->produkDiskon = $this->detailPenjualan[$index]['diskon'];
        $this->produkSubTotal = $this->detailPenjualan[$index]['subTotal'];
        $this->hitungHargaDiskon();
        $this->hitungSubTotal();
    }

    public function updateItem()
    {
        $this->validate([
            'produkId'=>'required',
            'produkJumlah'=>'required'
        ]);
        $index = $this->indexDetail;
        $this->detailPenjualan[$index]['produkId'] = $this->produkId;
        $this->detailPenjualan[$index]['kodeLokal'] = $this->produkKodeLokal;
        $this->detailPenjualan[$index]['item'] = $this->produkName;
        $this->detailPenjualan[$index]['harga'] = $this->produkHarga;
        $this->detailPenjualan[$index]['jumlah'] = $this->produkJumlah;
        $this->detailPenjualan[$index]['diskon'] = $this->produkDiskon;
        $this->detailPenjualan[$index]['subTotal'] = $this->produkSubTotal;
        $this->hitungTotal();
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->update = false;
    }

    public function deleteItem($index)
    {
        unset($this->detailPenjualan[$index]);
        $this->detailPenjualan = array_values($this->detailPenjualan);
    }

    public function storeAll()
    {
        $this->validate([
            'customerId'=>'required',
            'gudangId'=>'required',
            'jenis'=>'required',
            'tglNota'=>'required'
        ]);
        DB::beginTransaction();
        try {
            // set to array
            $dataPenjualan = [
                'activeCash'=>session('ClosedCash'),
                'customerId'=>$this->customerId,
                'branchId'=>$this->gudangId,
                'userId'=>auth()->id(),
                'tglNota'=>tanggalan_database_format($this->tglNota, 'd-M-Y'),
                'tglTempo'=>($this->jenis == 'cash') ? null : tanggalan_database_format($this->tglTempo, 'd-M-Y'),
                'statusBayar'=>$this->jenis,
                'sudahBayar'=>'belum',
                'totalJumlah'=>array_sum(array_column($this->detailPenjualan, 'jumlah')),
                'ppn'=>$this->ppn,
                'biayaLain'=>$this->biayaLain,
                'totalBayar'=>$this->totalBayar,
                'keterangan'=>$this->keterangan
            ];
            // insert to penjualan table
            $storePenjualan = (new PenjualanRepository())->storePenjualan($dataPenjualan);
            // insert to stock_keluar table
            $storeStockKeluar = (new StockKeluarRepository())->storeStockKeluarFromPenjualan($dataPenjualan, $storePenjualan->id_jual);
//            dd($storePenjualan);

            // insert detail
            foreach ($this->detailPenjualan as $row)
            {
                $datapenjualanDetail = [
                    'penjualanId'=>$storePenjualan->id_jual,
                    'stockKeluarId'=>$storeStockKeluar->id,
                    'branchId'=>$this->gudangId,
                    'produkId'=>$row['produkId'],
                    'harga'=>$row['harga'],
                    'jumlah'=>$row['jumlah'],
                    'diskon'=>$row['diskon'],
                    'subTotal'=>$row['subTotal'],
                ];
                // insert detil_penjualan table
                (new PenjualanRepository())->storePenjualanDetail($datapenjualanDetail);
                // insert stock_keluar_detil
                (new StockKeluarRepository())->storeStockKeluarDetailArray($datapenjualanDetail);
                // update inventory_real
                (new InventoryRealRepository())->updateInventoryOut($datapenjualanDetail);
            }
            DB::commit();
            return redirect()->to('sales/print/'.str_replace('/', '-', $storePenjualan->id_jual));
        } catch (\Exception $e){
            DB::rollBack();
            session()->flash('message', $e);
        }
    }

    public function updateAll()
    {
        $this->validate([
            'customerId'=>'required',
            'gudangId'=>'required',
            'jenis'=>'required',
            'tglNota'=>'required'
        ]);

        $penjualanRepository = new PenjualanRepository();
        $stockKeluarRepository = new StockKeluarRepository();
        $inventoryRealRepository = new InventoryRealRepository();

        DB::beginTransaction();
        try {
            // set to array
            $dataPenjualan = [
                'id_jual'=>$this->idPenjualan,
                'customerId'=>$this->customerId,
                'branchId'=>$this->gudangId,
                'userId'=>auth()->id(),
                'tglNota'=>tanggalan_database_format($this->tglNota, 'd-M-Y'),
                'tglTempo'=>($this->jenis == 'cash') ? null : tanggalan_database_format($this->tglTempo, 'd-M-Y'),
                'statusBayar'=>$this->jenis,
                'sudahBayar'=>'belum',
                'totalJumlah'=>array_sum(array_column($this->detailPenjualan, 'jumlah')),
                'ppn'=>$this->ppn,
                'biayaLain'=>$this->biayaLain,
                'totalBayar'=>$this->totalBayar,
                'keterangan'=>$this->keterangan
            ];
            // update penjualan
            $updatePenjualan = $penjualanRepository->updatePenjualan($dataPenjualan);
            // update Stock Keluar
            $getStockKeluar = $stockKeluarRepository->getStockKeluarByIdJual($this->idPenjualan);
            $stockKeluarId = $getStockKeluar->id;
            $updateStockKeluar = $stockKeluarRepository->updateStockkeluarFromPenjualan($dataPenjualan, $stockKeluarId);

            // delete detail first
            $penjualanRepository->destroyPenjualanDetailByIdJual($this->idPenjualan);
            $stockKeluarRepository->destroyStockKeluarByIdStockkeluar($stockKeluarId);

            // rollback inventory by stock keluar
            $inventoryRealRepository->rollbackInventoryOut($getStockKeluar->stockKeluarDetail, $this->gudangId);

            //insert detail
            foreach ($this->detailPenjualan as $row)
            {
                $datapenjualanDetail = [
                    'penjualanId'=>$this->idPenjualan,
                    'stockKeluarId'=>$stockKeluarId,
                    'branchId'=>$this->gudangId,
                    'produkId'=>$row['produkId'],
                    'harga'=>$row['harga'],
                    'jumlah'=>$row['jumlah'],
                    'diskon'=>$row['diskon'],
                    'subTotal'=>$row['subTotal'],
                ];
                // insert detil_penjualan table
                $penjualanRepository->storePenjualanDetail($datapenjualanDetail);
                // insert stock_keluar_detil
                $stockKeluarRepository->storeStockKeluarDetailArray($datapenjualanDetail);
                // update inventory_real
                $inventoryRealRepository->updateInventoryOut($datapenjualanDetail);
            }
            DB::commit();
            return redirect()->to('sales/print/'.str_replace('/', '-', $this->idPenjualan));
        } catch (\Exception $e){
            DB::rollBack();
            session()->flash('message', $e);
        }
    }

    public function render()
    {
        return view('livewire.sales.penjualan-transaksi-new');
    }
}
