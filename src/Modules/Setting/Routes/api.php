<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\ComplainController;
use Modules\Setting\Http\Controllers\SettingController;

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

Route::group(['as' => 'api.', 'prefix' => 'settings', 'middleware' => ['auth:api', 'localization', 'cache_data']], function () {

    Route::get('/', [SettingController::class, 'index']);
    Route::get('/enums', [SettingController::class, 'enums']);
    Route::get('/filters', [SettingController::class, 'filters']);

    Route::prefix('complains')->group(function () {
        Route::post('/create', [ComplainController::class, 'store']);
        Route::post('/update-solver-complain', [ComplainController::class, 'update']);
        Route::get('/show-complain', [ComplainController::class, 'show']);
        Route::get('/unsolved-complains', [ComplainController::class, 'unsolvedComplains']);
        Route::get('/solved-complains', [ComplainController::class, 'solvedComplains']);
        Route::get('/find', [ComplainController::class, 'find']);
    });

    Route::group(['prefix' => 'warehouses'], function () {
        Route::get('/', [SettingController::class, 'getWarehousesSettings']);
        Route::get('/system', [SettingController::class, 'getCurrentWarehouseSystem']);
        Route::post('/system', [SettingController::class, 'warehouseSystem']);
        Route::post('corridors/create', [SettingController::class, 'createCorridor']);
        Route::post('corridors/{id}/update', [SettingController::class, 'updateCorridor']);
        Route::post('baskets/set-total-count', [SettingController::class, 'updateTotalBasketsCount']);
        Route::post('baskets/mark-damaged', [SettingController::class, 'markBasketAsDamaged']);
        Route::post('baskets/{id}/restore', [SettingController::class, 'restoreDamagedBasket']);
        Route::post('high-price', [SettingController::class, 'setHighPriceThreshold']);
        Route::post('expiration-warning', [SettingController::class, 'setExpirationWarningThreshold']);
    });
});
