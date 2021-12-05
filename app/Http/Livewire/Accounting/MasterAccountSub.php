<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountKategori;
use App\Models\Accounting\AccountKategoriSub;
use App\Models\Accounting\AccountSub;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use function PHPUnit\Framework\isNull;

class MasterAccountSub extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $selectAccount, $selectSubAccount, $selectKategori, $selectSubKategori;
    public $idAccount, $idSubAccount, $idKategori, $idSubKategori;
    public $kodeAccountSub, $namaAccountSub, $keterangan;
    public $update;

    public function mount($idSubAccount = null)
    {
        $this->selectKategori = AccountKategori::all();
        $this->selectSubKategori = collect();
        $this->selectAccount = collect();

        if (!isNull($idSubAccount)){
            $accountSub = AccountSub::find($idSubAccount);
            $this->idAccount = $accountSub->account_id;
            $this->idSubKategori = $accountSub->account->kategori_sub_id;
            $this->idKategori = $accountSub->account->accountKategori->kategori_id;
        }
    }

    public function render()
    {
        return view('livewire.accounting.master-account-sub', [
            'dataAccountSub'=>AccountSub::query()
                ->paginate(10),
        ]);
    }

    public function updatedIdKategori($kategori)
    {
        $this->selectSubKategori = AccountKategoriSub::where('kategori_id', $kategori)->get();
    }

    public function updatedIdSubkategori($subKategori)
    {
        $this->selectAccount = Account::where('kategori_sub_id', $subKategori)->get();
    }

    public function resetForm()
    {
        $this->idKategori = '';
        $this->idSubKategori = '';
        $this->idAccount = '';
        $this->idSubAccount = '';
        $this->kodeAccountSub = '';
        $this->namaAccountSub = '';
        $this->keterangan = '';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'idAccount'=>'required',
            'namaAccountSub'=>'required|string|min:2',
            'kodeAccountSub'=> ['required',
                Rule::unique('accounting_account_sub', 'kode_account_sub')
                ]
        ]);
        $store = AccountSub::create([
            'account_id'=>$this->idAccount,
            'kode_account_sub'=>$this->kodeAccountSub,
            'sub_name'=>$this->namaAccountSub,
            'keterangan'=>$this->keterangan
        ]);
        $this->emit('hideModalSubAccount');
        $this->resetForm();
    }

    public function edit($idSubAccount)
    {
        $this->update = true;
        $accountSub = AccountSub::find($idSubAccount);
        $this->idKategori = $accountSub->account->accountKategori->kategori_id;
        $this->updatedIdKategori($this->idKategori);
        $this->idSubKategori = $accountSub->account->kategori_sub_id;
        $this->updatedIdSubkategori($this->idSubKategori);
        $this->idAccount = $accountSub->account_id;

        $this->idSubAccount = $accountSub->id;
        $this->kodeAccountSub = $accountSub->kode_account_sub;
        $this->namaAccountSub = $accountSub->sub_name;
        $this->keterangan = $accountSub->keterangan;

        $this->emit('showModalSubAccount');
    }

    public function update()
    {
        $this->validate([
            'idAccount'=>'required',
            'namaAccountSub'=>'required|string|min:2',
            'kodeAccountSub'=> ['required',
                Rule::unique('accounting_account_sub', 'kode_account_sub')->ignore($this->idSubAccount)
            ]
        ]);
        $update = AccountSub::query()
            ->where('id', $this->idSubAccount)
            ->update([
                'account_id'=>$this->idAccount,
                'kode_account_sub'=>$this->kodeAccountSub,
                'sub_name'=>$this->namaAccountSub,
                'keterangan'=>$this->keterangan
            ]);
        $this->emit('hideModalSubAccount');
        $this->resetForm();
        session()->flash('success', 'Data Sudah di Update');
    }

    public function delete($id)
    {
        try {
            $data = AccountSub::destroy($id);
            session()->flash('message', 'Data berhasil di Hapus');
        } catch (\Exception $e){
            session()->flash('message', $e);
        }
    }

    public function addAccountSub()
    {
        $this->emit('showModalSubAccount');
    }
}
