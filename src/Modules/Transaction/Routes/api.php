<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\CashPaymentController;
use Modules\Transaction\Http\Controllers\CashReceiveController;
use Modules\Transaction\Http\Controllers\TransactionsController;
use Modules\Transaction\Http\Controllers\TransferredBalanceController;

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

Route::group(['as' => 'api.', 'middleware' => ['auth:api', 'localization', 'cache_data']], function () {
    Route::get('/cash_payment', [CashPaymentController::class, 'index']);

    Route::get('/cash_receive', [CashReceiveController::class, 'index']);

    Route::group(['prefix' => 'balance'], function () {
        Route::get('/transferred', [TransferredBalanceController::class, 'transferredIndex']);
        Route::get('/received', [TransferredBalanceController::class, 'receivedIndex']);
    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/sales', [TransactionsController::class, 'sales']);
        Route::get('/notifications', [TransactionsController::class, 'notifications']);
        Route::get('/reports', [TransactionsController::class, 'reports']);
        Route::get('/reports-owe', [TransactionsController::class, 'reportsOwe']);
    });
});
