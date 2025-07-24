<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminParkingSlotController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ParkingRateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/home', [WelcomeController::class, 'index'])->name('home');

// User profile routes
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::put('/profile', 'update')->name('profile.update');
    });
});

// Authentication routes
Auth::routes();

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->middleware('can:access-admin')
        ->name('admin.dashboard');
        
    Route::get('/validasi-qr', [AdminController::class, 'validasiQr'])
        ->middleware('can:access-admin')
        ->name('admin.validasi-qr');
        
    Route::get('/riwayat-parkir', [AdminController::class, 'riwayatParkir'])
        ->middleware('can:access-admin')
        ->name('admin.riwayat-parkir');
        
    Route::get('/parking-slots', [AdminParkingSlotController::class, 'index'])
        ->middleware('can:access-admin')
        ->name('admin.parking_slots.index');

    Route::get('/parking-slots/_add_modal', [AdminParkingSlotController::class, 'showAddModal'])
        ->middleware('can:access-admin')
        ->name('admin.parking_slots.add_modal');
        
    Route::get('/parking-slots/{id}/edit', [AdminParkingSlotController::class, 'showEditModal'])
        ->middleware('can:access-admin')
        ->name('admin.parking_slots.edit_modal');
        
    Route::post('/parking-slots', [AdminParkingSlotController::class, 'store'])
        ->middleware('can:access-admin')
        ->name('admin.parking_slots.store');
    
    // Parking Rates Management
    Route::get('/parking-rates', [ParkingRateController::class, 'index'])
        ->middleware('can:access-admin')
        ->name('admin.parking_rates.index');
    Route::post('/parking-rates', [ParkingRateController::class, 'store'])
        ->middleware('can:access-admin')
        ->name('admin.parking_rates.store');
    Route::get('/parking-rates/{id}', [ParkingRateController::class, 'show'])
        ->middleware('can:access-admin')
        ->name('admin.parking_rates.show');
    Route::put('/parking-rates/{id}', [ParkingRateController::class, 'update'])
        ->middleware('can:access-admin')
        ->name('admin.parking_rates.update');
    Route::delete('/parking-rates/{id}', [ParkingRateController::class, 'destroy'])
        ->middleware('can:access-admin')
        ->name('admin.parking_rates.destroy');

    // User Management Routes
    Route::resource('/users', \App\Http\Controllers\Admin\UserController::class)
        ->middleware('can:access-admin')
        ->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);

    // API endpoints for admin
    Route::prefix('api')->middleware('can:access-admin')->group(function () {
        Route::post('/scan-entry', [AdminController::class, 'scanEntry'])->name('admin.scan.entry');
        Route::post('/scan-exit', [AdminController::class, 'scanExit'])->name('admin.scan.exit');
        
        // Parking slots API endpoints
        Route::post('/admin/parking-slots', [AdminParkingSlotController::class, 'store'])->name('api.admin.parking_slots.store');
        Route::post('/admin/parking-slots/{id}', [AdminParkingSlotController::class, 'update'])->name('api.admin.parking_slots.update');
        Route::delete('/admin/parking-slots/{id}', [AdminParkingSlotController::class, 'destroy'])->name('api.admin.parking_slots.destroy');
    });
});

// Customer routes
Route::middleware(['auth'])->group(function () {
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/booking/{id}/show', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');
});

// Customer routes with role middleware
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])
        ->middleware('can:access-customer')
        ->name('customer.dashboard');
        
    Route::get('/bookings', [CustomerController::class, 'bookingHistory'])->name('customer.bookings');
    Route::get('/bookings/active', [CustomerController::class, 'activeBookings'])->name('customer.bookings.active');
    Route::get('/bookings/create', [CustomerController::class, 'createBooking'])->name('customer.bookings.create');
    Route::post('/bookings', [CustomerController::class, 'storeBooking'])->name('customer.bookings.store');
    Route::get('/bookings/{id}', [CustomerController::class, 'showBooking'])->name('customer.bookings.show');
    Route::post('/bookings/{id}/cancel', [CustomerController::class, 'cancelBooking'])->name('customer.bookings.cancel');
    
    // Customer payment routes
    Route::get('/payment', [PaymentController::class, 'index'])->name('customer.payment');
    Route::get('/payment/{id}/form', [PaymentController::class, 'showForm'])->name('customer.payment.form');
    Route::post('/payment', [PaymentController::class, 'store'])->name('customer.payment.store');
});

// Public API routes
Route::prefix('api')->group(function () {
    Route::get('/parking-slots', [ParkingController::class, 'getParkingSlots'])->name('api.parking_slots.get');
});

// Endpoint untuk mendapatkan data user yang terautentikasi
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test-telegram', function() {
    try {
        $response = Telegram::getMe();
        dd($response);
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
});