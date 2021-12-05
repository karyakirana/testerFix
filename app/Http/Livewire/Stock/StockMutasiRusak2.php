<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\MutasiGudangRusakRepository;
use App\Models\Master\Produk;
use App\Models\Master\Supplier;
use App\Models\Stock\BranchStock;
use App\Http\Repositories\Stock\InventoryRusakRealRepository;
use App\Http\Repositories\Stock\StockRusakKeluarRepository;
use App\Http\Repositories\Stock\StockRusakMasukRepository;
use App\Http\Repositories\Stock\StockRusakKeluarDetilRepository;
use App\Http\Repositories\Stock\StockRusakMasukDetilRepository;
use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockMasuk;
use App\Models\Stock\StockMasukDetil;
use App\Models\Stock\StockKeluarDetil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockMutasiRusak2 extends Component
{
    public $idProduk, $namaProduk, $kodeLokal, $jumlah, $mutasiRusakRusakId;
    public $supplierId, $supplier, $jenisSupplier;
    public $activeCash, $gudang_asal, $gudang_tujuan, $user_id, $tgl_mutasi, $keterangan, $dataGudangAsal, $dataGudangTujuan;
    public $idSupplier;

    public $mutasiRusakRusakDetail = [];

    public $update, $indexItem;

    protected $listeners = ['getDataProduk', 'getDataSupplier'];

    public function mount($IdMutasiRusakRusak = null)
    {
        $this->tgl_mutasi = tanggalan_format(now('ASIA/JAKARTA'));
        $this->mutasiRusakRusakId = $IdMutasiRusakRusak;
        $dataGudang = BranchStock::all();
        $this->dataGudangAsal = $dataGudang;
        $this->dataGudangTujuan = $dataGudang;
    }

    public function getDataProduk($idProduk)
    {
        $produk = Produk::where('id_produk', $idProduk)->first();
        $this->idProduk = $produk->id_produk;
        $this->namaProduk = $produk->nama_produk."\n".$produk->cover."\n".$produk->hal;
        $this->kodeLokal = $produk->kode_lokal;
    }

    public function getDataSupplier($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        $this->supplierId = $supplier->id;
        $this->supplier = $supplier->namaSupplier;
        $this->jenisSupplier = $supplier->jenisSupplier;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->idProduk = '';
        $this->jumlah = '';
        $this->namaProduk = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function storeItem()
    {
        $this->validate([
            'idProduk'=>'required',
            'jumlah'=>'required'
        ]);
        $this->mutasiRusakRusakDetail[] = [
            'id_produk'=>$this->idProduk,
            'nama_produk'=>$this->namaProduk,
            'kode_lokal'=>$this->kodeLokal,
            'jumlahStock'=>$this->jumlah,
        ];
        $this->resetForm();
    }
    public function editItem($index)
    {
        $this->update = true;
        $this->indexItem = $index;
        $this->idProduk = $this->mutasiRusakRusakDetail[$index]['id_produk'];
        $this->namaProduk = $this->mutasiRusakRusakDetail[$index]['nama_produk'];
        $this->kodeLokal = $this->mutasiRusakRusakDetail[$index]['kode_lokal'];
        $this->jumlah = $this->mutasiRusakRusakDetail[$index]['jumlahStock'];
    }

    public function updateItem()
    {
        $index = $this->indexItem;
        $this->mutasiRusakRusakDetail[$index]['id_produk'] = $this->idProduk;
        $this->mutasiRusakRusakDetail[$index]['nama_produk'] = $this->namaProduk;
        $this->mutasiRusakRusakDetail[$index]['kode_lokal'] = $this->kodeLokal;
        $this->mutasiRusakRusakDetail[$index]['jumlahStock'] = $this->jumlah;
    }

    public function deleteItem($index)
    {
        unset($this->mutasiRusakRusakDetail[$index]);
        $this->mutasiRusakRusakDetail = array_values($this->mutasiRusakRusakDetail);
    }

    public function storeAll()
    {
        $this->validate([
            'gudang_asal'=>'required',
            'gudang_tujuan'=>'required',
            'supplier'=>'required',
            'tgl_mutasi'=>'required',
        ]);

        $mutasiRusakRusakRepo = new MutasiGudangRusakRepository();
        $stockRusakMasukRepo = new StockRusakMasukRepository();
        $stockRusakKeluarRepo = new StockRusakKeluarRepository();
        $inventoryRusakRepo = new InventoryRusakRealRepository();


        DB::beginTransaction();
        try {
            $dataMutasiRusak = [
                'activeCash'=>session('ClosedCash'),
                'gudang_asal'=>$this->gudang_asal,
                'gudang_tujuan'=>$this->gudang_tujuan,
                'supplierId'=>$this->supplierId,
                'tgl_mutasi'=>tanggalan_database_format($this->tgl_mutasi,'d-M-Y'),
                'user_id'=>\Auth::id(),
                'keterangan'=>$this->keterangan,
                'jenis_keluar'=>'mutasi_rusak_rusak'
            ];
            // insert to mutasi rusak rusak
            $storeMutasiRusakRusak = $mutasiRusakRusakRepo->storeData($dataMutasiRusak);
            // insert to stock rusak masuk
            $storeStockRusakMasuk = $stockRusakMasukRepo->storeStockMasuk($dataMutasiRusak, null, $storeMutasiRusakRusak->id);
            // insert to stock rusak keluar
            $storeStockRusakKeluar = $stockRusakKeluarRepo->storeStockKeluar($dataMutasiRusak);
            foreach ($this->mutasiRusakRusakDetail as $row)
            {
                $dataMutasiRusakDetail = [
                    'mutasi_gudang_id'=>$storeMutasiRusakRusak->id,
                    'produk_id'=>$row['id_produk'],
                    'jumlah'=>$row['jumlahStock'],

                    'stockKeluarRusakId'=>$storeStockRusakKeluar->id,
                    'branchId'=>$this->gudang_asal,
                    'stockMasukRusakId'=>$storeStockRusakMasuk->id,
                ];
                // inv rusak out
                $inventoryRusakRepo->storeStockIn($this->gudang_tujuan, $dataMutasiRusakDetail);
                // insert stock mutasi rusak rusak detail
                $mutasiRusakRusakRepo->storeDetailData($dataMutasiRusakDetail);
                // insert stock rusak keluar
                $stockRusakKeluarRepo->storeStockKeluarDetail($dataMutasiRusakDetail);
                // insert stock rusak masuk
                $stockRusakMasukRepo->storeStockMasukDetail($dataMutasiRusakDetail);

            }
            DB::commit();
            return redirect()->to('stock/rusak/mutasi/rusak');
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            session()->flash('message', $e);
        }
    }

    public function render()
    {
        return view('livewire.stock.stock-mutasi-rusak2');
    }
}
