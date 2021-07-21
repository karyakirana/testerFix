<?php

use Illuminate\Support\Facades\Route;

Route::get('produk/list', [\App\Http\Controllers\Master\ProdukController::class, 'index'])->name('produkList');
Route::post('produk/list', [\App\Http\Controllers\Master\ProdukController::class, 'store']);
Route::get('produk/list/{id}', [\App\Http\Controllers\Master\ProdukController::class, 'edit']);
Route::delete('produk/list/{id}', [\App\Http\Controllers\Master\ProdukController::class, 'edit'])->name('produkAction');
