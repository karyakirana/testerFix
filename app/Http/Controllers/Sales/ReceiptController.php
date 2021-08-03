<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\Penjualan;
use App\Models\Sales\PenjualanDetil;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function printRaw($id)
    {
        $id_jual = str_replace('-', '/', $id);
        $master = Penjualan::find($id_jual);
        $detil = PenjualanDetil::where('id_jual', $id_jual)->get();
        $data = [
            'master'=>$master,
            'detil'=>$detil
        ];
        return view('pages.print.salesReceipt', $data);
    }
}
