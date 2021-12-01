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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StockMutasiRusak2 extends Component
{
    public $idProduk, $namaProduk, $kodeLokal, $jumlah, $mutasiRusakRusakId;
    public $activeCash, $gudang_asal, $gudang_tujuan, $user_id, $tgl_mutasi, $keterangan, $dataGudangAsal, $dataGudangTujuan;

    public $mutasiRusakRusakDetail = [];

    public $update, $indexItem;

    protected $listeners = ['getDataProduk'];

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
                'tgl_mutasi'=>$this->tgl_mutasi,
                'user_id'=>\Auth::id(),
                'keterangan'=>$this->keterangan,

                'jenis_keluar'=>'mutasi_rusak_rusak'
            ];
            // insert to mutasi rusak rusak
            $storeMutasiRusakRusak = $mutasiRusakRusakRepo->storeData($dataMutasiRusak);
            // insert to stock rusak masuk
            $storeStockRusakMasuk = $stockRusakMasukRepo->storeStockMasuk($dataMutasiRusak, null, $storeMutasiRusakRusak->id);
            // insert to stock rusak keluar
            $storeStockRusakKeluar = $stockRusakKeluarRepo->storeStockKeluar($dataMutasiRusak, $storeMutasiRusakRusak->id);
            foreach ($this->mutasiRusakRusakDetail as $row)
            {
                $dataMutasiRusakDetail = [
                    'mutasi_gudan_id'=>$storeMutasiRusakRusak->id,
                    'produk_id'=>$row->idProduk,
                    'jumlahStock'=>$row->jumlah,

                    'stockKeluarRusak'=>$storeStockRusakKeluar->id,
                    'branchId'=>$this->gudang_asal,
                    'stockMasukRusakId'=>$storeStockRusakMasuk->id,
                ];
                // inv rusak out
                $inventoryRusakRepo->storeStockIn($this->gudang_tujuan, $dataMutasiRusakDetail);

            }
            DB::commit();
            return redirect()->to('stock/rusak/mutasi/rusak');
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
