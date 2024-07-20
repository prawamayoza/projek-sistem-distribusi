<?php

use App\Http\Controllers\DataSetController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();
Route::resource('user', UserController::class);
Route::resource('pelanggan', PelangganController::class);
Route::resource('pesanan', PesananController::class);
Route::resource('kendaraan', KendaraanController::class);
Route::resource('data-set', DataSetController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
