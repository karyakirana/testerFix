<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterAccountController extends Controller
{
    public function index()
    {
        return view('pages.accounting.master-account-index');
    }

    public function kategoriTipe()
    {
        return view('pages.accounting.account-kategori-tipe');
    }

    public function tipeAccount()
    {
        return view('pages.accounting.account-tipe');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function kategori()
    {
        return view('pages.accounting.kategori');
    }

    public function subKategori()
    {
        return view('pages.accounting.kategoriSub');
    }

    public function account()
    {
        return view('pages.accounting.account');
    }

    public function subAccount()
    {
        return view('pages.accounting.accountSub');
    }
}
