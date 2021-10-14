<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\Ledger;

class LedgerRepository
{
    public static function setData($dataLedger)
    {
        return Ledger::create([
            'journal_id'=>$dataLedger->hournal_id,
            'activeCash'=>session('ClosedCash'),
            'tgl_buat'=>$dataLedger->tgl,
            'journal_ref'=>$dataLedger->jurnal,
            'debet'=>$dataLedger->debet ?? 0,
            'kredit'=>$dataLedger->debet ?? 0,
            'account_id'=>$dataLedger->account_id
        ]);
    }
}
