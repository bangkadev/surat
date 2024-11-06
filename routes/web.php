<?php

use App\Http\Controllers\{
    AuthController,
    DashboardController,
    SuratKeluarEksternalController,
    SuratKeluarInternalController,
    SuratMasukEksternalController,
    SuratMasukInternalController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'auth')->name('auth');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::controller(SuratMasukInternalController::class)->group(function () {
        Route::get('surat-masuk-internal', 'index')->name('smi');
        Route::post('surat-masuk-internal', 'store')->name('smi.store');
        Route::put('surat-masuk-internal/{id}', 'update')->name('smi.update');
        Route::delete('surat-masuk-internal/{id}', 'destroy')->name('smi.destroy');
    });

    Route::controller(SuratKeluarInternalController::class)->group(function () {
        Route::get('surat-keluar-internal', 'index')->name('ski');
        Route::post('surat-keluar-internal', 'store')->name('ski.store');
        Route::put('surat-keluar-internal/{id}', 'update')->name('ski.update');
        Route::delete('surat-keluar-internal/{id}', 'destroy')->name('ski.destroy');
    });

    Route::controller(SuratMasukEksternalController::class)->group(function () {
        Route::get('surat-masuk-eksternal', 'index')->name('sme');
        Route::post('surat-masuk-eksternal', 'store')->name('sme.store');
        Route::put('surat-masuk-eksternal/{id}', 'update')->name('sme.update');
        Route::delete('surat-masuk-eksternal/{id}', 'destroy')->name('sme.destroy');
    });

    Route::controller(SuratKeluarEksternalController::class)->group(function () {
        Route::get('surat-keluar-eksternal', 'index')->name('ske');
        Route::post('surat-keluar-eksternal', 'store')->name('ske.store');
        Route::put('surat-keluar-eksternal/{id}', 'update')->name('ske.update');
        Route::delete('surat-keluar-eksternal/{id}', 'destroy')->name('ske.destroy');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
