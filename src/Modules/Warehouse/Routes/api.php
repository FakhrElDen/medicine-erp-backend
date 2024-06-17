<?php

use Illuminate\Support\Facades\Route;
use Modules\Warehouse\Http\Controllers\BasketController;
use Modules\Warehouse\Http\Controllers\BatchTransferController;
use Modules\Warehouse\Http\Controllers\BulkController;
use Modules\Warehouse\Http\Controllers\InventoryController;
use Modules\Warehouse\Http\Controllers\RetailController;
use Modules\Warehouse\Http\Controllers\SettlementController;
use Modules\Warehouse\Http\Controllers\TransferController;
use Modules\Warehouse\Http\Controllers\WarehouseController;

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

Route::group(['as' => 'api.', 'prefix' => 'warehouses', 'middleware' => ['auth:sanctum', 'localization', 'cache_data']], function () {

    Route::get('/', [WarehouseController::class, 'index']);
    Route::get('/view', [WarehouseController::class, 'view']);
    Route::get('/invoices', [WarehouseController::class, 'invoices']);
    Route::get('/corridors', [WarehouseController::class, 'corridors']);

    Route::group(['prefix' => 'batches'], function () {
        Route::get('/receiving', [WarehouseController::class, 'receivingBatches']);
        Route::patch('/complete-receiving', [WarehouseController::class, 'completeReceiving']);
        Route::get('/received', [WarehouseController::class, 'receivedBatches']);

        Route::get('/storing', [WarehouseController::class, 'storingBatches']);
        Route::patch('/complete-storing', [WarehouseController::class, 'completeStoring']);
        Route::get('/stored', [WarehouseController::class, 'storedBatches']);
    });

    Route::group(['prefix' => 'orders'], function () {

        // common (retail & bulk)
        // preparation
        Route::post('/prepare', [WarehouseController::class, 'prepared']); // prepared-order
        // reviewing
        Route::get('/unprepared', [WarehouseController::class, 'unprepared']); // unprepared-invoices
        Route::post('/inventorying', [WarehouseController::class, 'inventorying']);
        Route::post('/complete-inventorying', [WarehouseController::class, 'completeInventorying']);
        Route::post('/duplicate', [WarehouseController::class, 'duplicate']);

        Route::group(['prefix' => 'bulk'], function () {
            // preparation
            Route::get('/listing-prepared', [BulkController::class, 'listingPrepared']); // listing-prepared-orders
            Route::post('/print', [BulkController::class, 'print']); // print-whole-order
            Route::post('/complete', [BulkController::class, 'complete']); // complete-whole-order
            // reviewing
            Route::get('/listing-reviewing', [BulkController::class, 'listingReviewing']); // listing-reviewing-orders
            Route::get('/listing-reviewed', [BulkController::class, 'listingReviewed']); // listing-reviewed-orders
            Route::get('/view-prepared', [BulkController::class, 'viewPrepared']); // view-prepared-invoice-bulk
        });

        Route::group(['prefix' => 'retail'], function () {
            // preparation
            Route::get('/listing-prepared', [RetailController::class, 'listingPrepared']); // prepared-invoices
            Route::get('/view-unprepared', [RetailController::class, 'viewUnprepared']); // view-unprepared-invoice
            Route::get('/view-preparing', [RetailController::class, 'viewPreparing']); // view-preparing-invoice-retail
            Route::post('/complete', [RetailController::class, 'complete']); // complete-order
            // reviewing
            Route::get('/listing-reviewing', [RetailController::class, 'listingReviewing']); // reviewing-invoices
            Route::get('/listing-reviewed', [RetailController::class, 'listingReviewed']); // inventoried-invoices
            Route::get('/view-reviewed', [RetailController::class, 'viewReviewed']); // inventoried-invoice-content
            Route::get('/view-prepared', [RetailController::class, 'viewPrepared']); // view-prepared-invoice
        });
    });

    Route::group(['prefix' => 'baskets'], function () {
        Route::post('/create', [BasketController::class, 'create']);
        Route::delete('{basket_id}/delete', [BasketController::class, 'delete']);
    });

    // settlement
    Route::group(['prefix' => 'settlement'], function () {
        Route::get('/', [SettlementController::class, 'index']);
        Route::get('/finished', [SettlementController::class, 'finished']);
        Route::post('/update', [SettlementController::class, 'update']);
    });

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/{all?}', [InventoryController::class, 'index']);
    });

    // storekeeper routes
    Route::group(['prefix' => 'transfers'], function () {
        Route::get('/unconfirmed-transfers/{all?}', [BatchTransferController::class, 'unconfirmedTransfers']);
        Route::get('/products/{all?}', [BatchTransferController::class, 'confirmedProductsTransferred']);
        Route::get('/orders/{all?}', [TransferController::class, 'confirmedOrdersTransferred']);
        Route::post('/confirmed-transfers', [BatchTransferController::class, 'confirmTransfers']);
    });
});
