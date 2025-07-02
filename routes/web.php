<?php

// PARA LIMPIAR TODO PHP
// php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan config:cache

// PARA LIMPIAR LA CACHE DE COMPOSER
// composer dump-autoload

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogListController;
use App\Http\Controllers\PasswordListController;
use App\Http\Controllers\QuestionController;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;


Route::middleware(IsUserAuth::class)->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('show-login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});
Route::middleware('auth')->group(function () {
    Route::get('/', [LogListController::class, 'index'])->name('logs');
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions');
    Route::get('/passwords', [PasswordListController::class, 'index'])->name('passwords');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::fallback(function () {
    return redirect()->route('logs');
});
