<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function (){

    Route::get('/sales/list', [\App\Http\Controllers\Sales\SalesController::class, 'index'])->name('daftarSales');
    Route::post('/sales/list', [\App\Http\Controllers\Sales\SalesController::class, 'store']);
    Route::put('/sales/list', [\App\Http\Controllers\Sales\SalesController::class, 'update']);
    Route::get('/sales/list/{id}', [\App\Http\Controllers\Sales\SalesController::class, 'show']);
    Route::get('/sales/new', [\App\Http\Controllers\Sales\SalesController::class, 'create'])->name('salesNew');
    Route::get('/sales/edit/{id}', [\App\Http\Controllers\Sales\SalesController::class, 'edit']);
    Route::delete('/sales/edit/{id}', [\App\Http\Controllers\Sales\SalesController::class, 'destroy']);

    // Retur Baik
    Route::get('/retur/baik', [\App\Http\Controllers\Sales\SalesReturController::class, 'index'])->name('returBaik');
    Route::put('/retur/baik', [\App\Http\Controllers\Sales\SalesReturController::class, 'update']);
    Route::post('/retur/baik', [\App\Http\Controllers\Sales\SalesReturController::class, 'store']);
    Route::get('/retur/baik/new', [\App\Http\Controllers\Sales\SalesReturController::class, 'create'])->name('returBaikNew');
    Route::get('/retur/baik/edit/{id}', [\App\Http\Controllers\Sales\SalesReturController::class, 'edit']);
    Route::get('/retur/baik/print/{id}', [\App\Http\Controllers\Sales\SalesReturController::class, 'print']);
    Route::delete('/retur/baik/edit/{id}', [\App\Http\Controllers\Sales\SalesReturController::class, 'destroy']);

    // Retur Rusak
    Route::get('/retur/rusak/', [\App\Http\Controllers\Sales\SalesBadReturController::class, 'index'])->name('returRusak');
    Route::put('/retur/rusak/', [\App\Http\Controllers\Sales\SalesBadReturController::class, 'update']);
    Route::post('/retur/rusak/', [\App\Http\Controllers\Sales\SalesBadReturController::class, 'store']);
    Route::get('/retur/rusak/new', [\App\Http\Controllers\Sales\SalesBadReturController::class, 'create'])->name('returRusakNew');
    Route::get('/retur/rusak/edit/{id}', [\App\Http\Controllers\Sales\SalesBadReturController::class, 'create']);
    Route::delete('/retur/rusak/edit/{id}', [\App\Http\Controllers\Sales\SalesBadReturController::class, 'destroy']);

    // temp transaction
    Route::post('sales/temp/', [\App\Http\Controllers\Sales\DetilTempController::class, 'store']);
    Route::get('sales/temp/{id}', [\App\Http\Controllers\Sales\DetilTempController::class, 'edit']);
    Route::delete('sales/temp/{id}', [\App\Http\Controllers\Sales\DetilTempController::class, 'destroy']);

    // printing
    Route::get('/sales/print/{id}', [\App\Http\Controllers\Sales\ReceiptController::class, 'salesReceipt']);
    Route::get('/sales/print/{id}/pdf', [\App\Http\Controllers\Sales\ReceiptController::class, 'salesReceiptPdf']);

    // retur baik printing
    Route::get('/retur/baik/print/{id}', [\App\Http\Controllers\Sales\ReceiptController::class, 'returBaikReceipt'])->name('returBaikReceipt');

});


