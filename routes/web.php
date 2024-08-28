<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Web Api Routes
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/user-login', [UserController::class, 'UserLogin']);
Route::post('/send-otp', [UserController::class, 'SendOTPCode']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::post('/reset-password', [UserController::class, 'ResetPassword'])->middleware(TokenVerification::class);

Route::get('/user-profile', [UserController::class, 'UserProfile'])->middleware(TokenVerification::class);
Route::post('/update-profile', [UserController::class, 'UpdateProfile'])->middleware(TokenVerification::class);

// Logout Mechanism
Route::get('/logout', [UserController::class, 'UserLogout']);
// Web Routes
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword', [UserController::class, 'ResetPasswordPage'])->middleware(TokenVerification::class);

Route::get('/dashboard', [DashboardController::class, 'DashboardPage'])->middleware(TokenVerification::class);
Route::get('/userProfile', [UserController::class, 'ProfilePage'])->middleware(TokenVerification::class);
