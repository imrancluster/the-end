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

// User route
Route::middleware('isAdmin')->get('/users', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::get('/users/activation/{token}', 'UserController@activation');
Route::get('/users/{id}', 'UserController@show');

// Route::resource('users', 'UserController');

Route::resource('roles', 'RoleController');
Route::resource('permissions', 'PermissionController');
Route::resource('notes', 'NoteController');
Route::resource('persons', 'PersonController');
