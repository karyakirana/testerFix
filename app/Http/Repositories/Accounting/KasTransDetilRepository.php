<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\KasTransDetil;

class KasTransDetilRepository
{
    public static function setFromKas($kasId, $dataKasTempDetil)
    {
        KasTransDetil::create([
            'kas_id'=>$kasId,
            'account_id'=>$dataKasTempDetil->account_id,
            'debet'=>$dataKasTempDetil->debet ?? 0,
            'kredit'=>$dataKasTempDetil->kredit ?? 0,
            'keterangan'=>$dataKasTempDetil->keterangan
        ]);
    }
}
