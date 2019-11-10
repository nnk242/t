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

Route::resource('page', 'PageController')->except('create', 'edit', 'update');

Route::prefix('me')->name('me.')->group(function () {
    Route::resource('/', 'MeController')->only('index', 'store');
    Route::get('manager-share', 'MeController@managerShare')->name('managerShare');
    Route::get('share', 'MeController@share')->name('share');
    Route::post('manager-share', 'MeController@updateStatusManagerShare')->name('updateStatusManagerShare');
    Route::get('access-token', 'MeController@getAccessToken')->name('accessToken');
    Route::get('set-access-token', 'MeController@setAccessToken')->name('setAccessToken');
});
