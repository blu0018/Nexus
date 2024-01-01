<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\UserController;
use \App\Http\Controllers\Api\ChatController;
use \App\Http\Controllers\Api\PayPalController;
use \App\Http\Controllers\Api\SubscriptionController;
use \App\Http\Controllers\Api\StripeController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:api')->group(function(){
    Route::get('/profile', [UserController::class, 'getaccount']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/category/chat', [ChatController::class, 'chat']);
    Route::get('/chat/list', [ChatController::class, 'chatlist']);
    Route::get('/subscription/list', [SubscriptionController::class, 'getSubscriptionlist']);
    Route::post('/subscription/purchase', [SubscriptionController::class, 'subscriptionPurchase']);
    Route::get('/user/subscription/detail', [SubscriptionController::class, 'getUserSubscription']);
    Route::post('/user/update/account', [StripeController::class, 'createUpdateCustomer']);
    Route::post('/user/update/bank', [StripeController::class, 'createUpdateBank']);

});


Route::post('/calculation', [SubscriptionController::class, 'calculation']);
Route::get('/paypal', [PayPalController::class, 'createPlan']);