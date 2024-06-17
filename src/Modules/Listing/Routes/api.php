<?php

use Illuminate\Support\Facades\Route;
use Modules\Listing\Http\Controllers\ListingController;

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

Route::group(['as' => 'api.', 'prefix' => 'listings', 'middleware' => ['auth:api', 'localization', 'cache_data']], function () {
    Route::get('/', [ListingController::class, 'index']);
    Route::post('/create', [ListingController::class, 'store']);
    Route::put('/update', [ListingController::class, 'update']);
    Route::delete('/delete', [ListingController::class, 'delete']);
});
