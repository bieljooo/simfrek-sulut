<?php

use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\WebsiteStyleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpectrumApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/spectrum', [SpectrumApiController::class, 'index'])->name('spectrum.index');
    Route::get('/spectrum/statistics', [SpectrumApiController::class, 'statistics'])->name('spectrum.statistics');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => redirect('/admin'))->name('dashboard');
    Route::get('/export', [ImportController::class, 'export'])->name('export');
    Route::get('/template', [ImportController::class, 'template'])->name('template');
    Route::post('/import', [ImportController::class, 'store'])->name('import.store');
    Route::delete('/data', [ImportController::class, 'destroyAll'])->name('data.destroy');

    Route::prefix('settings/style')->name('settings.style.')->group(function () {
        Route::put('/images', [WebsiteStyleController::class, 'updateImages'])->name('images.update');
    });
});