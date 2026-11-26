<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetaniDashboardController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.proses');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', fn() => redirect(strtolower(trim((string) auth()->user()->role)) === 'admin' ? route('admin.dashboard') : route('petani.dashboard')))
    ->middleware('auth')->name('dashboard');

// ===== Petani (HARUS di atas admin, supaya /petani/dashboard tidak tertangkap resource petani) =====
Route::middleware(['auth', RoleMiddleware::class . ':petani'])->prefix('petani')->group(function () {
    Route::get('/dashboard', [PetaniDashboardController::class, 'index'])->name('petani.dashboard');
    Route::get('/kebun', [PetaniDashboardController::class, 'kebun'])->name('petani.kebun');
    Route::get('/laporan', [PetaniDashboardController::class, 'laporan'])->name('petani.laporan');
    $f = \App\Http\Controllers\PetaniFiturController::class;
    Route::get('/harga-tbs', [$f, 'hargaTbs'])->name('petani.harga');
    Route::get('/harga-tbs/data', [$f, 'hargaData'])->name('petani.harga.data');
    Route::get('/keuangan', [$f, 'keuangan'])->name('petani.keuangan');
    Route::post('/keuangan', [$f, 'keuanganStore'])->name('petani.keuangan.store');
    Route::delete('/keuangan/{biaya}', [$f, 'keuanganDestroy'])->name('petani.keuangan.destroy');
    Route::get('/jadwal', [$f, 'jadwal'])->name('petani.jadwal');
    Route::post('/jadwal', [$f, 'jadwalStore'])->name('petani.jadwal.store');
    Route::patch('/jadwal/{jadwal}', [$f, 'jadwalToggle'])->name('petani.jadwal.toggle');
    Route::delete('/jadwal/{jadwal}', [$f, 'jadwalDestroy'])->name('petani.jadwal.destroy');
    Route::get('/edukasi', [$f, 'edukasi'])->name('petani.edukasi');
    Route::get('/notifikasi', [$f, 'notifikasi'])->name('petani.notifikasi');
    $a = \App\Http\Controllers\PetaniAksiController::class;
    Route::get('/kebun/tambah', [$a, 'kebunCreate'])->name('petani.kebun.create');
    Route::post('/kebun/tambah', [$a, 'kebunStore'])->name('petani.kebun.store');
    Route::get('/panen/tambah', [$a, 'panenCreate'])->name('petani.panen.create');
    Route::post('/panen/tambah', [$a, 'panenStore'])->name('petani.panen.store');
    Route::get('/profil', [$a, 'profil'])->name('petani.profil');
    Route::post('/profil', [$a, 'profilUpdate'])->name('petani.profil.update');
    Route::post('/profil/foto', [$a, 'fotoUpdate'])->name('petani.profil.foto');
    Route::delete('/profil/foto', [$a, 'fotoHapus'])->name('petani.profil.foto.hapus');
    Route::put('/profil/password', [$a, 'passwordUpdate'])->name('petani.profil.password');
});

// ===== Admin =====
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    foreach (['petani' => 'PetaniController', 'kebun' => 'KebunController', 'panen' => 'PanenController', 'harga-tbs' => 'HargaTbsController'] as $uri => $ctrl) {
        $kelas = "App\\Http\\Controllers\\$ctrl";
        if (class_exists($kelas)) {
            Route::resource($uri, $kelas);
        }
    }
});

