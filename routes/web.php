<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\DashboardController@index');


Route::resource('kategori', KategoriController::class);
Route::resource('layanan', LayananController::class);
Route::resource('transaksi', TransaksiController::class);
Route::resource('pelanggan', PelangganController::class);
