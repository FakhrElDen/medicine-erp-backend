<?php

use Illuminate\Support\Facades\Route;
use Modules\Track\Http\Controllers\TrackController;

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

Route::group(['as' => 'api.', 'prefix' => 'tracks', 'middleware' => ['auth:api', 'localization', 'cache_data']], function () {
    Route::get('/', [TrackController::class, 'index']);
});
