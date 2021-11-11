<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\AccountKategori;
use Livewire\Component;

class MasterKategori extends Component
{
    public $formKategori = [];
    public $dataKategori;

    protected $rules = [
        'formKategori.nomorKategori'=>'required|integer|unique:accounting_kategori,kode_kategori',
        'formKategori.kategori'=>'required',
    ];

    public function mount()
    {
        //
    }

    public function addData()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        unset($this->formKategori);
        $this->emit('modalShow');
    }

    public function storeData()
    {
        $this->validate();

        $save = AccountKategori::create([
            'kode_kategori'=>$this->formKategori['nomorKategori'],
            'deskripsi'=>$this->formKategori['kategori'],
            'keterangan'=>$this->formKategori['keterangan']
        ]);
        $this->closeModal();
    }

    public function updateData()
    {
        $this->validateOnly($this->formKategori['kategori']);

        $save = AccountKategori::where('id', $this->formKategori['id'])
            ->update([
                'kode_kategori'=>$this->formKategori['nomorKategori'],
                'deskripsi'=>$this->formKategori['kategori'],
                'keterangan'=>$this->formKategori['keterangan']
            ]);
        $this->closeModal();
    }

    public function editData($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $data = AccountKategori::find($id);
        $this->formKategori['id']=$data->id;
        $this->formKategori['nomorKategori']=$data->kode_kategori;
        $this->formKategori['kategori']=$data->deskripsi;
        $this->formKategori['keterangan']=$data->keterangan;
        $this->emit('modalShow');
    }

    public function deleteData($id)
    {
        $delete = AccountKategori::destroy($id);
    }

    public function closeModal()
    {
        unset($this->formKategori);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->emit('modalHide');
    }

    public function render()
    {
        return view('livewire.accounting.master-kategori', [
            'accountKategori'=>AccountKategori::paginate(10),
        ]);
    }
}
