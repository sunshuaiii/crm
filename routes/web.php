<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GoogleAuthController;
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

Route::view('/', 'home')->name('home');

// Route::get('/', function () {
//     return view('customer.customerHome');
// });

Auth::routes();

Route::get('/login/customer', [LoginController::class, 'showCustomerLoginForm'])->name('login.customer');
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');
Route::get('/login/marketingStaff', [LoginController::class, 'showMarketingStaffLoginForm'])->name('login.marketingStaff');
Route::get('/login/supportStaff', [LoginController::class, 'showSupportStaffLoginForm'])->name('login.supportStaff');
Route::get('/register/customer', [RegisterController::class, 'showCustomerRegisterForm'])->name('register.customer');

Route::post('/login/customer', [LoginController::class, 'customerLogin'])->name('customerLogin');
Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::post('/login/marketingStaff', [LoginController::class, 'marketingStaffLogin']);
Route::post('/login/supportStaff', [LoginController::class, 'supportStaffLogin']);
Route::post('/register/customer', [RegisterController::class, 'createCustomer']);

Route::group(['middleware' => 'auth:customer'], function () {
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.auth');
    Route::get('auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);
    Route::view('/customer', 'customer.customerHome');
    Route::get('/customer/membership', [CustomerController::class, 'showQRandBarCode'])->name('customer.membership');
    Route::view('/customer/coupons', 'customer.coupons')->name('customer.coupons');
    Route::get('/customer/coupons', [CustomerController::class, 'getCouponsInfo'])->name('customer.coupons');
    Route::post('/customer/coupons/claim', [CustomerController::class, 'claimCoupon'])->name('customer.coupons.claim');
    Route::get('/customer/coupons/details/{couponCode}', [CustomerController::class, 'getCouponDetails'])->name('customer.coupons.details');
    Route::post('/customer/coupons/redeem/{couponCode}', [CustomerController::class, 'redeemCoupon'])->name('customer.coupons.redeem');
    Route::get('/customer/checkout/history', [CustomerController::class, 'getCheckoutHistory'])->name('customer.checkout.history');
    Route::get('/customer/checkout/{id}', [CustomerController::class, 'getCheckoutDetails'])->name('customer.checkoutDetails');
    Route::post('/customer/checkout/membership', [CustomerController::class, 'membershipCheckout'])->name('customer.checkout.membership');
    Route::view('/customer/support', 'customer.support');
    Route::view('/customer/support/contactUs', 'customer.contactUs')->name('customer.support.contactUs');
    Route::post('/customer/support/contactUs', [CustomerController::class, 'submitContactForm'])->name('customer.support.contactUs.submit');
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::post('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::view('/admin', 'admin.adminHome');
    Route::get('/admin/couponManagement', [AdminController::class, 'getAllCoupons'])->name('admin.couponManagement');
    Route::get('/admin/couponManagement/addCoupon', [AdminController::class, 'addCoupon'])->name('admin.addCoupon');
    Route::get('/admin/couponManagement/editCoupon/{id}', [AdminController::class, 'editCoupon'])->name('admin.editCoupon');
    Route::put('/admin/couponManagement/updateCoupon/{id}', [AdminController::class, 'updateCoupon'])->name('admin.updateCoupon');
    Route::post('/admin/couponManagement/storeCoupon', [AdminController::class, 'storeCoupon'])->name('admin.storeCoupon');
    Route::view('/admin/staffRegistration', 'admin.staffRegistration')->name('admin.staffRegistration');
    Route::get('/admin/register/admin', [AdminController::class, 'showAdminRegisterForm'])->name('admin.register.admin');
    Route::get('/admin/register/marketingStaff', [AdminController::class, 'showMarketingStaffRegisterForm'])->name('admin.register.marketingStaff');
    Route::get('/admin/register/supportStaff', [AdminController::class, 'showSupportStaffRegisterForm'])->name('admin.register.supportStaff');
    Route::post('/admin/register/admin', [AdminController::class, 'createAdmin'])->name('admin.register.admin.submit');
    Route::post('/admin/register/marketingStaff', [AdminController::class, 'createMarketingStaff'])->name('admin.register.marketingStaff.submit');
    Route::post('/admin/register/supportStaff', [AdminController::class, 'createSupportStaff'])->name('admin.register.supportStaff.submit');
    Route::view('/admin/searchCustomer', 'searchCustomer')->name('admin.searchCustomer');
    Route::post('/admin/searchCustomer', [AdminController::class, 'searchCustomers'])->name('admin.searchCustomer.submit');
    Route::get('/admin/viewCustomer/{id}', [AdminController::class, 'viewCustomer'])->name('admin.viewCustomer');
});


Route::group(['middleware' => 'auth:marketingStaff'], function () {
    Route::view('/marketingStaff', 'marketingStaff.marketingStaffHome');

    Route::view('/marketingStaff/searchCustomer', 'searchCustomer')->name('marketingStaff.searchCustomer');
    Route::post('/marketingStaff/searchCustomer', [MarketingStaffController::class, 'searchCustomers'])->name('marketingStaff.searchCustomer.submit');
    Route::get('/marketingStaff/viewCustomer/{id}', [MarketingStaffController::class, 'viewCustomer'])->name('marketingStaff.viewCustomer');
});

Route::group(['middleware' => 'auth:supportStaff'], function () {
    Route::view('/supportStaff', 'supportStaff.supportStaffHome');

    Route::view('/supportStaff/searchCustomer', 'searchCustomer')->name('supportStaff.searchCustomer');
    Route::post('/marketingStaff/searchCustomer', [SupportStaffController::class, 'searchCustomers'])->name('supportStaff.searchCustomer.submit');
    Route::get('/marketingStaff/viewCustomer/{id}', [SupportStaffController::class, 'viewCustomer'])->name('supportStaff.viewCustomer');
});

Route::get('logout', [LoginController::class, 'logout']);
