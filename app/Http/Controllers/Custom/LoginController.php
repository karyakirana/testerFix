<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ClosedCash;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle Closed Cashed after login to session
     * @param number ID USER
     * @return string Active Closed id
     */
    public function ClosedCash($idUser)
    {
        $data = ClosedCash::whereNull('closed')->latest()->first();
        if ($data) {
            // jika null maka buat data
            return $data->active;
        }
        $generateClosedCash = md5(now());
        $isi = [
            'active' => $generateClosedCash,
            'idUser' => $idUser,
        ];
        $createData = ClosedCash::create($isi);
        return $generateClosedCash;

    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // check and add session closed cash
        $idUser = Auth::user()->id;
        $request->session()->put('ClosedCash', $this->ClosedCash($idUser));

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
