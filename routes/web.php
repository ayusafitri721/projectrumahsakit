<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterControllers;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes  
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Forgot Password Routes
Route::get('/password/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Reset Password Routes
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Photo Upload Route (bisa diakses tanpa login untuk register success page)
Route::post('/upload-photo', [AuthController::class, 'uploadPhoto'])->name('upload.photo');

// Protected Routes (harus login dulu)
Route::middleware('auth')->group(function () {
    // Dashboard user setelah login
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Resource routes untuk dokter
    Route::resource('dktr', DokterControllers::class);
});