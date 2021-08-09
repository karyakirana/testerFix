<?php

namespace App\Http\Datatables;

use App\Models\Stock\StockKeluar;
use App\Models\Stock\StockKeluarDetil;
use App\Models\Stock\StockMasukDetil;
use Yajra\DataTables\DataTables;

class StockDetilTable {

    private function action($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('produk', function ($row){
                $produk = $row->produk->nama_produk ?? '';
                $cover = $row->produk->cover.'-' ?? '';
                $kat_harga = $row->produk->kategoriHarga->nama_kat.'-' ?? '';
                $kode_lokal = $row->produk->kode_lokal ?? '';
                return $produk.'<br>'.$cover.$kat_harga;
            })
            ->rawColumns(['produk'])
            ->make(true);
    }

    public function stockKeluarDetil($stockKeluarId)
    {
        $data = StockKeluarDetil::with('produk')->where('stock_keluar', $stockKeluarId)->latest()->get();
        return $this->action($data);
    }

    public function stockMasukDetil($stockMasukId)
    {
        $data = StockMasukDetil::with('produk')->where('idStockMasuk', $stockMasukId)->latest()->get();
        return $this->action($data);
    }
}
