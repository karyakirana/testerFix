<?php

use Illuminate\Support\Facades\Route;

// Stock Keluar
Route::get('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'index'])->name('stokKeluar');
Route::post('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'store']);
Route::put('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'update']);
Route::get('/stock/keluar/edit/{id}', [\App\Http\Controllers\Stock\StockKeluarController::class, 'edit']);
Route::get('/stock/keluar/new', [\App\Http\Controllers\Stock\StockKeluarController::class, 'create'])->name('stockKeluarNew');

// Stock Temp Trans
Route::post('/stock/temp', [\App\Http\Controllers\Stock\StockTempController::class, 'store']);
Route::get('/stock/temp/{id}', [\App\Http\Controllers\Stock\StockTempController::class, 'edit']);
Route::delete('/stock/temp/{id}', [\App\Http\Controllers\Stock\StockTempController::class, 'destroy']);
