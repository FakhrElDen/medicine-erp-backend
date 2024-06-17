<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

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

Route::group(['as' => 'api.', 'prefix' => 'carts', 'middleware' => ['auth:sanctum', 'localization']], function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/create', [CartController::class, 'store']);
    Route::get('/sales', [CartController::class, 'sales']);
    Route::get('/client-product', [CartController::class, 'clientProduct']);
    Route::delete('{batch_id}/delete', [CartController::class, 'destroy']);
    Route::post('/delete-all', [CartController::class, 'deleteAll']);
});
