<?php

namespace App\Http\Repositories\Sales;

use App\Models\Sales\PenjualanDetilTemp;
use App\Models\Sales\PenjualanTemp;
use Illuminate\Support\Facades\Auth;

class SalesTempRepository
{
    public function newSessionSalesTemp($id = null)
    {
        $createSalesTemp = PenjualanTemp::create([
            'jenisTemp' => 'Penjualan',
            'idSales' => Auth::user()->id,
            'id_jual'=> $id,
        ]);
        if ($id == null){
            session()->put(['penjualan'=>$createSalesTemp->id]);
        }
        return $createSalesTemp;
    }

    public function getSessionSalesTemp($session)
    {
        return PenjualanTemp::find($session);
    }

    public function getLastSalesTemp()
    {
        return PenjualanTemp::whereNull('id_jual')
            ->where('jenisTemp', 'Penjualan')
            ->where('idSales', Auth::id())
            ->latest()
            ->first();
    }

    public function checkSession($jenisTemp, $session = null)
    {
        if ($session)
        {
            $sessionSales = $this->getSessionSalesTemp($session);
            if ($sessionSales->idSales != Auth::id() && $sessionSales->jenisTemp = $jenisTemp)
            {
                $sessionSales = $this->newSessionSalesTemp();
            }
        }
        else
        {
            $sessionSales = $this->getLastSalesTemp();
            if (!$sessionSales)
            {
                $sessionSales = $this->newSessionSalesTemp();
            }
            else
            {
                session()->put(['penjualan'=>$sessionSales->id]);
            }
        }

        return $sessionSales;
    }

    public function checkUpdateSession($jenisTemp, $idMaster)
    {
        //
    }

    public function getTempDetail($idTemp)
    {
        return PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->get();
    }

    public function destroyAllTemp($idTemp)
    {
        // delete detil temp
        PenjualanDetilTemp::where('idPenjualanTemp', $idTemp)->delete();
        // delete temp
        PenjualanTemp::destroy($idTemp);
    }
}
