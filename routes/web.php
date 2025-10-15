<?php

use App\Http\Controllers\PackageController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});


Route::resource('package', PackageController::class);
Route::resource('layanan', LayananController::class);
Route::resource('transaksi', TransaksiController::class);
Route::resource('pelanggan', PelangganController::class);
