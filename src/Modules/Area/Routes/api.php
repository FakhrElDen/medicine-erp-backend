<?php

use Illuminate\Support\Facades\Route;
use Modules\Area\Http\Controllers\AreaController;
use Modules\Area\Http\Controllers\CityController;

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

Route::group(['as' => 'api.',  'middleware' => ['auth:api', 'localization', 'cache_data']], function () {
    Route::prefix('areas')->group(function () {
        Route::get('/', [AreaController::class, 'index']);
    });

    Route::prefix('cities')->group(function () {
        Route::get('/', [CityController::class, 'index']);
    });
});
