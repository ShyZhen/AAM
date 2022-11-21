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
    Route::post('login-code', 'AuthController@loginCodeCrul');
    Route::post('login', 'AuthController@loginCurl');

    Route::get('start', 'BootstrapController@start');
    Route::get('book-check', 'BootstrapController@bookCheck');

    Route::get('shops', 'ShopController@getAll');
    Route::get('shop/{uuid}', 'ShopController@getOne');

    Route::get('technician', 'TechnicianController@getAll');
    Route::get('technician/{uuid}', 'TechnicianController@getOne');

    Route::get('service', 'ServiceController@getAll');
    Route::get('service/{uuid}', 'ServiceController@getOne');
});

// need ACCESS TOKEN
Route::prefix('V1')->middleware('auth:sanctum')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    // 用户信息
    Route::get('me', 'AuthController@myInfo');
    Route::post('me', 'AuthController@updateMyInfo');
    Route::get('user/{uuid}', 'AuthController@getUserByUuid');
    Route::get('logout', 'AuthController@logout');

    // 关注、取关某人
    Route::post('follow/{userUuid}', 'UserController@follow');
    Route::get('follow/status/{userUuid}', 'UserController@status');
    Route::get('follow/list/{userUuid}', 'UserController@getFollowsList');

    Route::get('h5config', 'BootstrapController@h5config');

    Route::prefix('order')->group(function () {

    });
});
