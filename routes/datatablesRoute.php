<?php

use Illuminate\Support\Facades\Route;

// select2
Route::post('select2/kategori', [\App\Http\Select2\KategoriSelect::class, 'kategori'])->name('select2Kategori');
Route::post('select2/kategoriharga', [\App\Http\Select2\KategoriHargaSelect::class, 'kategoriHarga'])->name('select2KategoriHarga');

Route::patch('/data/produk/list', [\App\Http\Datatables\ProdukTable::class, 'list']);
Route::patch('/data/produk/listcrud', [\App\Http\Datatables\ProdukTable::class, 'listCrud'])->name('produkcrud');
