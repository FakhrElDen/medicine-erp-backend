<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\CartPurchaseController;
use Modules\Purchase\Http\Controllers\PurchaseController;
use Modules\Purchase\Http\Controllers\PurchaseReturnController;

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

Route::group(['as' => 'api.', 'prefix' => 'purchases', 'middleware' => ['auth:api', 'localization', 'cache_data']], function () {
    Route::get('/', [PurchaseController::class, 'index']);
    Route::get('/receiving-reviewer', [PurchaseController::class, 'receivingReviewer']);
    Route::get('/print', [PurchaseController::class, 'print']);
    Route::get('/{purchase}', [PurchaseController::class, 'show']);
    Route::post('/reviewing', [PurchaseController::class, 'reviewing']);

    Route::group(['prefix' => 'cart'], function () {
        Route::post('/inventorying', [CartPurchaseController::class, 'inventorying']);
        Route::get('/remove-inventoried', [CartPurchaseController::class, 'removeInventoried']);
    });

    Route::group(['prefix' => 'returns'], function () {
        Route::get('/', [PurchaseReturnController::class, 'index']);
        Route::get('/paginated', [PurchaseReturnController::class, 'paginated']);
        Route::get('/view', [PurchaseReturnController::class, 'view']);
        Route::post('/store', [PurchaseReturnController::class, 'store']);
        Route::get('/cancel', [PurchaseReturnController::class, 'cancel']);
        Route::post('/update', [PurchaseReturnController::class, 'update']);
        Route::get('/receiving', [PurchaseReturnController::class, 'receiving']);
        Route::get('/receiving-paginated', [PurchaseReturnController::class, 'receivingPaginated']);
    });
});
