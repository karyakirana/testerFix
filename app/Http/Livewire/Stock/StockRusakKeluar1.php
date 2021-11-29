<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Master\ProdukRepository;
use App\Http\Repositories\Stock\StockKeluarRepository;
use App\Models\Master\Supplier;
use App\Models\Master\Produk;
use App\Models\Stock\InventoryRusak;
use App\Models\Stock\StockKeluarRusak;
use App\Models\Stock\StockKeluarRusakDetil;
use App\Models\Stock\BranchStock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockRusakKeluar1 extends Component
{
    public $idProduk, $namaProduk, $stockOpname, $supplier, $supplierId;
    public $jumlah, $activeCash, $kode_lokal, $tgl_keluar_rusak, $keterangan;

    public $detailStockRusak = [];

    public $update, $indexItem;

    protected $listeners = ['getDataProduk', 'getDataSupplier'];

    public function mount()
    {
        $this->tgl_keluar_rusak = tanggalan_format(now('ASIA/JAKARTA'));
    }

    public function getDataProduk($idProduk)
    {
        $produk = Produk::where('id_produk', $idProduk)->first();
        $this->idProduk = $produk->id_produk;
        $this->namaProduk = $produk->nama_produk."\n".$produk->cover."\n".$produk->hal;
        $this->kode_lokal = $produk->kode_lokal;
    }

    public function getDataSupplier($supplierId)
    {
        $supplier = Supplier::where('kodeSupplier', $supplierId)->first();
        $this->supplierId = $supplier->kodeSupplier;
        $this->supplier = $supplier->namaSupplier;
    }
    public function resetForm()
    {
        $this->idProduk = '';
        $this->jumlah = '';
        $this->supplierId = '';
    }

    public function storeItem()
    {
        $this->validate([
            'idProduk'=>'required',
            'jumlah'=>'required'
        ]);
        $this->detailStockRusak[] = [
            'id_produk'=>$this->idProduk,
            'nama_produk'=>$this->namaProduk,
            'kode_lokal'=>$this->kode_lokal,
            'jumlahStock'=>$this->jumlah,
            'kode_supplier'=>$this->supplierId,
        ];
    }

    public function editItem($index)
    {
        $this->update = true;
        $this->indexDetail = $index;
        $this->idProduk = $this->detailStockRusak[$index]['id_produk'];
        $this->namaProduk = $this->detailStockRusak[$index]['nama_produk'];
        $this->kode_lokal = $this->detailStockRusak[$index]['kode_lokal'];
        $this->jumlah = $this->detailStockRusak[$index]['jumlahStock'];
        $this->supplierId = $this->detailStockRusak[$index]['kodeSupplier'];
    }

    public function updateItem()
    {
        $index = $this->indexItem;
        $this->detailStockRusak[$index]['id_produk'] = $this->idProduk;
        $this->detailStockRusak[$index]['nama_produk'] = $this->namaProduk;
        $this->detailStockRusak[$index]['kode_lokal'] = $this->kode_lokal;
        $this->detailStockRusak[$index]['jumlahStock'] = $this->jumlah;
        $this->detailStockRusak[$index]['kodeSupplier'] = $this->supplierId;
    }

    public function storeAll()
    {
        DB::beginTransaction();
        try {
            $datainventory_real_rusak = [
                'kode_lokal'=>$this->kode_lokal,
                'activeCash'=>session('ClosedCash'),
                'user_id'=>auth()->id(),
                'tgl_keluar_rusak'=>tanggalan_database_format($this->tgl_keluar_rusak, 'd_M_Y'),
                'keterangan'=>$this->keterangan,
            ];
            foreach ($this->detailStockRusak as $row)
            {
                $datainventory_real_rusak = [
                    'stock_keluar_rusak_id'=>'stockKeluarRusakId',
                    'produk_id'=>$this->idProduk,
                    'jumlahStock'=>$this->jumlah,
                ];
            }
            DB::commit();
            return redirect()->to('stock/rusak/masuk');
        } catch (\Exception $e){
            DB::rollBack();
            session()->flash('message', $e);
        }
    }

    public function deleteItem($index)
    {
        unset($this->detailStockRusak[$index]);
        $this->detailStockRusak = array_values($this->detailStockRusak);
    }

    public function render()
    {
        return view('livewire.stock.stock-rusak-keluar1');
    }
}
