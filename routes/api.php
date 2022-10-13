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
});

// need ACCESS TOKEN
Route::prefix('V1')->middleware('auth:sanctum')->namespace('App\Http\Controllers\Api\V1')->group(function () {
    // 用户信息
    Route::get('me', 'AuthController@myInfo');
    Route::post('me', 'AuthController@updateMyInfo');
    Route::get('user/{uuid}', 'AuthController@getUserByUuid');
    Route::get('logout', 'AuthController@logout');
    Route::post('name', 'AuthController@updateMyName');  // 后台审核，无论通过还是不通过，都要删除该条记录，留存无意义。如果通过，需要开启事务同步修改当前user_id的用户name
});
