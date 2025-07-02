<?php

// PARA LIMPIAR TODO PHP
// php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan config:cache

// PARA LIMPIAR LA CACHE DE COMPOSER
// composer dump-autoload

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\ContentSecurityPolicy;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware(IsUserAuth::class);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::fallback(function () {
    return redirect()->route('home');
});
