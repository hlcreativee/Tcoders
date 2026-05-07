<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\ProfileController;

// DASHBOARD
Route::get('/', [DashboardController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index']);

// TRANSAKSI
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

// PREDIKSI
Route::get('/prediksi', [PrediksiController::class, 'index'])->name('prediksi');

Route::prefix('predictions')->group(function () {
    Route::get('/', [PrediksiController::class, 'index'])->name('predictions.index');
    Route::get('/create', [PrediksiController::class, 'create'])->name('predictions.create');
    Route::post('/store', [PrediksiController::class, 'store'])->name('predictions.store');
});
