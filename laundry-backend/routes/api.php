<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AnalyticsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::get('/orders/search', [OrderController::class, 'searchByCustomerName']);

// Admin setup route (for initial setup - should be removed in production)
Route::post('/setup-admin', function () {
    try {
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@laundry.com'],
            [
                'name' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
        
        return response()->json([
            'message' => 'Admin user created/updated successfully',
            'email' => $admin->email,
            'role' => $admin->role,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error creating admin user',
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Order routes
    Route::get('/orders/statistics', [OrderController::class, 'statistics']);
    Route::get('/orders/employee-overview', [OrderController::class, 'employeeOverview']);
    Route::apiResource('orders', OrderController::class);
    
    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'index']);
});
