<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\ClientController;
use Modules\Client\Http\Controllers\PharmacyController;
use Modules\Client\Http\Controllers\WaitingListController;

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

Route::group(['as' => 'api.', 'middleware' => ['auth:sanctum', 'localization', 'cache_data']], function () {

    Route::group(['prefix' => 'clients', 'middleware' => 'auth:api'], function () {
        Route::get('/', [ClientController::class, 'index']);
        Route::get('/view', [ClientController::class, 'view']);
        Route::get('/dropdown', [ClientController::class, 'dropdown']);
        Route::post('/store', [ClientController::class, 'store']);
    });

    Route::group(['prefix' => 'pharmacies', 'middleware' => 'auth:api'], function () {
        Route::get('/', [PharmacyController::class, 'index']);
        Route::post('/store', [PharmacyController::class, 'create']);
        Route::get('/settings', [PharmacyController::class, 'settings']);
        Route::post('/add-pharmacy-to-client', [PharmacyController::class, 'addPharmacyToClient']);
        Route::post('/update/{pharmacy}', [PharmacyController::class, 'updatePharmacy']);
        Route::delete('/delete-media', [PharmacyController::class, 'deleteMedia']);
        Route::put('/add-extra-discount', [PharmacyController::class, 'addExtraDiscount']);
    });

    Route::group(['prefix' => 'waiting-list', 'middleware' => 'auth:api'], function () {
        Route::get('/', [WaitingListController::class, 'index']);
        Route::post('/create', [WaitingListController::class, 'create']);
    });
});
