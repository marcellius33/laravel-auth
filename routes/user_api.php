<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('verify/email', [AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('oauth/facebook', [SocialiteController::class, 'redirectToFacebook']);
Route::get('oauth/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);
Route::get('oauth/google', [SocialiteController::class, 'redirectToGoogle']);
Route::get('oauth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::get('dashboard', DashboardController::class)->middleware('auth:user_api')->name('dashboard');
