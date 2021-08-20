<?php

use Illuminate\Support\Facades\Route;

// select2
Route::post('select2/kategori', [\App\Http\Select2\KategoriSelect::class, 'kategori'])->name('select2Kategori');
Route::post('select2/kategoriharga', [\App\Http\Select2\KategoriHargaSelect::class, 'kategoriHarga'])->name('select2KategoriHarga');
Route::post('select2/jenissupplier', [\App\Http\Select2\JenisSupplierSelect::class, 'jenisSupplier'])->name('select2JenisSupplier');

// Datatables
Route::patch('/data/produk/list', [\App\Http\Datatables\ProdukTable::class, 'list']);
Route::patch('/data/produk/listcrud', [\App\Http\Datatables\ProdukTable::class, 'listCrud'])->name('produkcrud');

Route::patch('data/jenissupplier', [\App\Http\Datatables\JenisSupplierTable::class, 'list']);
Route::patch('data/supplier/list', [\App\Http\Datatables\SupplierTable::class, 'list']);
Route::patch('data/supplier/listcrud', [\App\Http\Datatables\SupplierTable::class, 'listCrud'])->name('suppliercrud');

Route::patch('data/kategori/produk', [\App\Http\Datatables\KategoriProdukTable::class, 'list']);
Route::patch('data/kategori/harga', [\App\Http\Datatables\KategoriHargaTable::class, 'list']);

Route::post('data/customer', [\App\Http\Datatables\CustomerTable::class, 'list']);
Route::patch('data/customer', [\App\Http\Datatables\CustomerTable::class, 'listcrud']);

Route::patch('data/penjualan', [\App\Http\Datatables\SalesTransTable::class, 'penjualanList'])->name('penjualanList');
Route::patch('data/retur/baik', [\App\Http\Datatables\ReturTable::class, 'returBaik'])->name('returBaikList');

Route::patch('data/stock/keluar', [\App\Http\Datatables\StockTable::class, 'stockKeluarList'])->name('stockKeluarList');
Route::patch('data/stock/keluar/detil/{id}', [\App\Http\Datatables\StockDetilTable::class, 'stockKeluarDetil']);

Route::patch('data/stock/masuk', [\App\Http\Datatables\StockTable::class, 'stockMasukList'])->name('stockMasukList');
Route::patch('data/stock/masuk/detil/{id}', [\App\Http\Datatables\StockDetilTable::class, 'stockMasukDetil']);

// stock_akhir
Route::patch('data/stock/akhir', [\App\Http\Datatables\StockTable::class, 'stockAkhirList']);
Route::patch('data/stock/akhir/branch/{id}', [\App\Http\Datatables\StockTable::class, 'stockAkhirList']);
Route::patch('data/stock/akhir/list/{id}', [\App\Http\Datatables\StockTable::class, 'stockAkhirListDetil']);
Route::patch('data/stock/akhir/detil/', [\App\Http\Datatables\StockTable::class, 'stockAkhirDetil']); // all Gudang
Route::patch('data/stock/akhir/detil/{id}', [\App\Http\Datatables\StockTable::class, 'stockAkhirDetil']); // salah satu gudang

// stock_preorder
Route::patch('data/stock/preorder', [\App\Http\Datatables\StockOrderTable::class, 'stockOrderList']);
Route::patch('data/stock/preorder/{id}', [\App\Http\Datatables\StockOrderTable::class, 'stockOrderDetilList']);

// inventory_real
Route::patch('data/stock/real', [\App\Http\Datatables\StockAllTable::class, 'StockAllBranch']);
Route::patch('data/stock/real/branch/{id}', [\App\Http\Datatables\StockAllTable::class, 'StockByBranch']);

// temporary
Route::post('data/penjualan/trans/{id}', [\App\Http\Datatables\SalesTransTable::class, 'detilTemp'])->name('detilTemp');

Route::patch('data/stock/temp/detil/{id}', [\App\Http\Datatables\StockTempTable::class, 'stockTrans']);
