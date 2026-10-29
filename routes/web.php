<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AuthController;

// Guest routes (Login, Register, OTP)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/otp/verify', [AuthController::class, 'showOtpForm'])->name('otp.verify');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('products.index');
    });

    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store']);
    
    // Pansamantalang GET route para madaling ma-test ang logout sa URL bar
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});