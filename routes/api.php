<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace'=>'Api','middleware'=>'check'],function(){
    Route::post('/register','ApiController@register');
    Route::post('/login','ApiController@login');
    Route::post('/change','ApiController@changePassword');
    Route::post('/update','ApiController@updateInfo');
    Route::post('/getNotice','ApiController@getNotice');
    Route::post('/read','ApiController@noticeRead');
    Route::post('/addFeedback','ApiController@addFeedback');
    Route::post('/get','ApiController@getEth');

    // 发送短信
    Route::post('/send','CodeController@message');
});
