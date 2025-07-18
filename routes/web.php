<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminParkingSlotController;
use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::get('/booking/{id}/show', [BookingController::class, 'show'])->name('booking.show');
Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');

Auth::routes();

Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/parking-slots', [AdminParkingSlotController::class, 'index'])->name('parking_slots.index');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// API Publik (tanpa autentikasi)
Route::prefix('api')->group(function () {
    // Endpoint untuk booking
    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('api.bookings.store');

    // Endpoint untuk mendapatkan slot parkir yang tersedia
    Route::get('/parking-slots', [ParkingController::class, 'getParkingSlots'])
        ->name('api.parking_slots.get');

    // Endpoint untuk riwayat booking
    Route::get('/booking-history/{user_id}', [BookingController::class, 'getHistory'])
        ->name('api.booking.history');
});

// API untuk Admin (membutuhkan autentikasi)
Route::middleware(['auth:sanctum', 'can:access-admin'])
    ->prefix('api/admin')
    ->name('api.admin.')
    ->group(function () {

        // Endpoint untuk scan masuk
        Route::post('/scan-entry', [AdminController::class, 'scanEntry'])
            ->name('scan.entry');

        // Endpoint untuk scan keluar
        Route::post('/scan-exit', [AdminController::class, 'scanExit'])
            ->name('scan.exit');

        // Manajemen slot parkir
        Route::prefix('api/parking-slots')->group(function () {
            Route::post('/', [AdminParkingSlotController::class, 'store'])
                ->name('parking_slots.store');
            Route::put('/{id}', [AdminParkingSlotController::class, 'update'])
                ->name('parking_slots.update');
            Route::delete('/{id}', [AdminParkingSlotController::class, 'destroy'])
                ->name('parking_slots.destroy');
        });
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