<?php

namespace App\Http\Livewire\Master;

use App\Http\Repositories\Master\PegawaiRepository;
use Livewire\Component;

class PegawaiLiviwire extends Component
{
    public $idPegawai;
    public $kode;
    public $nama;
    public $alamat;
    public $kota;
    public $gender;
    public $kotaLahir;
    public $tglLahir;
    public $ktp;
    public $npwp;
    public $users;

    public $dataPegawaiAll;
    public $dataPegawaiById;

    public $successMessage;

    protected $rules = [
        'nama'=>'required',
        'alamat'=>'required',
        'kota'=>'required',
        'gender'=>'required'
    ];

    public function mount()
    {
        //
    }

    public function render()
    {
        return view('livewire.master.pegawai-liviwire', [
            'dataPegawai'=>(new PegawaiRepository())->getPegawaiAll()
        ]);
    }

    protected function resetInput()
    {
        $this->idPegawai = '';
        $this->kode = '';
        $this->nama = '';
        $this->alamat = '';
        $this->kota = '';
        $this->gender = '';
        $this->kotaLahir = '';
        $this->tglLahir = '';
        $this->ktp = '';
        $this->npwp = '';

        $this->successMessage = '';
    }

    public function cancel()
    {
        $this->resetInput();

        // reset error
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function edit($id)
    {
        $dataPegawai = (new PegawaiRepository())->getPegawaiById($id);
        $this->idPegawai = $dataPegawai->id;
        $this->kode = $dataPegawai->kode;
        $this->nama = $dataPegawai->nama;
        $this->alamat = $dataPegawai->alamat;
        $this->kota = $dataPegawai->kota;
        $this->gender = $dataPegawai->gender;
        $this->kotaLahir = $dataPegawai->kotaLahir;
        $this->tglLahir = date('d-M-Y', strtotime($dataPegawai->tglLahir));
        $this->ktp = $dataPegawai->ktp;
        $this->npwp = $dataPegawai->npwp;
    }

    public function storePegawai()
    {
        $this->validate();
        $dataInput = (object)[
            'idPegawai'=>$this->idPegawai,
            'kode'=>$this->kode,
            'nama'=>$this->nama,
            'alamat'=>$this->alamat,
            'kota'=>$this->kota,
            'gender'=>$this->gender,
            'kotaLahir'=>$this->kotaLahir,
            'tglLahir'=>date('Y-m-d', strtotime($this->tglLahir)),
            'ktp'=>$this->ktp,
            'npwp'=>$this->ktp,
        ];
        $store = (new PegawaiRepository())->updateOrCreatePegawai($dataInput);
        $this->successMessage = 'data berhasil disimpan';
        $this->emit('pegawaiStore');
    }

    public function destroyPegawai($id)
    {
        $delete = (new PegawaiRepository())->deletePegawai($id);
    }
}
