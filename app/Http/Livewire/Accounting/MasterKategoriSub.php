<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\AccountKategori;
use App\Models\Accounting\AccountKategoriSub;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MasterKategoriSub extends Component
{
    public $inputForm = [];

    public function render()
    {
        return view('livewire.accounting.master-kategori-sub', [
            'subKategoriData'=>AccountKategoriSub::all(),
            'selectkategori'=>AccountKategori::all()
        ]);
    }

    protected function resetForm()
    {
        $this->inputForm['id']='';
        $this->inputForm['kategori']='';
        $this->inputForm['kodeKategori']='';
        $this->inputForm['subKategori']='';
        $this->inputForm['keterangan']='';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function close()
    {
        $this->resetForm();
        $this->emit('closeModal');
    }

    public function store()
    {
        $this->validate([
            'inputForm.kategori'=>'required',
            'inputForm.subKategori'=>'required|string',
            'inputForm.kodeKategori'=>['required',
                'integer',
                Rule::unique('accounting_kategori_sub', 'kode_kategori_sub')
                    ->ignore($this->inputForm['id'] ?? '', 'id'),
                ],
        ]);
        AccountKategoriSub::updateOrCreate(
            [
                'id'=>$this->inputForm['id']
            ],
            [
                'kategori_id'=>$this->inputForm['kategori'],
                'kode_kategori_sub'=>$this->inputForm['kodeKategori'],
                'deskripsi'=>$this->inputForm['subKategori'],
                'keterangan'=>$this->inputForm['keterangan'],
        ]);
        $this->resetForm();
        if ($this->inputForm['id'] == '')
        {
            session()->flash('message', 'Data berhasil disimpan.');
        } else {
            session()->flash('message', 'Data berhasil diupdate.');
        }
        $this->emit('closeModal');
    }

    public function edit($id)
    {
        $data = AccountKategoriSub::find($id);
        $this->inputForm['id']= $data->id;
        $this->inputForm['kategori']= $data->kategori_id;
        $this->inputForm['kodeKategori']= $data->kode_kategori_sub;
        $this->inputForm['subKategori']= $data->deskripsi;
        $this->inputForm['keterangan']= $data->keterangan;
        $this->emit('openModal');
    }

    public function delete($id)
    {
        try {
            $data = AccountKategoriSub::destroy($id);
            session()->flash('message', 'Data berhasil di Hapus.');
        } catch (\Exception $e){
            session()->flash('message', $e);
        }
    }
}
