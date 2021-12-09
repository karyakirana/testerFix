<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\TipeAccount;
use App\Models\Accounting\AccountKategoriTipe;
use Livewire\Component;
use Livewire\WithPagination;
use function PHPUnit\Framework\isNull;

class MasterAccountTipe extends Component
{
    use WithPagination;
    protected $paginationTheme = 'metronics-bootstrap';

    protected $listeners = ['addData'];

    public $search;
    public $tipeId, $tipeKategori, $tipeAkun, $keterangan, $selectKategoriTipe, $idKategoriTipe;
    public $prefix_kategori, $kategori;
    public $paginate = 10;

    public $deleteId;

    public function render()
    {
        return view('livewire.accounting.master-account-tipe', [
            'tipeAccount' => TipeAccount::query()
            ->paginate(10),
        ]);
    }

    public function mount($idKategoriTipe = null)
    {
        $this->selectKategoriTipe = AccountKategoriTipe::all();
        $this->prefix_kategori = collect();

        if (!isNull($idKategoriTipe)){
            $kategoriTipe = TipeAccount::find($idKategoriTipe);
            $this->prefix_kategori = $kategoriTipe->prefix;
        }
    }

    public function addData()
    {
        $this->emit('showModal');
    }

    public function resetForm()
    {
        $this->tipeId = '';
        $this->tipeKategori = '';
        $this->tipeAkun = '';
        $this->keterangan = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->emit('hideModal');
    }

    public function store()
    {
        $this->validate([
            'prefix_kategori'=>'required'
        ]);
        $store =  TipeAccount::updateOrCreate(
            [
                'id'=>$this->tipeId
            ],
            [
                'tipe'=>$this->tipeAkun,
                'kategori_tipe_id'=>$this->prefix_kategori,
                'keterangan'=>$this->keterangan
            ]
        );
        $this->closeModal();
    }

    public function edit($id)
    {
        $data = TipeAccount::find($id);
        $this->tipeId = $data->id;
        $this->tipeKategori = $data->kategori_tipe_id;
        $this->tipeAkun = $data->tipe;
        $this->keterangan = $data->keterangan;
        $this->emit('showModal');
    }

    public function destroy()
    {
        TipeAccount::destroy($this->deleteId);
        $this->emit('hideConfirmDelete');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->emit('showConfirmDelete');
    }
}
