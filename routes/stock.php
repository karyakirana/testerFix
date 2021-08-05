<?php

use Illuminate\Support\Facades\Route;

Route::get('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'index']);
Route::get('/stock/new', [\App\Http\Controllers\Stock\StockKeluarController::class, 'create'])->name('stockKeluarNew');
