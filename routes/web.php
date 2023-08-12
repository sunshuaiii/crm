<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'home');
Auth::routes();

Route::get('/login/customer', [LoginController::class, 'showCustomerLoginForm'])->name('login.customer');
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm']);
Route::get('/login/marketingStaff', [LoginController::class, 'showMarketingStaffLoginForm']);
Route::get('/login/supportStaff', [LoginController::class, 'showSupportStaffLoginForm']);
Route::get('/register/customer', [RegisterController::class, 'showCustomerRegisterForm'])->name('register.customer');
Route::get('/register/admin', [RegisterController::class, 'showAdminRegisterForm']);
Route::get('/register/marketingStaff', [RegisterController::class, 'showMarketingStaffRegisterForm']);
Route::get('/register/supportStaff', [RegisterController::class, 'showSupportStaffRegisterForm']);

Route::post('/login/customer', [LoginController::class, 'customerLogin']);
Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::post('/login/marketingStaff', [LoginController::class, 'marketingStaffLogin']);
Route::post('/login/supportStaff', [LoginController::class, 'supportStaffLogin']);
Route::post('/register/customer', [RegisterController::class, 'createCustomer']);
Route::post('/register/admin', [RegisterController::class, 'createAdmin']);
Route::post('/register/marketingStaff', [RegisterController::class, 'createMarketingStaff']);
Route::post('/register/supportStaff', [RegisterController::class, 'createSupportStaff']);

Route::group(['middleware' => 'auth:customer'], function () {
    Route::view('/customer', 'customer.customerHome');
    Route::view('/customer/membership', 'customer.membership');
    Route::view('/customer/coupons', 'customer.coupons');
    Route::view('/customer/support', 'customer.support');
    Route::view('/customer/support/contactUs', 'customer.contactUs');
    Route::view('/customer/profile', 'customer.profile')->name('customer.profile');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::view('/admin', 'customer.adminHome');
});

Route::group(['middleware' => 'auth:marketingStaff'], function () {
    Route::view('/marketingStaff', 'customer.marketingStaffHome');
});

Route::group(['middleware' => 'auth:supportStaff'], function () {
    Route::view('/supportStaff', 'customer.supportStaffHome');
});

Route::get('logout', [LoginController::class, 'logout']);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
