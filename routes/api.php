<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication Controller routes
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// This route requires authentication for admin users
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Store Controller routes
use App\Http\Controllers\Api\StoreController;

// Public routes (anyone can access)
Route::get('/stores', [StoreController::class, 'index']);
Route::get('/stores/{store}', [StoreController::class, 'show']);

// Protected routes (user must be logged in)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/stores', [StoreController::class, 'store']);
    // We will add the update route here later
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/stores', [StoreController::class, 'store']);
        Route::put('/stores/{store}', [StoreController::class, 'update']); // <-- ADD/CONFIRM THIS
    });
});

// Review Controller routes
use App\Http\Controllers\Api\ReviewController;

// --- Review Routes ---

// Public route to get all reviews for a specific store
Route::get('/stores/{store}/reviews', [ReviewController::class, 'index']);

// Protected route for a consumer to post a review
Route::middleware('auth:sanctum')->post('/stores/{store}/reviews', [ReviewController::class, 'store']);

// Admin Store Verification routes
use App\Http\Controllers\Admin\StoreVerificationController;

// --- Admin Routes ---
Route::middleware(['auth:sanctum', 'is.admin'])->prefix('admin')->group(function () {
    Route::get('/stores/pending', [StoreVerificationController::class, 'getPendingStores']);
    Route::post('/stores/{store}/approve', [StoreVerificationController::class, 'approve']);
    Route::post('/stores/{store}/reject', [StoreVerificationController::class, 'reject']);
});

// Synchronization routes
use App\Http\Controllers\Api\SyncController;

// --- Synchronization Route ---
Route::middleware('auth:sanctum')->post('/sync/items', [SyncController::class, 'syncItems']);

// Subscription Controller routes
use App\Http\Controllers\Api\SubscriptionController;
// Promotion Controller routes
use App\Http\Controllers\Api\PromotionController;
// protected route for subscriptions and promotions
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::post('/promotions', [PromotionController::class, 'store']);
});

// Payment Controller routes
use App\Http\Controllers\Api\PaymentController;

// Route for the user to start the payment
Route::middleware('auth:sanctum')->post('/payments/initiate', [PaymentController::class, 'initiatePayment']);

// Route for the payment gateway to send a callback
Route::post('/payments/callback', [PaymentController::class, 'handleCallback']);
