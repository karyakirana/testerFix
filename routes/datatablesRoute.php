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

// temporary
Route::post('data/penjualan/trans/{id}', [\App\Http\Datatables\SalesTransTable::class, 'detilTemp'])->name('detilTemp');

