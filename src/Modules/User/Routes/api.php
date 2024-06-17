<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\UserController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::group(['as' => 'api.', 'prefix' => 'users', 'middleware' => ['auth:api', 'localization', 'cache_data']], function () {

    Route::get('/sales', [UserController::class, 'sales']);
    Route::get('/delivers', [UserController::class, 'delivers']);
    Route::get('/suppliers', [UserController::class, 'suppliers']);
    Route::get('/receivers-auditor', [UserController::class, 'receiversAuditor']);
    Route::get('/receivers-auditor-store-keepers', [UserController::class, 'receiversAuditorStoreKeepers']);
    Route::get('/general-preparation', [UserController::class, 'generalPreparation']);
    Route::get('/retail-preparation', [UserController::class, 'retailPreparation']);
    Route::get('/storing-workers', [UserController::class, 'storingWorkers']);
    Route::get('/retail-sales-auditor', [UserController::class, 'retailSalesAuditor']);

    Route::get('/get-client-pharmacies', [UserController::class, 'getClientPharmacies']);

    Route::prefix('permissions')->group(function () {
        Route::get('/', [UserController::class, 'getPermissions']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [UserController::class, 'getRoles']);
        Route::get('/users', [UserController::class, 'getUsersByRole']);
    });
});
