<?php

namespace App\Http\Repositories\Master;

use App\Models\Master\Pegawai;
use Illuminate\Support\Facades\Auth;

class PegawaiRepository
{
    public function getPegawaiById($id)
    {
        return Pegawai::find($id);
    }

    public function getPegawaiAll()
    {
        return Pegawai::paginate(10);
    }

    public function kodepegawai()
    {
        $idPegawai = Pegawai::orderBy('kode', 'desc')->first();
        $num = null;
        if(!$idPegawai)
        {
            $num = 1;
        } else {
            $urutan = (int) substr($idPegawai->kode, 1, 5);
            $num = $urutan + 1;
        }
        return "P".sprintf("%05s", $num);
    }

    public function updateOrCreatePegawai($dataPegawai)
    {
        return Pegawai::updateOrCreate(
            ['id'=>$dataPegawai->id],
            [
            'kode'=>$dataPegawai->kode ?? $this->kodepegawai(),
            'nama'=>$dataPegawai->nama,
            'alamat'=>$dataPegawai->alamat,
            'kota'=>$dataPegawai->kota,
            'gender'=>$dataPegawai->gender,
            'kotaLahir'=>$dataPegawai->kotaLahir,
            'tglLahir'=>$dataPegawai->tglLahir,
            'ktp'=>$dataPegawai->ktp,
            'npwp'=>$dataPegawai->npwp,
            'users'=>Auth::id()
        ]);
    }

    public function deletePegawai($id)
    {
        return Pegawai::destroy($id);
    }
}
