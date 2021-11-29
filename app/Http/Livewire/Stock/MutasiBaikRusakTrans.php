<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\InventoryRealRepository;
use App\Http\Repositories\Stock\InventoryRusakRealRepository;
use App\Http\Repositories\Stock\MutasiGudangBaikRusakRepository;
use App\Http\Repositories\Stock\StockKeluarRepository;
use App\Http\Repositories\Stock\StockRusakKeluarRepository;
use App\Http\Repositories\Stock\StockRusakMasukRepository;
use App\Models\Master\Produk;
use App\Models\Stock\BranchStock;
use App\Models\Stock\StockKeluar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Component;

class MutasiBaikRusakTrans extends Component
{
    public $dataGudangAsal, $dataGudangTujuan;
    public $gudangAsal, $gudangTujuan;
    public $mutasiBaikRusakId, $tglMutasi, $keterangan;
    public $mutasiBaikRusakDetail = [];
    public $update, $indexDetail;

    public $produkId, $produkName, $produkKodeLokal, $produkJumlah;

    protected $listeners = ['getDataProduk'];

    public function mount($idMutasiBaikRusak = null)
    {
        $this->tglMutasi = tanggalan_format(now('ASIA/JAKARTA'));
        $this->mutasiBaikRusakId = $idMutasiBaikRusak;
        $dataGudang = BranchStock::all();
        $this->dataGudangAsal = $dataGudang;
        $this->dataGudangTujuan = $dataGudang;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.stock.mutasi-baik-rusak-trans');
    }

    public function getDataProduk($idProduk)
    {
        $produk = Produk::where('id_produk', $idProduk)->first();
        $this->produkId = $produk->id_produk;
        $this->produkKodeLokal = $produk->kode_lokal;
        $this->produkName = $produk->nama_produk."\n".$produk->cover."\n".$produk->hal;
    }

    public function resetForm()
    {
        $this->produkId = '';
        $this->produkName = '';
        $this->produkJumlah = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function storeItem()
    {
        $this->validate([
            'produkId'=>'required',
            'produkJumlah'=>'required'
        ]);
        $this->mutasiBaikRusakDetail [] = [
            'produkId'=>$this->produkId,
            'kodeLokal'=>$this->produkKodeLokal,
            'item'=>$this->produkName,
            'jumlah'=>$this->produkJumlah,
        ];
        $this->resetForm();
    }

    public function editItem($index)
    {
        $this->update = true;
        $this->indexDetail = $index;
        $this->produkId = $this->mutasiBaikRusakDetail[$index]['produkId'];
        $this->produkKodeLokal = $this->mutasiBaikRusakDetail[$index]['kodeLokal'];
        $this->produkName = $this->mutasiBaikRusakDetail[$index]['item'];
        $this->produkJumlah = $this->mutasiBaikRusakDetail[$index]['jumlah'];
    }

    public function updateItem()
    {
        $this->validate([
            'produkId'=>'required',
            'produkJumlah'=>'required'
        ]);
        $index = $this->indexDetail;
        $this->mutasiBaikRusakDetail[$index]['produkId'] = $this->produkId;
        $this->mutasiBaikRusakDetail[$index]['kodeLokal'] = $this->produkKodeLokal;
        $this->mutasiBaikRusakDetail[$index]['item'] = $this->produkName;
        $this->mutasiBaikRusakDetail[$index]['jumlah'] = $this->produkJumlah;
        $this->resetForm();
        $this->update = false;
    }

    public function deleteItem($index)
    {
        unset($this->mutasiBaikRusakDetail[$index]);
        $this->mutasiBaikRusakDetail = array_values($this->mutasiBaikRusakDetail);
    }

    public function storeAll()
    {
        $this->validate([
            'gudangAsal'=>'required',
            'gudangTujuan'=>'required',
            'tglMutasi'=>'required',
        ]);

        $mutasiBaikRusakRepo = new MutasiGudangBaikRusakRepository();
        $stockBaikKeluarRepo = new StockKeluarRepository();
        $stockRusakMasukRepo = new StockRusakMasukRepository();
        $inventoryBaikRepo = new InventoryRealRepository();
        $inventoryRusakRepo = new InventoryRusakRealRepository();
        \DB::beginTransaction();
        try {
            // make array
            $dataMutasi = [
                'activeCash'=>session('ClosedCash'),
                'gudang_asal'=>$this->gudangAsal,
                'gudang_tujuan'=>$this->gudangTujuan,
                'tgl_mutasi'=>tanggalan_database_format($this->tglMutasi, 'd-M-Y'),
                'user_id'=>\Auth::id(),
                'keterangan'=>$this->keterangan,

                // stock Keluar Baik
                'jenis_keluar'=>'mutasi_rusak_baik'
            ];
            // insert to mutasi baik rusak
            $storeMutasiBaikRusak = $mutasiBaikRusakRepo->storeData($dataMutasi);
            // stock baik keluar
            $storeStockBaikKeluar = $stockBaikKeluarRepo->storeStockKeluarFromPenjualan($dataMutasi, $storeMutasiBaikRusak->id);
            // stock rusak masuk
            $storeStockRusakMasuk = $stockRusakMasukRepo->storeStockMasuk($dataMutasi, null, $storeMutasiBaikRusak->id);

            // insert each
            foreach ($this->mutasiBaikRusakDetail as $row){
                $dataMutasiDetail = [
                    'mutasi_gudang_id'=>$storeMutasiBaikRusak->id,
                    'produk_id'=>$row['produkId'],
                    'jumlah'=>$row['jumlah'],

                    'stockKeluarId'=>$storeStockBaikKeluar->id,
                    'branchId'=>$this->gudangAsal, // untuk stock_out
                    'stockMasukRusakId'=>$storeStockRusakMasuk->id
                ];

                // inventory baik out
                $inventoryBaikRepo->updateInventoryOut($dataMutasiDetail);

                // inventory rusak in
                $inventoryRusakRepo->storeStockIn($this->gudangTujuan, $dataMutasiDetail);

                // insert stock mutasi baik ke rusak detail
                $mutasiBaikRusakRepo->storeDetaildata($dataMutasiDetail);

                // insert stock baik keluar
                $stockBaikKeluarRepo->storeStockKeluarDetailArray($dataMutasiDetail);

                // insert stock rusak masuk
                $stockRusakMasukRepo->storeStockMasukDetail($dataMutasiDetail);
            }
            \DB::commit();
            return redirect()->to(route('stock.mutasi.baik.rusak'));
        } catch (ModelNotFoundException $e){
            \DB::rollBack();
            session()->flash('message', $e);
        }
    }
}
