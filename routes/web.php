<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterControllers;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\PasienController;
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

// Photo Upload Route
Route::post('/upload-photo', [AuthController::class, 'uploadPhoto'])->name('upload.photo');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Resource routes untuk dokter
    Route::resource('dktr', DokterControllers::class);
    
    // Resource routes untuk ruangan
    Route::resource('ruangan', RuanganController::class);
    
    // ⚠️ PENTING: Route AJAX/API KHUSUS HARUS SEBELUM route resource!
    // Karena Laravel match route dari atas ke bawah
    
    // AJAX routes untuk pasien - URUTAN PENTING!
    Route::get('/pasien/get-dokter/{penyakit}', [PasienController::class, 'getDokter'])->name('pasien.get.dokter');
    Route::get('/pasien/cek-kamar/{namaKamar}', [PasienController::class, 'cekKamar'])->name('pasien.cek.kamar');
    Route::get('/pasien/dokter-with-room/{dokterId}', [PasienController::class, 'getDokterWithRoom'])->name('pasien.dokter.room');
    
    // Route AJAX yang DIPERLUKAN untuk fix daya tampung:
    Route::get('/cek-kamar/{namaKamar}', [PasienController::class, 'cekKamar'])->name('cek.kamar'); // INI YANG DIPANGGIL AJAX!
    Route::get('/dokter/{penyakit}', [PasienController::class, 'getDokter'])->name('get.dokter');
    
    // Resource routes untuk pasien - HARUS SETELAH route spesifik
    Route::resource('pasien', PasienController::class);
    // Tambahkan routes ini di web.php
Route::get('/update-otomatis-daya-tampung', [PasienController::class, 'updateOtomatisDayaTampung']);
Route::get('/sinkronisasi-daya-tampung', [PasienController::class, 'sinkronisasiDayaTampung']);
    
    
    // Routes tambahan khusus untuk pasien (opsional)
    Route::get('/pasien/search/kamar/{nomorKamar}', [PasienController::class, 'searchByKamar'])->name('pasien.search.kamar');
    Route::get('/pasien/by-dokter/{idDokter}', [PasienController::class, 'getByDokter'])->name('pasien.by.dokter');
    Route::get('/get-kapasitas-ruangan', [PasienController::class, 'getKapasitasRuangan']);
    // API endpoints (opsional)
    Route::prefix('api')->group(function () {
        Route::get('/pasien/data', [PasienController::class, 'getData'])->name('api.pasien.data');
        Route::post('/pasien/update-status', [PasienController::class, 'updateStatus'])->name('api.pasien.update.status');
        Route::get('/kamar/status/{namaKamar}', [PasienController::class, 'getKamarStatus'])->name('api.kamar.status');
    });
});