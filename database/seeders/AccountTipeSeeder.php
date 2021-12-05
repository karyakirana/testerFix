<?php

namespace Database\Seeders;

use App\Models\Accounting\TipeAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountTipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipeAccount::truncate();
        $tipeAccount = array(
            [
                'tipe'=> Str::ucfirst('Kas/Bank')
            ],[
                'tipe'=> Str::ucfirst('Akun Piutang')
            ],[
                'tipe'=> Str::ucfirst('Akun Piutang Internal')
            ],[
                'tipe'=> Str::ucfirst('Persediaan')
            ],[
                'tipe'=> Str::ucfirst('Aktiva Lancar Lainnya')
            ],[
                'tipe'=> Str::ucfirst('Aktiva Tetap')
            ],[
                'tipe'=> Str::ucfirst('Akumulasi Penyusutan')
            ],[
                'tipe'=> Str::ucfirst('Aktiva Lainnya')
            ],[
                'tipe'=> Str::ucfirst('Akun Hutang')
            ],[
                'tipe'=> Str::ucfirst('Hutang Lancar Lainnya')
            ],[
                'tipe'=> Str::ucfirst('Hutang Jangka Panjang')
            ],[
                'tipe'=> Str::ucfirst('Ekuitas')
            ],[
                'tipe'=> Str::ucfirst('Pendapatan')
            ],[
                'tipe'=> Str::ucfirst('Pendapatan Lain-lain')
            ],[
                'tipe'=> Str::ucfirst('Harga Pokok Penjualan')
            ],[
                'tipe'=> Str::ucfirst('Beban')
            ],[
                'tipe'=> Str::ucfirst('Beban Lain-lain')
            ],
        );
        foreach ($tipeAccount as $row){
            TipeAccount::create([
                'tipe'=>$row['tipe']
            ]);
        }
    }
}
