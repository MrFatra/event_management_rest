<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\ProfileController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    $featuredEvents = Event::latest()->take(3)->get();
    return view('home', compact('featuredEvents'));
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Events
Route::get('/explore', [EventController::class, 'index'])->name('events.index');
Route::get('/event/{id}', [EventController::class, 'show'])->name('events.show');

// Protected Profile & Tickets
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/my-tickets', [ProfileController::class, 'tickets'])->name('profile.tickets');
    Route::post('/event/{id}/register', [ProfileController::class, 'registerForEvent'])->name('events.register');
    Route::post('/event/{id}/cancel', [ProfileController::class, 'cancelRegistration'])->name('events.cancel');

    // Payments
    Route::get('/payment/{registrationId}', [\App\Http\Controllers\PaymentController::class, 'pay'])->name('payments.pay');
});
