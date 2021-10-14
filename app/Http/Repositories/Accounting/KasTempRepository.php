<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\KasTemp;
use Illuminate\Support\Facades\Auth;

class  KasTempRepository
{
    protected function lastSession($jeniskasTemp)
    {
        return KasTemp::where('activeCash', session('ClosedCash'))
            ->where('jenis', $jeniskasTemp)
            ->whereNull('transaction_id');
    }

    protected function createKasTemp($jenisKasTemp, $transactionId=null)
    {
        return KasTemp::create([
            'user_id'=>Auth::id(),
            'transaction_id'=>$transactionId,
            'jenis'=>$jenisKasTemp
        ]);
    }

    public function createSession($jeniskasTemp)
    {
        $lastSession = $this->lastSession($jeniskasTemp);
        if ($lastSession->get()->count() > 0)
        {
            $id = $lastSession->first()->id;
        } else
        {
            $id = $this->createKasTemp($jeniskasTemp)->id;
        }
        session([$jeniskasTemp=>$id]);
        return $id;
    }

    public function editKasTemp($transactionId, $jenisTemp)
    {
        $getKasTemp = KasTemp::where('transactionId', $transactionId)
            ->where('jenis', $jenisTemp);
        if ($getKasTemp->exist())
        {
            $data = $getKasTemp->first();
        }
        else
        {
            $data = $this->createKasTemp($jenisTemp, $transactionId);
        }
        return $data;
    }

    public static function destroyKasTemp($jeniskasTemp)
    {
        session()->forget($jeniskasTemp);
    }
}
