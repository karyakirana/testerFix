<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\StockRusakMasukDetilRepository;
use App\Http\Repositories\Stock\StockRusakMasukRepository;
use App\Models\Master\Supplier;
use App\Models\Stock\InventoryRusak;
use App\Models\Stock\StockMasukRusak;
use App\Models\Stock\StockMasukRusakDetil;
use App\Models\Master\Produk;
use App\Models\Stock\BranchStock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockMasukRusak1 extends Component
{
    public $idProduk, $namaProduk, $branchId, $stockOpname, $stockIn, $stockOut, $stockNow, $supplier, $supplierId, $jenisSupplier;
    public $jenis, $jumlah, $activeCash, $kode, $retur_id, $tgl_masuk_rusak, $mutasi_id, $keterangan;

    public $detailStockRusak = [];

    public $update, $indexItem;

    protected $listeners = ['getDataProduk', 'getDataSupplier'];

    public function mount()
    {
        $this->jenis = BranchStock::all();
        $this->tgl_masuk_rusak = tanggalan_format(now('ASIA/JAKARTA'));
    }

    public function getDataProduk($idProduk)
    {
        $produk = Produk::where('id_produk', $idProduk)->first();
        $this->idProduk = $produk->id_produk;
        $this->namaProduk = $produk->nama_produk."\n".$produk->cover."\n".$produk->hal;
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
        $this->branchId = '';
        $this->jenis = '';
        $this->jumlah = '';
        $this->supplierId = '';
        $this->stockOut = '';
        $this->stockIn = '';
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
        'branchId'=>$this->branchId,
        'jenis'=>$this->jenis,
        'jumlahStock'=>$this->jumlah,
        'stockOut'=>$this->stockOut,
        'stockIn'=>$this->stockIn,
        ];
    }

    public function editItem($index)
    {
        $this->update = true;
        $this->indexItem = $index;
        $this->idProduk = $this->detailStockRusak[$index]['id_produk'];
        $this->namaProduk = $this->detailStockRusak[$index]['nama_produk'];
        $this->branchId = $this->detailStockRusak[$index]['branchId'];
        $this->jenis = $this->detailStockRusak[$index]['jenis'];
        $this->jumlah = $this->detailStockRusak[$index]['jumlahStock'];
        $this->stockOut = $this->detailStockRusak[$index]['stockOut'];
        $this->stockIn = $this->detailStockRusak[$index]['stockIn'];
    }

    public function updateItem()
    {
        $index = $this->indexItem;
        $this->detailStockRusak[$index]['id_produk'] = $this->idProduk;
        $this->detailStockRusak[$index]['nama_produk'] = $this->namaProduk;
        $this->detailStockRusak[$index]['branchId'] = $this->branchId;
        $this->detailStockRusak[$index]['jenis'] = $this->jenis;
        $this->detailStockRusak[$index]['kodeSupplier'] = $this->supplierId;
        $this->detailStockRusak[$index]['stockOut'] = $this->stockOut;
        $this->detailStockRusak[$index]['stockIn'] = $this->stockIn;
    }


    public function storeAll()
    {
        DB::beginTransaction();
        try{
            $dataStockMasuk = [
                'jenis'=>'stock_masuk',
                'activeCash'=>session('ClosedCash'),
                'branch_id'=>$this->branchId,
                'retur_id'=>$this->retur_id,
                'user_id'=>auth()->id(),
                'tgl_masuk_rusak'=>tanggalan_database_format($this->tgl_masuk_rusak,'d-M-Y'),
                'keterangan'=>$this->keterangan
            ];
            // insert stock table
            $storeStockMasuk = (new StockRusakMasukRepository())->storeStockMasuk($dataStockMasuk);
            // insert stock detil table
            $storeStockMasuk = (new StockRusakMasukDetilRepository())->create($dataStockMasuk);


            foreach ($this->detailStockRusak as $row)
            {
                $dataStockMasukDetail = [
                'stock_masuk_rusak_id'=>'stockMasukRusakId',
                'produk_id'=>$this->idProduk,
                'jumlah'=>$this->jumlah,
                ];

                (new StockRusakMasukRepository())->storeStockMasukDetail($dataStockMasukDetail);
            }
            DB::commit();
            return redirect()->to('stock/rusak/real');
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
        return view('livewire.stock.stock-masuk-rusak1');
    }


}
