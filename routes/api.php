<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return 'welcome to API Routes';
});

// no ACCESS TOKEN
Route::prefix('V1')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    Route::get('start1', 'AuthController@test');
});

// need ACCESS TOKEN
Route::prefix('V1')->middleware('auth:sanctum')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    Route::get('start2', 'AuthController@getUser');
});
