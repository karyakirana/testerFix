<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\KasTrans;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasTransRepository
{
    /**
     * get data for table list
     * @param null $jenis
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getDataTable($jenis=null)
    {
        $kasTrans = KasTrans::with([])
            ->where('activeCash', session('ClosedCash'));
        if ($jenis)
        {
            return $kasTrans->where('jenis', $jenis)->get();
        }
        else
        {
            return $kasTrans->get();
        }
    }

    public static function getData($id)
    {
        return KasTrans::find($id);
    }

    public static function kode($jenis)
    {
        $dataKas = KasTrans::where('jenis', $jenis)
            ->where('activeCash', session('ClosedCash'))
            ->latest('kode');
        if ($dataKas->exists())
        {
            $plusOne = $dataKas->first()->kode + 1;
        } else
        {
            $plusOne = 1;
        }
        return sprintf("%06s", $plusOne);
    }

    public static function setData($dataKas)
    {
        return KasTrans::create([
            'kode'=>$dataKas->kode,
            'activeCash'=>session('ClosedCash'),
            'tgl'=>$dataKas->tgl,
            'jenis'=>$dataKas->jenis,
            'account_id'=>$dataKas->accountId,
            'user_id'=>Auth::id(),
            'debet'=>$dataKas->debet,
            'kredit'=>$dataKas->kredit,
            'keterangan'=>$dataKas->keterangan
        ]);
    }

    public function setAllData($dataKas, $dataKasTempDetil)
    {
        DB::beginTransaction();
        try {
            // save to KasTrans Model
            $setKasTrans = self::setData($dataKas);

            // save to ledger

            // save detail
            foreach ($dataKasTempDetil as $item)
            {
                // save to KasTransDetil Model
                KasTransDetilRepository::setFromKas($setKasTrans->id, $item);

                // save to Ledger
                LedgerRepository::setData($item);
                // save to Ledger Sub
            }

            DB::commit();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
        }
    }

    public static function updateData($id, $dataKas)
    {
        return KasTrans::where('id', $id)
            ->update([
                'account_id'=>$dataKas->accountId,
                'user_id'=>Auth::id(),
                'debet'=>$dataKas->debet,
                'kredit'=>$dataKas->kredit,
                'keterangan'=>$dataKas->keterangan
            ]);
    }
}
