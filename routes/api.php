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

    // 第三方回调
    Route::prefix('callback')->group(function () {
        Route::any('alipay', 'CallbackController@alipay');
        Route::any('wechat', 'CallbackController@wechat');
    });
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

    // 由于可以换技师，在支付成功回调更新技师订单数
    Route::prefix('order')->group(function () {
        Route::get('/', 'OrderController@getAll');
        Route::get('/{order_id}', 'OrderController@getOne');
        Route::post('/', 'OrderController@createOrder');
        Route::delete('/{order_id}', 'OrderController@deleteOrder');
        Route::post('change', 'OrderController@changeTechnician');

        // 支付
        Route::post('/pay', 'OrderController@doPay');
        Route::delete('/pay/{payment_id}', 'OrderController@cancelPay');
        // 退款申请
        Route::post('/pay/refund', 'OrderController@refund');            // （后台处理完退款之后，记得修改order_refund.status=1, order.status=3）
        // 调用隐私保护打电话
        // Route::post('/call', 'OrderController@call');

        // 用户确认完成订单   评分
        Route::post('/finish', 'OrderController@finish');
        Route::post('/rating', 'OrderController@rating');

        // 没有用户完成订单，是需要支付两次，给某个技师，支付后才算完成，更改完成状态
    });
});
