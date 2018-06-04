<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

/**
 * User
 */
Route::get('users/living/{token}', 'Api\UserController@living');

Route::get('generate-pdf','HomeController@generatePDF');



Auth::routes();