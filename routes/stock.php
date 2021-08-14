<?php

use Illuminate\Support\Facades\Route;

// Stock Keluar
Route::get('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'index'])->name('stokKeluar');
Route::post('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'store']);
Route::put('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'update']);
Route::get('/stock/keluar/edit/{id}', [\App\Http\Controllers\Stock\StockKeluarController::class, 'edit']);
Route::get('/stock/keluar/new', [\App\Http\Controllers\Stock\StockKeluarController::class, 'create'])->name('stockKeluarNew');

// Stock Masuk
Route::get('/stock/masuk', [\App\Http\Controllers\Stock\StockMasukController::class, 'index'])->name('stokMasuk');
Route::post('/stock/masuk', [\App\Http\Controllers\Stock\StockMasukController::class, 'store']);
Route::put('/stock/masuk', [\App\Http\Controllers\Stock\StockMasukController::class, 'update']);
Route::get('/stock/masuk/new', [\App\Http\Controllers\Stock\StockMasukController::class, 'create'])->name('stockMasukNew');
Route::get('/stock/masuk/edit/{id}', [\App\Http\Controllers\Stock\StockMasukController::class, 'edit']);

// Stock Akhir
Route::get('/stock/akhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'index'])->name('stockAkhir');
Route::post('/stock/akhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'store']);
Route::put('/stock/akhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'update']);
Route::get('/stock/akhir/new', [\App\Http\Controllers\Stock\StockAkhirController::class, 'create'])->name('stockAkhirNew');
Route::get('/stock/akhir/edit/{id}', [\App\Http\Controllers\Stock\StockAkhirController::class, 'edit']);
Route::get('/stock/akhir/edit/{id}', [\App\Http\Controllers\Stock\StockAkhirController::class, 'destroy']);
Route::get('/stock/akhir/detil', [\App\Http\Controllers\Stock\StockAkhirController::class, 'show'])->name('stockAkhirDetil');

// Stock Real
Route::get('/stock/real', [\App\Http\Controllers\Stock\StockRekonsiliasiController::class, 'index']);
Route::get('/stock/real/branch/{id}', [\App\Http\Controllers\Stock\StockRekonsiliasiController::class, 'indexyByBranch']);

// Stock Temp Trans
Route::post('/stock/temp', [\App\Http\Controllers\Stock\StockTempController::class, 'store']);
Route::get('/stock/temp/{id}', [\App\Http\Controllers\Stock\StockTempController::class, 'edit']);
Route::delete('/stock/temp/{id}', [\App\Http\Controllers\Stock\StockTempController::class, 'destroy']);

// Stock generate
Route::post('generate/penjualan/to/stock', [\App\Http\Controllers\Generator\GenerateSalesToStockController::class, 'store'])->name('generateSalesToStock');