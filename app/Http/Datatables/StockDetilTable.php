<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockKeluar;
use Yajra\DataTables\DataTables;

class StockDetilTable {

    private function action($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat ?? '';
                return $produk.'<br>'.$cover.'-'.$kat_harga;
            })
            ->make(true);
    }

    public function stockKeluarDetil($stockKeluarId)
    {
        $data = StockKeluar::with('produk')->where('stock_keluar')->latest()->get();
        return $this->action();
    }
}
