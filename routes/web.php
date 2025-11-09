<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::resource('kategori', KategoriController::class);
Route::resource('layanan', LayananController::class);
Route::resource('transaksi', TransaksiController::class);
Route::resource('pelanggan', PelangganController::class);

// Laporan Routes
Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('laporan/filter', [LaporanController::class, 'filter'])->name('laporan.filter');
Route::get('laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.pdf');
Route::get('laporan/harian-pdf', [LaporanController::class, 'harianPDF'])->name('laporan.harian.pdf');
