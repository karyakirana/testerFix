<?php

namespace App\View\Components;

use App\Http\Repositories\Accounting\MasterAccountingRepository;
use Illuminate\View\Component;

class SelectAccountingAccount extends Component
{
    public $dataAccount;
    public $kategoriAccount;
    public $idKategori;
    public $deskripsiKategori;

    public function __construct($deskripsiKategori = null, $idKategori = null)
    {
        $this->deskripsiKategori = $deskripsiKategori;
        $this->idKategori = $idKategori;
        $this->dataAccount = (new MasterAccountingRepository())->getAccountJoinKategori($this->deskripsiKategori, $this->idKategori);
    }

    public function render()
    {
        return view('components.nano.select-accounting-account');
    }
}
