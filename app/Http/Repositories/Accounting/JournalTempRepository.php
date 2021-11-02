<?php

namespace App\Http\Repositories\Accounting;

use App\Models\Accounting\JurnalTempDetail;
use App\Models\Accounting\JurnalTempMaster;
use Illuminate\Support\Facades\Auth;

class JournalTempRepository
{
    public function create($jenisJurnal, $jurnalId = null)
    {
        return JurnalTempMaster::create([
            'jenis_jurnal'=>$jenisJurnal,
            'jurnal_id'=>$jurnalId,
            'user_id'=>Auth::id()
        ]);
    }

    public function createNewSession($jenisJurnal)
    {
        // check session
        $sessionJurnal = session($jenisJurnal);
        if (!$sessionJurnal){
            // check last jurnal by user
            $lastJurnalTemp = JurnalTempMaster::where('user_id', Auth::id())
                ->where('jenis_jurnal', $jenisJurnal)
                ->latest()
                ->first();
            if (!$lastJurnalTemp){
                $lastJurnalTemp = $this->create($jenisJurnal);
            }
            session()->put($jenisJurnal, $lastJurnalTemp->id);
        } else {
            $lastJurnalTemp = JurnalTempMaster::find($sessionJurnal);
        }
        return $lastJurnalTemp;
    }

    public function getDetailByMaster($idMaster)
    {
        return JurnalTempDetail::where('journal_id', $idMaster)->get();
    }

    public function storeDetailTemp($dataDetail)
    {
        return JurnalTempDetail::updateOrCreate(
            ['id'=>$dataDetail->id],
            [
                'account_id'=>$dataDetail->accountId,
                'debit'=>$dataDetail->debit,
                'kredit'=>$dataDetail->kredit
            ]
        );
    }

    public function deleteDetailById($idDetail)
    {
        return JurnalTempDetail::destroy($idDetail);
    }

    public function deleteDetailByMaster($idMaster)
    {
        return JurnalTempDetail::where('journal_id')
            ->delete();
    }

    public function deleteMasterDetail($idMaster): bool
    {
        $this->deleteDetailByMaster($idMaster);
        JurnalTempMaster::destroy($idMaster);
        return true;
    }
}
