<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('verify/email', [AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('dashboard', DashboardController::class)->middleware('auth:user_api')->name('dashboard');
