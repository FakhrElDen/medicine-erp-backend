<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\BatchController;
use Modules\Product\Http\Controllers\OfferController;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\ReportController;

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

Route::group(['as' => 'api.', 'prefix' => 'products', 'middleware' => ['auth:sanctum', 'localization', 'cache_data']], function () {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/dropdown', [ProductController::class, 'dropdown']);
    Route::get('/pages', [ProductController::class, 'indexPaginate']);
    Route::get('/view', [ProductController::class, 'view']);
    Route::get('/view-by-barcode', [ProductController::class, 'viewByBarcode']);
    Route::post('update/{product}', [ProductController::class, 'update']);
    Route::get('/manufacturers', [ProductController::class, 'manufacturers']);
    Route::get('/shortage', [ProductController::class, 'shortage']);
    Route::get('/bonus', [ProductController::class, 'bonus']);
    Route::get('/percentage/slat-one', [ProductController::class, 'percentageOfferSlatOne']);
    Route::get('/percentage/slat-two', [ProductController::class, 'percentageOfferSlatTwo']);
    Route::get('/medication-alternatives', [ProductController::class, 'medicationAlternatives']);
    Route::get('/related-active-ingredient', [ProductController::class, 'relatedActiveIngredient']);

    Route::group(['prefix' => 'batches', 'middleware' => 'auth:api'], function () {
        Route::get('/', [BatchController::class, 'index']);
        Route::get('/almost-expired', [BatchController::class, 'almostExpired']);
        Route::get('/prohibited', [BatchController::class, 'prohibitedBatches']);
        Route::post('/prohibited/store', [BatchController::class, 'storeProhibitedBatch']);
    });

    // edit batch operating number & expiration date
    Route::group(['prefix' => 'batches'], function () {
        Route::get('/paginate', [BatchController::class, 'indexPaginate']);
        Route::post('/update', [BatchController::class, 'updateBatchOperatingNumber']);
        Route::get('/updated-operations', [BatchController::class, 'getBatchesOperatingNumberUpdated']);
        Route::get('/updated-operations/{all}', [BatchController::class, 'getBatchesOperatingNumberUpdated']);
    });

    Route::group(['prefix' => 'offers', 'middleware' => 'auth:api'], function () {
        Route::get('/check', [OfferController::class, 'check']);
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('summary', [ReportController::class, 'index']);
        Route::get('sales/{all?}', [ReportController::class, 'sales']);
        Route::get('sales-returns/{all?}', [ReportController::class, 'salesReturns']);
        Route::get('purchases/{all?}', [ReportController::class, 'purchases']);
        Route::get('purchases-returns/{all?}', [ReportController::class, 'purchaseReturns']);
        Route::get('inventory/{all?}', [ReportController::class, 'inventory']);
        Route::get('transfers/{all?}', [ReportController::class, 'transfers']);
    });
});
