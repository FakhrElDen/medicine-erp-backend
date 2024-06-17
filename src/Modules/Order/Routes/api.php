<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;
use Modules\Order\Http\Controllers\ReportController;
use Modules\Order\Http\Controllers\ReturnController;

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

Route::group(['as' => 'api.', 'prefix' => 'orders', 'middleware' => ['auth:sanctum', 'localization', 'cache_data']], function () {

    Route::get('/', [OrderController::class, 'index']);
    Route::get('/inventoried', [OrderController::class, 'inventoried']);
    Route::post('/create', [OrderController::class, 'store']);
    Route::get('/invoice-content', [OrderController::class, 'invoiceContent']);
    Route::get('/invoice-content-inventoried', [OrderController::class, 'invoiceInventoriedContent']);
    Route::get('/follow-up', [OrderController::class, 'followUp']);

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/salesman-productivity', [ReportController::class, 'salesmanProductivityReport']);
        Route::get('/salesman-sales', [ReportController::class, 'salesmanSalesReport']);
        Route::get('/client-sales', [ReportController::class, 'pharmacySalesReport']);

        Route::get('/seller-sales-with-his-clients', [ReportController::class, 'sellerSalesWithHisClients']);
        Route::get('/seller-sales-with-non-his-clients', [ReportController::class, 'sellerSalesWithNonHisClients']);

        Route::get('/governorate-productivity', [ReportController::class, 'governorateProductivity']);

        Route::get('/city-productivity', [ReportController::class, 'cityProductivity']);
        Route::get('/city-sales', [ReportController::class, 'citySalesReport']);

        Route::get('/track-productivity', [ReportController::class, 'trackProductivity']);
        Route::get('/track-sales', [ReportController::class, 'trackSales']);

        Route::get('/city-customer-sales', [ReportController::class, 'cityCustomerSalesReport']);
        Route::get('/city-customer-sales-non-deal', [ReportController::class, 'cityCustomerSalesNonDealReport']);
    });

    // storekeeper routes
    Route::group(['prefix' => 'returns'], function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::get('/returnables', [ReturnController::class, 'returnables']);
        Route::get('/returnables/print', [ReturnController::class, 'print']);
        Route::get('/get-return', [ReturnController::class, 'getReturn']); // changed to view
        Route::post('/create', [ReturnController::class, 'store']);
        Route::post('/validate-quantity', [ReturnController::class, 'validateQuantity']); // unused api return nothing
        Route::post('/validate-returns', [ReturnController::class, 'validateReturnWithoutOrder']); // unused api
    });
});
