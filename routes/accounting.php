<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){

    // kategori
    Route::get('accounting/master/kategori', [\App\Http\Controllers\Accounting\KategoriController::class, 'index'])->name('accountingKategori');
    Route::post('accounting/master/kategori', [\App\Http\Controllers\Accounting\KategoriController::class, 'store']);
    Route::patch('accounting/master/kategori', [\App\Http\Controllers\Accounting\KategoriController::class, 'dataList']);
    Route::get('accounting/master/kategori/{id}', [\App\Http\Controllers\Accounting\KategoriController::class, 'edit']);
    Route::delete('accounting/master/kategori/{id}', [\App\Http\Controllers\Accounting\KategoriController::class, 'destroy']);
    Route::put('accounting/master/kategori', [\App\Http\Controllers\Accounting\KategoriController::class, 'select2']);

    // kategori-sub
    Route::get('accounting/master/kategorisub', [\App\Http\Controllers\Accounting\KategoriSubController::class, 'index'])->name('accountingSubKategori');
    Route::post('accounting/master/kategorisub', [\App\Http\Controllers\Accounting\KategoriSubController::class, 'store']);
    Route::patch('accounting/master/kategorisub', [\App\Http\Controllers\Accounting\KategoriSubController::class, 'dataList']);
    Route::get('accounting/master/kategorisub/{id}', [\App\Http\Controllers\Accounting\KategoriSubController::class, 'edit']);

    // account
    Route::get('accounting/master/account', [\App\Http\Controllers\Accounting\AccountController::class, 'index'])->name('accountingAccount');
    Route::patch('accounting/master/account', [\App\Http\Controllers\Accounting\AccountController::class, 'listData']);
    Route::post('accounting/master/account', [\App\Http\Controllers\Accounting\AccountController::class, 'store']);

    // account-sub
    Route::get('accounting/master/accountsub', [\App\Http\Controllers\Accounting\AccountSubController::class, 'index'])->name('accountingSubAccount');

    // journal ref
    Route::get('accounting/master/jurnalref', [\App\Http\Controllers\Accounting\JournalRefController::class, 'index'])->name('accountingJournalRef');

    // journal pembayaran nota
    Route::get('kasir/pembayarannota', [\App\Http\Controllers\Accounting\JurnalPembayaranNotaController::class, 'index'])->name('jurnalPembayaranNota');
    Route::get('kasir/pembayarannota/baru', [\App\Http\Controllers\Accounting\JurnalPembayaranNotaController::class, 'create'])->name('jurnalPembayaranNotaBaru');

});
