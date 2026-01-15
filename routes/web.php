<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
	return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');


Route::prefix('admin')->middleware('auth')->group(function () {

	// Rute yang bisa diakses oleh Kasir & Owner
	Route::middleware(['role.access:kasir,owner'])->group(function () {
		Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
		Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
		Route::post('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
		Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
		Route::resource('pelanggan', PelangganController::class);
		Route::resource('transaksi', TransaksiController::class);
	});

	// Rute Khusus Owner
	Route::middleware(['role.access:owner'])->group(function () {
		Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
		Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
		Route::post('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
		Route::resource('kategori', KategoriController::class);
		Route::resource('layanan', LayananController::class);
		Route::resource('user', UserController::class);
		Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
		Route::get('laporan/filter', [LaporanController::class, 'filter'])->name('laporan.filter');
		Route::get('laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.pdf');
		Route::get('laporan/harian-pdf', [LaporanController::class, 'harianPDF'])->name('laporan.harian.pdf');
	});
});
