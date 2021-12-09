<?php

namespace App\Http\Livewire\Stock;

use App\Http\Repositories\Stock\StokcOpnameRusakRepository;
use App\Models\Master\Pegawai;
use App\Models\Master\Produk;
use App\Models\Stock\BranchStock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StockOpnameRusakDetil extends Component
{
    public $branch_id, $tgl_input, $activeCash, $kode, $user, $pelapor, $keterangan;
    public $stockOpnameRusakId, $produk, $jumlah;
    public $produkName, $dataPelapor, $dataGudang;
    public $stockOpnameRusakDetil =[];
    public $update, $indexDetil;

    protected $listeners = ['getDataProduk'];


    public function mount()
    {
        $this->dataPelapor = Pegawai::all();
        $this->dataGudang = BranchStock::all();
    }


    public function render()
    {
        return view('livewire.stock.stock-opname-rusak-detil');
    }

    public function getDataProduk($idProduk)
    {
        $produk = Produk::where('id_produk', $idProduk)->first();
        $this->produk = $produk->id_produk;
        $this->produkName = $produk->nama_produk."\n".$produk->cover."\n".$produk->hal;
    }

    public function resetForm()
    {
        $this->produk = '';
        $this->produkName = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function storeItem()
    {
        $this->validate([
            'produk'=>'required',
            'jumlah'=>'required',
        ]);
        $this->stockOpnameRusakDetil [] = [
          'produk'=>$this->produk,
          'produkName'=>$this->produkName,
          'jumlah'=>$this->jumlah,
        ];
        $this->resetForm();
    }

    public function editItem($index)
    {
        $this->update = true;
        $this->indexDetil = $index;
        $this->produk = $this->stockOpnameRusakDetil[$index]['produk'];
        $this->produkName = $this->stockOpnameRusakDetil[$index]['produkName'];
        $this->jumlah = $this->stockOpnameRusakDetil[$index]['jumlah'];
        $this->resetForm();
        $this->update = false;
    }

    public function deleteItem($index)
    {
        unset($this->stockOpnameRusakDetil[$index]);
        $this->stockOpnameRusakDetil = array_values($this->stockOpnameRusakDetil);
    }

    public function storeAll()
    {
        $this->validate([
           'branch_id'=>'required',
           'tgl_input'=>'required',
        ]);

        $stockOpnameRusakRepo = new StokcOpnameRusakRepository();
        $stockOpnameRusakDetilRepo = new StokcOpnameRusakRepository();
        DB::beginTransaction();
        try {
            $dataOpname = [
                'activeCash'=>session('ClosedCash'),
                'user'=>Auth::id(),
                'pelapor'=>$this->pelapor,
                'branch_id'=>$this->branch_id,
                'tgl_input'=>tanggalan_database_format($this->tgl_input, 'd-M-Y'),
                'keterangan'=>$this->keterangan,
            ];
            // insert to StockOpnameRusak
            $storeStockOpnameRusak = $stockOpnameRusakRepo->storeData($dataOpname);

            foreach ($this->stockOpnameRusakDetil as $row){
                $dataopnameDetil = [
                    'stock_opname_rusak_id'=>$storeStockOpnameRusak->id,
                    'produk_id'=>$row['produk'],
                    'jumlah'=>$row['jumlah'],
                ];
                $storeStockOpnameRusakDetil = $stockOpnameRusakDetilRepo->storeDataDetail($dataopnameDetil);
            }
            DB::commit();
            return redirect()->to(route('stock.opname.rusak'));
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            session()->flash('message', $e);
        }
    }
}
