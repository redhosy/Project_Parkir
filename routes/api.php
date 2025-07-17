<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController; // tambahkan jika pakai controller


// Endpoint untuk mendapatkan user yang sedang login
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
    ]);
});

// Contoh endpoint yang dilindungi role middleware
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin-data', function () {
    return response()->json([
        'message' => 'Data ini hanya bisa diakses oleh admin',
    ]);
});

// Contoh route ke controller (pastikan controllernya ada)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);  // Contoh: /api/users
});
