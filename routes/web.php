<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [\App\Http\Controllers\Custom\LoginController::class, 'index'])
    ->middleware('guest')
    ->name('login');

Route::post('login', [\App\Http\Controllers\Custom\LoginController::class, 'store']);
Route::get('logout', [\App\Http\Controllers\Custom\LoginController::class, 'destroy'])->name('logout');

Route::get('/metronics', function (){
    return view('pages.dashboard');
})->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//require __DIR__.'/auth.php';
require __DIR__.'/sales.php';
require __DIR__.'/master.php';
require __DIR__.'/stock.php';
require __DIR__.'/datatablesRoute.php';
require __DIR__.'/accounting.php';
