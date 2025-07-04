<?php

// PARA LIMPIAR TODO PHP
// php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan config:cache

// PARA LIMPIAR LA CACHE DE COMPOSER
// composer dump-autoload

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogListController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PasswordListController;
use App\Http\Controllers\QuestionController;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;


Route::middleware(IsUserAuth::class)->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('show-login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Rutas para OTP
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('verify.otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');

    // Rutas para preguntas de seguridad
    Route::get('/security-question', [AuthController::class, 'showSecurityQuestionForm'])->name('security.question.form');
    Route::post('/security-question', [AuthController::class, 'verifySecurityQuestion'])->name('verify.security.question');
});
Route::middleware('auth')->group(function () {
    Route::get('/', [LogListController::class, 'index'])->name('logs');
    Route::get('/passwords', [PasswordController::class, 'showChangeForm'])->name('passwords');
    Route::post('/password/update', [PasswordController::class, 'changePassword'])->name('password.update');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::fallback(function () {
    return redirect()->route('logs');
});
