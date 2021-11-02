<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function (){

    Route::get('master/kategori/produk', [\App\Http\Controllers\Master\KategoriController::class, 'index'])->name('kategoriProduk');
    Route::post('master/kategori/produk', [\App\Http\Controllers\Master\KategoriController::class, 'store']);
    Route::get('master/kategori/produk/{id}', [\App\Http\Controllers\Master\KategoriController::class, 'edit']);
    Route::delete('master/kategori/produk/{id}', [\App\Http\Controllers\Master\KategoriController::class, 'destroy']);

    Route::get('master/kategori/harga', [\App\Http\Controllers\Master\KategoriHargaController::class, 'index'])->name('kategoriHarga');
    Route::post('master/kategori/harga', [\App\Http\Controllers\Master\KategoriHargaController::class, 'store']);
    Route::get('master/kategori/harga/{id}', [\App\Http\Controllers\Master\KategoriHargaController::class, 'edit']);
    Route::delete('master/kategori/harga/{id}', [\App\Http\Controllers\Master\KategoriHargaController::class, 'destroy']);

    Route::get('/master/produk/list', [\App\Http\Controllers\Master\ProdukController::class, 'index'])->name('produkList');
    Route::patch('/master/produk/list', [\App\Http\Controllers\Master\ProdukController::class, 'listData']);
    Route::post('/master/produk/list', [\App\Http\Controllers\Master\ProdukController::class, 'store']);
    Route::get('/master/produk/list/{id}', [\App\Http\Controllers\Master\ProdukController::class, 'edit']);
    Route::delete('/master/produk/list/{id}', [\App\Http\Controllers\Master\ProdukController::class, 'destroy'])->name('produkAction');

    Route::get('/master/jenissupplier', [\App\Http\Controllers\Master\JenisSupplierController::class, 'index'])->name('jenisSupplierList');
    Route::post('/master/jenissupplier', [\App\Http\Controllers\Master\JenisSupplierController::class, 'store']);
    Route::get('/master/jenissupplier/{id}', [\App\Http\Controllers\Master\JenisSupplierController::class, 'edit']);
    Route::delete('/master/jenissupplier/{id}', [\App\Http\Controllers\Master\JenisSupplierController::class, 'destroy']);

    Route::get('master/supplier', [\App\Http\Controllers\Master\SupplierController::class, 'index'])->name('supplier');
    Route::post('master/supplier', [\App\Http\Controllers\Master\SupplierController::class, 'store']);
    Route::get('master/supplier/{id}', [\App\Http\Controllers\Master\SupplierController::class, 'edit']);
    Route::delete('master/supplier/{id}', [\App\Http\Controllers\Master\SupplierController::class, 'destroy']);

    Route::get('master/customer', [\App\Http\Controllers\Master\CustomerController::class, 'index'])->name('customer');
    Route::post('master/customer', [\App\Http\Controllers\Master\CustomerController::class, 'store']);
    Route::get('master/customer/{id}', [\App\Http\Controllers\Master\CustomerController::class, 'edit']);
    Route::delete('master/customer/{id}', [\App\Http\Controllers\Master\CustomerController::class, 'destroy']);

    Route::get('master/pegawai', [\App\Http\Controllers\Master\PegawaiController::class, 'index'])->name('pegawai');

});
