<?php

namespace App\Http\Livewire\Stock;

use App\Models\Master\Produk;
use App\Models\Master\Supplier;
use App\Models\Stock\BranchStock;
use App\Http\Repositories\Stock\InventoryRusakRealRepository;
use App\Http\Repositories\Stock\StockRusakKeluarRepository;
use App\Http\Repositories\Stock\StockRusakMasukRepository;
use App\Http\Repositories\Stock\StockRusakKeluarDetilRepository;
use App\Http\Repositories\Stock\StockRusakMasukDetilRepository;
use App\Models\Stock\StockMutasiRusak2 as MutasiRusak2;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use App\Models\Stock\StockKeluarDetil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockMutasiRusak2 extends Component
{
    public $idProduk, $namaProduk, $stockOpname, $supplier, $supplierId, $jumlah;
    public $activeCash, $kode, $gudang_asal, $gudang_tujuan, $user_id, $tgl_mutasi, $keterangan;

    public $detailStockMutasiRusak2 = [];

    public $update, $indexItem;

    protected $listeners = ['getDataProduk', 'getDataSupplier'];

    public function mount()
    {
        $this->jenis = BranchStock::all();
        $this->tgl_mutasi = tanggalan_format(now('ASIA/JAKARTA'));
    }

    public function getDataProduk($idProduk)
    {
        $produk = Produk::where('id_produk', $idProduk)->first();
        $this->idProduk = $produk->id_produk;
        $this->namaProduk = $produk->nama_produk."\n".$produk->cover."\n".$produk->hal;
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
        $this->detailStockMutasiRusak2[] = [
            'id_produk'=>$this->idProduk,
            'nama_produk'=>$this->namaProduk,
            'jumlahStock'=>$this->jumlah,
            'kode_supplier'=>$this->supplierId,
        ];
    }
    public function editItem($index)
    {
        $this->update = true;
        $this->indexDetail = $index;
        $this->idProduk = $this->detailStockMutasiRusak2[$index]['id_produk'];
        $this->namaProduk = $this->detailStockMutasiRusak2[$index]['nama_produk'];
        $this->jumlah = $this->detailStockMutasiRusak2[$index]['jumlahStock'];
        $this->supplierId = $this->detailStockMutasiRusak2[$index]['kodeSupplier'];
    }

    public function updateItem()
    {
        $index = $this->indexItem;
        $this->detailStockMutasiRusak2[$index]['id_produk'] = $this->idProduk;
        $this->detailStockMutasiRusak2[$index]['nama_produk'] = $this->namaProduk;
        $this->detailStockMutasiRusak2[$index]['jumlahStock'] = $this->jumlah;
        $this->detailStockMutasiRusak2[$index]['kodeSupplier'] = $this->supplierId;
    }
    public function storeAll()
    {
        $this->validate([
           'id_produk'=>'required',
           'jumlah'=>'required',
        ]);
        DB::beginTransaction();
        try {
            $datainventory_real_rusak = [
                'kode'=>$this->kode,
                'activeCash'=>session('ClosedCash'),
                'gudang_asal'=>$this->gudang_asal,
                'gudang_tujuan'=>$this->gudang_tujuan,
                'user_id'=>auth()->id(),
                'tgl_mutasi'=>tanggalan_database_format($this->tgl_mutasi, 'd_M_Y'),
                'keterangan'=>$this->keterangan,
            ];
            // insert to stock rusak masuk
            $storeStockMasuk = (new StockRusakMasukRepository())->storeStockMasuk($datainventory_real_rusak);
            // insert to stock rusak keluar
            $storeStockKeluar = (new StockRusakKeluarRepository())->storeStockKeluarRusak($datainventory_real_rusak);
            foreach ($this->detailStockMutasiRusak2 as $row)
            {
                $datainventory_real_rusak = [
                    'stock'=>'stockMutasiRusak2',
                    'produk_id'=>$this->idProduk,
                    'jumlahStock'=>$this->jumlah,
                ];
                // insert to stock rusak masuk detil
                (new StockRusakMasukRepository())->storeStockMasukRusakDetail($datainventory_real_rusak);
                // insert to stock rusak keluar detil
                (new StockRusakKeluarRepository())->storeStocKeluarRusakDetail($datainventory_real_rusak);
            }
            DB::commit();
            return redirect()->to('stock/rusak/masuk');
        } catch (\Exception $e){
            DB::rollBack();
            session()->flash('message', $e);
        }
    }

    public function render()
    {
        return view('livewire.stock.stock-mutasi-rusak2');
    }
}
