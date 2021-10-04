<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){

    // Stock Keluar
    Route::get('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'index'])->name('stokKeluar');
    Route::post('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'store']);
    Route::put('/stock/keluar', [\App\Http\Controllers\Stock\StockKeluarController::class, 'update']);
    Route::get('/stock/keluar/edit/{id}', [\App\Http\Controllers\Stock\StockKeluarController::class, 'edit']);
    Route::get('/stock/keluar/new', [\App\Http\Controllers\Stock\StockKeluarController::class, 'create'])->name('stockKeluarNew');

    Route::get('/stock/keluar/coba', [\App\Http\Controllers\Stock\StockKeluarController::class, 'stockKeluarToReal']);

    // Stock Masuk
    Route::get('/stock/masuk', [\App\Http\Controllers\Stock\StockMasukController::class, 'index'])->name('stokMasuk');
    Route::post('/stock/masuk', [\App\Http\Controllers\Stock\StockMasukController::class, 'store']);
    Route::put('/stock/masuk', [\App\Http\Controllers\Stock\StockMasukController::class, 'update']);
    Route::get('/stock/masuk/new', [\App\Http\Controllers\Stock\StockMasukController::class, 'create'])->name('stockMasukNew');
    Route::get('/stock/masuk/edit/{id}', [\App\Http\Controllers\Stock\StockMasukController::class, 'edit']);

    Route::get('stock/masuk/coba', [\App\Http\Controllers\Stock\StockMasukController::class, 'stockMasukToReal']);

    // Stock Akhir
    Route::get('/stock/akhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'index'])->name('stockAkhir');
    Route::post('/stock/akhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'store']);
    Route::put('/stock/akhir', [\App\Http\Controllers\Stock\StockAkhirController::class, 'update']);
    Route::get('/stock/akhir/new', [\App\Http\Controllers\Stock\StockAkhirController::class, 'create'])->name('stockAkhirNew');
    Route::get('/stock/akhir/edit/{id}', [\App\Http\Controllers\Stock\StockAkhirController::class, 'edit']);
    Route::get('/stock/akhir/edit/{id}', [\App\Http\Controllers\Stock\StockAkhirController::class, 'destroy']);
    Route::get('/stock/akhir/detil', [\App\Http\Controllers\Stock\StockAkhirController::class, 'show'])->name('stockAkhirDetil');
    Route::get('/stock/akhir/detil/{id}', [\App\Http\Controllers\Stock\StockAkhirController::class, 'stockByBranch']);

    Route::get('stock/akhir/coba', [\App\Http\Controllers\Stock\StockAkhirController::class, 'stockAkhirToReal']);

    // Stock Order
    Route::get('/stock/order', [\App\Http\Controllers\Stock\StockOrderController::class, 'index'])->name('stockOrder');
    Route::post('/stock/order', [\App\Http\Controllers\Stock\StockOrderController::class, 'store']);
    Route::put('/stock/order', [\App\Http\Controllers\Stock\StockOrderController::class, 'update']);
    Route::get('/stock/order/new', [\App\Http\Controllers\Stock\StockOrderController::class, 'create'])->name('stockOrderNew');
    Route::get('/stock/order/edit/{id}', [\App\Http\Controllers\Stock\StockOrderController::class, 'edit']);
    Route::delete('/stock/order/edit/{id}', [\App\Http\Controllers\Stock\StockOrderController::class, 'destroy']);

    // Stock Order Report
    Route::get('/stock/order/receipt/{id}', [\App\Http\Controllers\Stock\StockOrderController::class, 'print']);

    // Stock Mutasi
    Route::get('/stock/mutasi', [\App\Http\Controllers\Stock\MutasiGudangController::class, 'index'])->name('mutasiStock');
    Route::post('/stock/mutasi', [\App\Http\Controllers\Stock\MutasiGudangController::class, 'store']);
    Route::put('/stock/mutasi', [\App\Http\Controllers\Stock\MutasiGudangController::class, 'update']);
    Route::get('/stock/mutasi/new', [\App\Http\Controllers\Stock\MutasiGudangController::class, 'create'])->name('mutasiGudangNew');
    Route::get('/stock/mutasi/edit/{id}', [\App\Http\Controllers\Stock\MutasiGudangController::class, 'edit']);


    // Stock Real
    Route::get('/stock/real', [\App\Http\Controllers\Stock\StockRekonsiliasiController::class, 'index']);
    Route::get('/stock/real/branch/{id}', [\App\Http\Controllers\Stock\StockRekonsiliasiController::class, 'indexyByBranch']);
    Route::patch('/stock/real/out/branch/{produk}/{branch}', [\App\Http\Controllers\Stock\StockRekonsiliasiController::class, 'stockKeluarByProduk']);
    Route::patch('/stock/real/opname/branch/{produk}/{branch}', [\App\Http\Controllers\Stock\StockRekonsiliasiController::class, 'stockOpnameByProduk']);
    Route::patch('/stock/real/in/branch/{produk}/{branch}', [\App\Http\Controllers\Stock\StockRekonsiliasiController::class, 'stockMasukByProduk']);

    // Stock Rusak
    Route::get('/stock/real/rusak', [\App\Http\Controllers\Stock\InventoryRusakController::class, 'index']);
    Route::get('/stock/real/rusak/{id}', [\App\Http\Controllers\Stock\InventoryRusakController::class, 'byBranch']);

    // Stock Temp Trans
    Route::post('/stock/temp', [\App\Http\Controllers\Stock\StockTempController::class, 'store']);
    Route::get('/stock/temp/{id}', [\App\Http\Controllers\Stock\StockTempController::class, 'edit']);
    Route::delete('/stock/temp/{id}', [\App\Http\Controllers\Stock\StockTempController::class, 'destroy']);

    // Stock generate
    Route::post('/generate/penjualan/to/stock', [\App\Http\Controllers\Generator\GenerateSalesToStockController::class, 'store'])->name('generateSalesToStock');

});


