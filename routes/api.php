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


Route::post('/auth/token', 'AuthController@getAccessToken');
Route::post('/auth/reset-password', 'AuthController@passwordResetRequest');
Route::post('/auth/change-password', 'AuthController@changePassword');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
