<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterAccountController extends Controller
{
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
}
