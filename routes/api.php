<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('/events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', [EventController::class, 'view']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/{id}/register', [EventController::class, 'register']);
        Route::post('/{id}/rate', [EventController::class, 'rating']);
    });
});

Route::prefix('/payments')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [PaymentController::class, 'checkout']);
        Route::get('/{orderId}', [PaymentController::class, 'show']);
    });
    Route::post('/webhook', [PaymentController::class, 'webhook']);
});
