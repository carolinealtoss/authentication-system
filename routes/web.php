<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\TwoFactorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/2fa/setup', [TwoFactorController::class, 'setup2FA'])->name('setup.2fa');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable2FA'])->name('enable.2fa');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable2FA'])->name('disable.2fa');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
});

Route::middleware('guest')->group(function () {
    Route::get('/2fa/verify', [TwoFactorController::class, 'showVerifyForm'])->name('verify.2fa');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('verify.2fa.post');
});

require __DIR__.'/auth.php';
