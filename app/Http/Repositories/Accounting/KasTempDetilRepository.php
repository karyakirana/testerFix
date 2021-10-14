<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\KasTempDetil;

class KasTempDetilRepository
{
    public function store($dataTemp)
    {
        return KasTempDetil::updateOrCreate([
            'kas_temp'=>$dataTemp->id,
            'account_id'=>$dataTemp->accoundId,
            'debet'=>$dataTemp->debet ?? 0,
            'kredit'=>$dataTemp->kredit ?? 0,
            'keterangan'=>$dataTemp->keterangan
        ]);
    }

    public static function destroy($idTemp)
    {
        return KasTempDetil::with('kas_temp', $idTemp)->delete();
    }
}
