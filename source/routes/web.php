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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['namespace' => 'Facebook', 'prefix' => 'facebook'], function () {
    Route::get('webhook', 'WebHookController@webhook');
    Route::post('webhook', 'WebHookController@store');
});

Route::resource('page', 'PageController')->except('create', 'edit', 'update');

Route::group(['namespace' => 'Me', 'prefix' => 'me', 'as' => 'me.'], function () {
    Route::resource('/', 'MeController')->only('index', 'store');
    Route::resource('manager-share', 'ManagerShareController')->only('index', 'store');
    Route::resource('share', 'ShareController')->only('index', 'store');
    Route::get('access-token', 'MeController@getAccessToken')->name('access-token');
    Route::get('set-access-token', 'MeController@setAccessToken')->name('set-access-token');
});
