<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\TokenVerification;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

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
Route::post('/send-otp', [UserController::class, 'SendOTP']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::post('/reset-password', [UserController::class, 'ResetPassword'])->middleware([TokenVerification::class]);


Route::get('/user-profile', [UserController::class, 'UserProfile'])->middleware(TokenVerification::class);
Route::post('/update-profile', [UserController::class, 'UpdateProfile'])->middleware(TokenVerification::class);
//
// Logout Mechanism
Route::get('/logout', [UserController::class, 'UserLogout'])->middleware(TokenVerification::class);
// Web Routes
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOtpPage']);
Route::get('/resetPassword', [UserController::class, 'ResetPasswordPage'])->middleware(TokenVerification::class);

// Dashboard Pages
Route::get('/dashboard', [DashboardController::class, 'DashboardPage'])->middleware(TokenVerification::class);
Route::get('/userProfile', [UserController::class, 'ProfilePage'])->middleware(TokenVerification::class);
Route::get('/categoryPage', [CategoryController::class, 'CategoryPage'])->middleware(TokenVerification::class);
Route::get('/customerPage', [CustomerController::class, 'CustomerPage'])->middleware(TokenVerification::class);
Route::get('/productPage', [ProductController::class, 'ProductPage'])->middleware(TokenVerification::class);
Route::get('/invoicePage', [InvoiceController::class, 'InvoicePage'])->middleware(TokenVerification::class);
Route::get('/salePage', [InvoiceController::class, 'SalePage'])->middleware(TokenVerification::class);
Route::get('/reportPage',[ReportController::class,'ReportPage'])->middleware([TokenVerification::class]);



// Category
Route::post('/category-create', [CategoryController::class, 'CategoryCreate'])->middleware(TokenVerification::class);
Route::get('/category-list', [CategoryController::class, 'CategoryList'])->middleware(TokenVerification::class);
Route::post('/category-update', [CategoryController::class, 'CategoryUpdate'])->middleware(TokenVerification::class);
Route::post('/category-delete', [CategoryController::class, 'CategoryDelete'])->middleware(TokenVerification::class);
Route::post('/category-by-id', [ProductController::class, 'CategoryById'])->middleware(TokenVerification::class);

//Customer
Route::get('/customer-list', [CustomerController::class, 'CustomerList'])->middleware(TokenVerification::class);
Route::post('/customer-by-id', [CustomerController::class, 'CustomerById'])->middleware(TokenVerification::class);
Route::post('/customer-create', [CustomerController::class, 'CustomerCreate'])->middleware(TokenVerification::class);
Route::post('/customer-update', [CustomerController::class, 'CustomerUpdate'])->middleware(TokenVerification::class);
Route::post('/customer-delete', [CustomerController::class, 'CustomerDelete'])->middleware(TokenVerification::class);

// Product
Route::post('/product-create', [ProductController::class, 'ProductCreate'])->middleware(TokenVerification::class);
Route::get('/product-list', [ProductController::class, 'ProductList'])->middleware(TokenVerification::class);
Route::post('/product-by-id', [ProductController::class, 'ProductById'])->middleware(TokenVerification::class);
// Route::get('/product-by/{product_id}', [ProductController::class, 'ProductBy'])->middleware(TokenVerification::class);
Route::post('/product-update', [ProductController::class, 'ProductUpdate'])->middleware(TokenVerification::class);
Route::post('/product-delete', [ProductController::class, 'ProductDelete'])->middleware(TokenVerification::class);

// Invoice
Route::post('/invoice-create', [InvoiceController::class, 'InvoiceCreate'])->middleware(TokenVerification::class);
Route::get('/invoice-select', [InvoiceController::class, 'InvoiceSelect'])->middleware(TokenVerification::class);
Route::post('/invoice-details', [InvoiceController::class, 'InvoiceDetails'])->middleware(TokenVerification::class);
Route::post('/invoice-update', [InvoiceController::class, 'InvoiceUpdate'])->middleware(TokenVerification::class);
Route::post('/invoice-delete', [InvoiceController::class, 'InvoiceDelete'])->middleware(TokenVerification::class);


// Summary and Report
Route::get('/summary', [DashboardController::class, 'Summary'])->middleware(TokenVerification::class);
Route::get('/sales-report/{FromDate}/{ToDate}', [ReportController::class, 'SalesReport'])->middleware(TokenVerification::class);