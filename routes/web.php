<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscriptionController;

use \App\Http\Controllers\Api\StripeController;


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

Route::get('/', [AdminController::class, 'index']);
Route::get('/admin', [AdminController::class, 'index' ])->name('login.admin');
Route::get('/login', [AdminController::class, 'index' ])->name('login'); 

Route::post('/admin/auth', [AdminController::class, 'auth' ])->name('admin.auth');

Route::group(['middleware' => 'admin_auth'],function(){

Route::get('/admin/dashboard', [AdminController::class, 'dashboard' ])->name('admin.dashboard');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard' ])->name('admin.dashboard');
Route::get('/logout', [AdminController::class, 'logout' ])->name('logout');

// Category routes
 Route::prefix('admin/category')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/manage', [CategoryController::class, 'manage_category'])->name('category.manage');
    Route::post('/update', [CategoryController::class, 'updateCategory'])->name('category.update');
    Route::get('/edit/{id}', [CategoryController::class, 'editCategory'])->name('category.edit');
    Route::get('/delete/{id}', [CategoryController::class, 'deleteCategory'])->name('category.delete');
    Route::get('/status/{type}/{id}', [CategoryController::class, 'updateStatus'])->name('category.status');
});

// Coupon routes
Route::prefix('admin/coupon')->group(function () {
    Route::get('/', [CouponController::class, 'index'])->name('coupon.index');
    Route::get('/manage', [CouponController::class, 'manage_coupon'])->name('coupon.manage');
    Route::post('/update', [CouponController::class, 'updateCoupon'])->name('coupon.update');
    Route::get('/edit/{id}', [CouponController::class, 'editCoupon'])->name('coupon.edit');
    Route::get('/delete/{id}', [CouponController::class, 'deleteCoupon'])->name('coupon.delete');
    Route::get('/status/{type}/{id}', [CouponController::class, 'updateStatus'])->name('coupon.status');
});

// Size routes
Route::prefix('admin/size')->group(function () {
    Route::get('/', [SizeController::class, 'index'])->name('size.index');
    Route::get('/manage', [SizeController::class, 'manage_size'])->name('size.manage');
    Route::post('/update', [SizeController::class, 'updateSize'])->name('size.update');
    Route::get('/edit/{id}', [SizeController::class, 'editSize'])->name('size.edit');
    Route::get('/delete/{id}', [SizeController::class, 'deleteSize'])->name('size.delete');
    Route::get('/status/{type}/{id}', [SizeController::class, 'updateStatus'])->name('size.status');
});

// Color routes
Route::prefix('admin/color')->group(function () {
    Route::get('/', [ColorController::class, 'index'])->name('color.index');
    Route::get('/manage', [ColorController::class, 'manage_Color'])->name('color.manage');
    Route::post('/update', [ColorController::class, 'updateColor'])->name('color.update');
    Route::get('/edit/{id}', [ColorController::class, 'editColor'])->name('color.edit');
    Route::get('/delete/{id}', [ColorController::class, 'deleteColor'])->name('color.delete');
    Route::get('/status/{type}/{id}', [ColorController::class, 'updateStatus'])->name('color.status');
});


// Prduct routes
 Route::prefix('admin/product')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
    Route::get('/manage', [ProductController::class, 'manage_product'])->name('product.manage');
    Route::post('/update', [ProductController::class, 'updateProduct'])->name('product.update');
    Route::get('/edit/{id}', [ProductController::class, 'editProduct'])->name('product.edit');
    Route::get('/delete/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
    Route::get('/status/{type}/{id}', [ProductController::class, 'updateStatus'])->name('product.status');
});


// Subscription routes
Route::prefix('admin/subscription')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('/manage', [SubscriptionController::class, 'manage_subscription'])->name('subscription.manage');
    Route::post('/update', [SubscriptionController::class, 'updateSubscription'])->name('subscription.update');
    Route::get('/edit/{id}', [SubscriptionController::class, 'editSubscription'])->name('subscription.edit');
    Route::get('/delete/{id}', [SubscriptionController::class, 'deleteSubscription'])->name('subscription.delete');
    Route::get('/status/{type}/{id}', [SubscriptionController::class, 'updateStatus'])->name('subscription.status');
});


Route::get('/admin/hashPasssword', [AdminController::class, 'hashPasssword' ]);
});

Route::get('/auth/google', [AdminController::class, 'redirectToGoogle' ])->name('google.login');
Route::get('/auth/google/callback', [AdminController::class, 'handleGoogleCallback' ]);

Route::get('/auth/facebook', [AdminController::class, 'redirectToFacebook' ])->name('facebook.login');
Route::get('/auth/facebook/callback', [AdminController::class, 'handleFacebookCallback' ]);


Route::get('/auth/linkedin', [AdminController::class, 'redirectToLinkedIn' ])->name('linkedin.login');
Route::get('/auth/linkedin/callback', [AdminController::class, 'handleLinkedInCallback' ]);

// Route::get('/import', [AdminController::class, 'import' ])->name('shopify.import');

Route::get('/add/bank', [AdminController::class, 'addBank' ])->name('add.bank');
Route::post('/token/exchange', [AdminController::class, 'tokenExchange' ])->name('token.exchange');


Route::get('/fancy', [AdminController::class, 'fancyBox' ]);


Route::get('/form', function(){
    return view ('form');
});
