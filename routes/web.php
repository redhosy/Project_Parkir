<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ParkingSlotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');


// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/profile', [AuthController::class, 'profile'])->name('customer.profile');
    Route::get('/customer/vehicles', [VehicleController::class, 'index'])->name('customer.vehicles');
    Route::get('/customer/payment', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/customer/payment', [PaymentController::class, 'store'])->name('payment.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/vehicles', [AdminController::class, 'listVehicles'])->name('admin.vehicles');
    Route::get('/admin/slots', [ParkingSlotController::class, 'index'])->name('admin.slots');
    Route::post('/admin/qr-capture', [AdminController::class, 'captureQR'])->name('admin.capture.qr');
    Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin');

});

// Default route (homepage redirect to login)
Route::get('/', function () {
    return view('welcome');
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/debug', function () {
        dd(Auth::user());
    });
});