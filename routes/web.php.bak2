<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HargaTbsController;
use App\Http\Controllers\KebunController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\PetaniDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Dashboard Petani (nanti ganti ke /petani/dashboard + middleware auth setelah login dipasang)
Route::get('/petani/{petani}/dashboard', [PetaniDashboardController::class, 'index'])->name('petani.dashboard');

// CRUD (nanti bungkus dengan middleware auth setelah login dipasang, mis. Laravel Breeze)
Route::resource('petani', PetaniController::class)->except('show');
Route::resource('kebun', KebunController::class)->except('show');
Route::resource('harga-tbs', HargaTbsController::class)->except('show')->parameters(['harga-tbs' => 'harga_tb']);
Route::resource('panen', PanenController::class)->except('show');