<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;

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

Route::group([
    'namespace'=>'App\Http\Controllers',
    'controller'=>AuthController::class], function () {
    Route::post('/login','login');
    Route::post('/admin/login','adminLogin')->middleware('is_admin');
    Route::post('/register','register');
    Route::post('/logout','logout')->middleware('auth:sanctum');
    Route::get('/account','account')->middleware('auth:sanctum');
});

Route::group(['prefix'=>'v1', 'namespace'=>'App\Http\Controllers\Api\V1'], function () {
    Route::get('products', [ProductController::class, 'list']);
    Route::get('products/{product}', [ProductController::class, 'detail']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('carts',CartController::class);
        Route::apiResource('orders',OrderController::class)->except(['destroy']);
    });

    Route::get('families',FamilyController::class);
    Route::get('tags',TagController::class);
});

// Route::group([
//     'prefix'=>'v1/admin',
//     'namespace'=>'App\Http\Controllers\Api\Admin\V1',
//     'middleware'=>'auth:sanctum',
// ], function () {
//     Route::apiResource('products',ProductController::class)
//         ->except(['show', 'destroy']);

//     Route::apiResource('orders',OrderController::class)
//         ->except(['store','show','destroy']);
// });
