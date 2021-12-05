<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountKategoriSub;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class MasterAccount extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['destroy'];

    public $search;

    public $accountId, $kategoriSubId, $kodeAccount, $tipe, $accountName, $keterangan;
    public $dataSubKategori;

    public function mount()
    {
        $this->dataSubKategori = AccountKategoriSub::all();
    }

    public function render()
    {
        if (Gate::allows('SuperAdmin')){
            return view('livewire.accounting.master-account', [
                'daftarAkun'=>Account::query()
                    ->withTrashed()
                    ->where(function ($query) {
                        $query->whereRelation('accountKategori', 'deskripsi', 'like', '%'.$this->search.'%')
                            ->orWhereRelation('tipe', 'tipe', 'like', '%'.$this->search.'%')
                            ->orWhere('account_name', 'like', '%'.$this->search.'%')
                            ->orWhere('kode_account', 'like', '%'.$this->search.'%');
                    })->paginate(10)
            ]);
        } else {

            return view('livewire.accounting.master-account', [
                'daftarAkun'=>Account::where(function ($query) {
                    $query->whereRelation('accountKategori', 'deskripsi', 'like', '%'.$this->search.'%')
                        ->orWhereRelation('tipe', 'tipe', 'like', '%'.$this->search.'%')
                        ->orWhere('account_name', 'like', '%'.$this->search.'%')
                        ->orWhere('kode_account', 'like', '%'.$this->search.'%');
                })->paginate(10)
            ]);
        }
    }

    public function addData()
    {
        $this->emit('showModalAccount');
    }

    public function store()
    {
        $this->validate([
            'tipe'=>'required',
            'kode'=>'required|unique:accounting_account, kode_account',
            'kategoriSubId'=>'required',
            'namaAkun'=>'required|min:2',
        ]);
        DB::beginTransaction();
        try {
            Account::updateOrCreate(
                [
                    'id'=>$this->accountId
                ],
                [
                    'kode_account'=>$this->kodeAccount,
                    'accounting_tipe_id'=>$this->tipe,
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
        $this->tipe = $account->accounting_tipe_id;
        $this->kategoriSubId = $account->kategori_sub_id;
        $this->kodeAccount = $account->kode_account;
        $this->accountName = $account->account_name;
        $this->keterangan = $account->keterangan;
        $this->emit('showModalAccount');
    }

    public function notification($id, $type=null)
    {
        $this->accountId = $id;
        $this->emit('showNotification', $type);
    }

    public function destroy($type = null)
    {
        if (!is_null($type)){
            $this->forceDestroy($this->accountId);
        } else {
            Account::destroy($this->accountId);
        }
        $this->emit('hideNotification');
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
        $this->tipe = '';
        $this->kategoriSubId = '';
        $this->kodeAccount = '';
        $this->accountName = '';
        $this->keterangan = '';
    }
}
