<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){

    // Kasir
    Route::get('kasir/pemasukan/', [\App\Http\Controllers\Accounting\KasirPemasukanController::class, 'index'])->name('kasir.pemasukan');
    Route::patch('kasir/pemasukan/', [\App\Http\Controllers\Accounting\KasirPemasukanController::class, 'listData']);
    Route::get('kasir/pemasukan/transaksi', [\App\Http\Controllers\Accounting\KasirPemasukanController::class, 'create'])->name('kasir.pemasukan.transaksi');

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
    Route::get('accounting/master/account/{id}', [\App\Http\Controllers\Accounting\AccountController::class, 'edit']);

    // account-sub
    Route::get('accounting/master/accountsub', [\App\Http\Controllers\Accounting\AccountSubController::class, 'index'])->name('accountingSubAccount');
    Route::patch('accounting/master/accountsub', [\App\Http\Controllers\Accounting\AccountSubController::class, 'listData']);
    Route::post('accounting/master/accountsub', [\App\Http\Controllers\Accounting\AccountSubController::class, 'store']);
    Route::get('accounting/master/accountsub/{id}', [\App\Http\Controllers\Accounting\AccountSubController::class, 'edit']);

    // journal ref
    Route::get('accounting/master/jurnalref', [\App\Http\Controllers\Accounting\JournalRefController::class, 'index'])->name('accountingJournalRef');

    // journal pembayaran nota
//    Route::get('kasir/pembayarannota', [\App\Http\Controllers\Accounting\JurnalPembayaranNotaController::class, 'index'])->name('jurnalPembayaranNota');
    Route::get('kasir/pembayarannota/baru', [\App\Http\Controllers\Accounting\JurnalPembayaranNotaController::class, 'create'])->name('jurnalPembayaranNotaBaru');

    Route::get('kasir/nota/', [\App\Http\Controllers\Kasir\PaymentController::class, 'notaPenjualan'])->name('kasir.nota');
    Route::get('kasir/nota/tempo', \App\Http\Livewire\Kasir\NotaPenjualanTempo::class)->name('kasir.nota.tempo');
    Route::get('kasir/nota/cash', [\App\Http\Controllers\Kasir\PaymentController::class, 'notaPenjualanCash'])->name('kasir.nota.cash');
    Route::get('kasir/nota/piutang', [\App\Http\Controllers\Kasir\PaymentController::class, 'notaPenjualanBelumBayar'])->name('kasir.nota.piutang');
    Route::get('kasir/payment/tambahbiaya/{id}', [\App\Http\Controllers\Kasir\PaymentController::class, 'transaksiTambahanBiayaPenjualan']);
    Route::get('kasir/payment/tambahbiaya/{id}/edit', [\App\Http\Controllers\Kasir\PaymentController::class, 'tambahanBiayaPenjualan']);

    Route::get('/kasir/payment/cash/{id}', [\App\Http\Controllers\Kasir\PaymentController::class, 'pembayaranPenjualanCash']);

    // piutang
    Route::get('kasir/piutang/penjualan', []);

});
