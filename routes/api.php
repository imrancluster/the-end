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


Route::post('/auth/token', 'Api\AuthController@getAccessToken');
Route::post('/auth/reset-password', 'Api\AuthController@passwordResetRequest');
Route::post('/auth/change-password', 'Api\AuthController@changePassword');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// User route
Route::middleware('isAdmin')->get('/users', 'Api\UserController@index');
Route::post('/users', 'Api\UserController@store');
Route::get('/users/activation/{token}', 'Api\UserController@activation');
Route::get('/users/{id}', 'Api\UserController@show');

// Route::resource('users', 'UserController');

Route::resource('roles', 'Api\RoleController');
Route::resource('permissions', 'Api\PermissionController');
Route::resource('notes', 'Api\NoteController');
Route::resource('persons', 'Api\PersonController');
