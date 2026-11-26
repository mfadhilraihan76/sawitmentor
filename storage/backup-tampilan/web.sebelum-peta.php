<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Panel;
use App\Http\Controllers\PetaniAksiController;
use App\Http\Controllers\PetaniDashboardController;
use App\Http\Controllers\PetaniFiturController;
use App\Http\Middleware\PastikanProfilPetani;
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
Route::middleware(['auth', RoleMiddleware::class . ':petani', PastikanProfilPetani::class])
    ->prefix('petani')
    ->name('petani.')
    ->group(function () {
        Route::get('/dashboard', [PetaniDashboardController::class, 'index'])->name('dashboard');

        // ---- Fitur lama yang dipulihkan ----
        // Harga TBS live
        Route::get('harga', [PetaniFiturController::class, 'hargaTbs'])->name('harga');
        Route::get('harga/data', [PetaniFiturController::class, 'hargaData'])->name('harga.data');

        // Keuangan (menu lama): arahkan ke halaman keuangan Panel
        Route::get('keuangan-ringkasan', fn() => redirect()->route('petani.keuangan.index'))->name('keuangan');

        // Jadwal perawatan
        Route::get('jadwal', [PetaniFiturController::class, 'jadwal'])->name('jadwal');
        Route::post('jadwal', [PetaniFiturController::class, 'jadwalStore'])->name('jadwal.store');
        Route::patch('jadwal/{jadwal}/toggle', [PetaniFiturController::class, 'jadwalToggle'])->name('jadwal.toggle');
        Route::delete('jadwal/{jadwal}', [PetaniFiturController::class, 'jadwalDestroy'])->name('jadwal.destroy');

        // Edukasi & notifikasi
        Route::get('edukasi', [PetaniFiturController::class, 'edukasi'])->name('edukasi');
        Route::get('notifikasi', [PetaniFiturController::class, 'notifikasi'])->name('notifikasi');

        // Profil
        Route::get('profil', [PetaniAksiController::class, 'profil'])->name('profil');
        Route::put('profil', [PetaniAksiController::class, 'profilUpdate'])->name('profil.update');
        Route::post('profil/foto', [PetaniAksiController::class, 'fotoUpdate'])->name('profil.foto');
        Route::delete('profil/foto', [PetaniAksiController::class, 'fotoHapus'])->name('profil.foto.hapus');
        Route::put('profil/password', [PetaniAksiController::class, 'passwordUpdate'])->name('profil.password');

        // ---- CRUD Panel ----
        Route::resource('kebun', Panel\KebunController::class)
            ->except('show')
            ->names(['index' => 'kebun']);

        Route::resource('panen', Panel\PanenController::class)->except('show');

        Route::resource('keuangan', Panel\KeuanganController::class)->except('show');

        Route::resource('perawatan', Panel\PerawatanController::class)->except('show');
        Route::patch('perawatan/{perawatan}/selesai', [Panel\PerawatanController::class, 'selesai'])
            ->name('perawatan.selesai');

        Route::resource('laporan', Panel\LaporanController::class)
            ->names(['index' => 'laporan']);

        Route::resource('harga-tbs', Panel\HargaTbsController::class)
            ->except('show')
            ->parameters(['harga-tbs' => 'harga']);
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