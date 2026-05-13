<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/felhasznalo/add', [RegistrationController::class, 'show']);
Route::post('/felhasznalo/add', [RegistrationController::class, 'store']);

Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('email.verify');
Route::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])->middleware('throttle:3,1');

Route::middleware('session.user')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/teszt', [UtilityController::class, 'testPage']);
    Route::post('/kategoria/add', [CategoryController::class, 'store']);
    Route::delete('/kategoria/{id}', [CategoryController::class, 'destroy']);
});

Route::middleware(['session.user', 'session.user.verified'])->group(function () {
    Route::get('/fooldal', [DashboardController::class, 'index']);
    Route::post('/koltseg/add', [TransactionController::class, 'store']);
    Route::put('/koltseg/edit/{id}', [TransactionController::class, 'update']);
    Route::delete('/koltseg/delete/{id}', [TransactionController::class, 'destroy']);
    Route::get('/arfolyam/frissites', [ExchangeRateController::class, 'showRefreshInfo']);
    Route::get('/statisztika', [StatController::class, 'index'])->name('statisztika.index');
});
