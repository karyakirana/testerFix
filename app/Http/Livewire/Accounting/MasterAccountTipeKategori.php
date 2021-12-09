<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\AccountKategoriTipe;
use Livewire\Component;
use Livewire\WithPagination;


class MasterAccountTipeKategori extends Component
{
    use WithPagination;
    protected $paginationTheme = 'metronics-bootstrap';
    public $formKategoriTipe = [];
    public $dataKategoriTipe;

    public $search = '';
    public $paginate = 10;

    protected $rules = [
      'formKategoriTipe.prefix'=>'required',
      'formKategoriTipe.kategori'=>'required',
    ];

    public function addData()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        unset($this->formKategoriTipe);
        $this->emit('modalShow');
    }

    public function storeData()
    {
        $this->validate();

        $save = AccountKategoriTipe::create([
           'prefix_kategori'=>$this->formKategoriTipe['prefix'],
           'kategori'=>$this->formKategoriTipe['kategori'],
           'keterangan'=>$this->formKategoriTipe['keterangan'],
        ]);
        $this->closeModal();
    }

    public function updateData()
    {
        $this->validateOnly($this->formKategoriTipe['kategori']);

        $save = AccountKategoriTipe::where('id', $this->formKategoriTipe['id'])
            ->update([
                'prefix_kategori'=>$this->formKategoriTipe['prefix'],
                'kategori'=>$this->formKategoriTipe['kategori'],
                'keterangan'=>$this->formKategoriTipe['keterangan'],
            ]);
        $this->closeModal();
    }

    public function editData($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $data = AccountKategoriTipe::find($id);
        $this->formKategoriTipe['id']=$data->id;
        $this->formKategoriTipe['prefix']=$data->prefix_kategori;
        $this->formKategoriTipe['kategori']=$data->kategori;
        $this->formKategoriTipe['keterangan']=$data->keterangan;
        $this->emit('modalShow');
    }

    public function deleteData($id)
    {
        $delete = AccountKategoriTipe::destroy($id);
    }

    public function closeModal()
    {
        unset($this->formKategoriTipe);
        $this->resetValidation();
        $this->resetErrorBag();
        $this->emit('modalHide');
    }

    public function render()
    {
        return view('livewire.accounting.master-account-tipe-kategori', [
            'accountKategoriTipe'=>AccountKategoriTipe::paginate(10),
        ]);
    }
}
