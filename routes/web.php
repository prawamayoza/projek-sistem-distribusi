<?php

use App\Http\Controllers\DataSetController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\UserController;
use App\Exports\PesananExport;
use Maatwebsite\Excel\Facades\Excel;
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
Route::get('/saving-matrix/{distribusiId}', [DataSetController::class, 'perhitungan'])->name('saving_matrix.show');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('export-pesanan', function () {
    return Excel::download(new PesananExport, 'pesanan.xlsx');
})->name('pesanan.export');
Route::post('/data-set/{id}/update-status', [DataSetController::class, 'updateStatus'])->name('data-set.update-status');
Route::patch('/kendaraan/{id}/changeStatus', [KendaraanController::class, 'changeStatus'])->name('kendaraan.changeStatus');
Route::get('export-nearest-neighbors/{id}', [DataSetController::class, 'exportNearestNeighbors'])->name('export.nearest.neighbors');
Route::get('/export-nearest-neighbors', [DataSetController::class, 'exportdistribusi'])->name('export.distribusi');