<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountKategoriSub;

class MasterAccountingRepository
{
    public function generateNumberAccount($dataInput)
    {
        $kategoriNumber = $dataInput->kategoriId;
        $kodekategori = AccountKategoriSub::find($kategoriNumber);
        $data = Account::with('accountKategori')
            ->where('kategori_sub_id', $kategoriNumber)
            ->latest('kode_account');
        if ($data->get()->count() > 0) {
            $dataNumber = (int) substr($data->first()->kode_account, '4');
            $number = $dataNumber + 1;
        } else {
            $number = 1;
        }
        $subKategoriNumber = $kodekategori->kode_kategori_sub;
        return $subKategoriNumber.'-'.sprintf('%03s', $number);
    }

    /**
     * ambil data biaya penjualan
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getBiayaPenjualan()
    {
        return Account::with([
            'accountKategori'=>function($query){
                $query->where('deskripsi', 'like', 'biaya penjualan');
            },
        ])
            ->get();
    }
}
