<?php

use Illuminate\Support\Facades\Route;

Route::get('/sales/list', [\App\Http\Controllers\Sales\SalesController::class, 'index'])->name('daftarSales');
Route::post('/sales/list', [\App\Http\Controllers\Sales\SalesController::class, 'store']);
Route::put('/sales/list', [\App\Http\Controllers\Sales\SalesController::class, 'update']);
Route::get('/sales/list/{id}', [\App\Http\Controllers\Sales\SalesController::class, 'show']);
Route::get('/sales/new', [\App\Http\Controllers\Sales\SalesController::class, 'create'])->name('salesNew');
Route::get('/sales/edit/{id}', [\App\Http\Controllers\Sales\SalesController::class, 'edit']);
Route::get('/sales/print/{id}', [\App\Http\Controllers\Sales\SalesController::class, 'show']);
Route::delete('/sales/edit/{id}', [\App\Http\Controllers\Sales\SalesController::class, 'destroy']);

// temp transaction
Route::post('sales/temp/', [\App\Http\Controllers\Sales\DetilTempController::class, 'store']);
Route::get('sales/temp/{id}', [\App\Http\Controllers\Sales\DetilTempController::class, 'edit']);
Route::delete('sales/temp/{id}', [\App\Http\Controllers\Sales\DetilTempController::class, 'destroy']);

// printing
Route::get('/sales/print/{id}', [\App\Http\Controllers\Sales\ReceiptController::class, 'printRaw']);
