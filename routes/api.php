<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/account',[AuthController::class,'account'])->middleware('auth:sanctum');

Route::group(['prefix'=>'v1', 'namespace'=>'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('products',ProductController::class)->only(['index', 'show']);

    Route::middleware(['auth:sanctum','abilities:cart-access'])->group(function () {
        Route::apiResource('carts',CartController::class);
        Route::apiResource('orders',OrderController::class)->except(['destroy']);
    });

    Route::get('families',FamilyController::class);
    Route::get('tags',TagController::class);
});

Route::group(['prefix'=>'v1/admin', 'namespace'=>'App\Http\Controllers\Api\Admin\V1'], function () {
    Route::apiResource('products',ProductController::class)->except(['show', 'destroy']);
});
