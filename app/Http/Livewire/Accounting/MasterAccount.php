<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountKategoriSub;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MasterAccount extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    public $accountId, $kategoriSubId, $kodeAccount, $accountName, $keterangan;
    public $dataSubKategori;

    public function mount()
    {
        $this->dataSubKategori = AccountKategoriSub::all();
    }

    public function render()
    {
        return view('livewire.accounting.master-account', [
            'daftarAkun'=>Account::where(function ($query) {
                $query->whereRelation('accountKategori', 'deskripsi', 'like', '%'.$this->search.'%')
                    ->orWhere('account_name', 'like', '%'.$this->search.'%')
                    ->orWhere('kode_account', 'like', '%'.$this->search.'%');
            })->paginate(10)
        ]);
    }

    public function addData()
    {
        $this->emit('showModalAccount');
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            Account::updateOrCreate(
                [
                    'id'=>$this->accountId
                ],
                [
                    'kode_account'=>$this->kodeAccount,
                    'account_name'=>$this->accountName,
                    'keterangan'=>$this->keterangan,
                    'kategori_sub_id'=>$this->kategoriSubId
                ]
            );
            DB::commit();
            session()->flash('simpan', 'Data berhasil disimpan');
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            session()->flash('gagal', 'Data tidak berhasil disimpan');
        }
        $this->emit('hideModalAccount');
        $this->resetForm();

    }

    public function edit($id)
    {
        $account = Account::find($id);
        $this->accountId = $account->id;
        $this->kategoriSubId = $account->kategori_sub_id;
        $this->kodeAccount = $account->kode_account;
        $this->accountName = $account->account_name;
        $this->keterangan = $account->keterangan;
        $this->emit('showModalAccount');
    }

    public function destroy($id)
    {
        Account::destroy($id);
        session()->flash('simpan', 'Data berhasil dihapus');
    }

    public function forceDestroy($id)
    {
        Account::withTrashed()
            ->where('id', $id)
            ->forceDelete();
    }

    public function resetForm()
    {
        $this->accountId = '';
        $this->kategoriSubId = '';
        $this->kodeAccount = '';
        $this->accountName = '';
        $this->keterangan = '';
    }
}
