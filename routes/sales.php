<?php

use Illuminate\Support\Facades\Route;

Route::get('/sales/list', [\App\Http\Controllers\Sales\SalesController::class, 'index']);
