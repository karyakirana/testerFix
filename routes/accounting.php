<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){

    // kategori
    Route::get('accounting/master/kategori', [\App\Http\Controllers\Accounting\KategoriController::class, 'index'])->name('accountingKategori');

    // kategori-sub
    Route::get('accounting/master/kategorisub', [\App\Http\Controllers\Accounting\KategoriSubController::class, 'index'])->name('accountingSubKategori');

    // account
    Route::get('accounting/master/account', [\App\Http\Controllers\Accounting\AccountController::class, 'index'])->name('accountingAccount');

    // account-sub
    Route::get('accounting/master/accountsub', [\App\Http\Controllers\Accounting\AccountSubController::class, 'index'])->name('accountingSubAccount');

    // journal ref
    Route::get('accounting/master/jurnalref', [\App\Http\Controllers\Accounting\JournalRefController::class, 'index'])->name('accountingJournalRef');

});
