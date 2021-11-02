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

    public function storePegawai()
    {
        $this->validate();
        $dataInput = (object)[
            'kode'=>$this->kode,
            'nama'=>$this->nama,
            'alamat'=>$this->alamat,
            'kota'=>$this->kota,
            'gender'=>$this->gender,
            'kotaLahir'=>$this->kotaLahir,
            'tglLahir'=>$this->kotaLahir,
            'tgllahir'=>$this->tglLahir,
            'ktp'=>$this->ktp,
            'npwp'=>$this->ktp,
        ];
        $store = (new PegawaiRepository())->updateOrCreatePegawai($dataInput);
    }
}
