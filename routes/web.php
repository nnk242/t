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
    Route::resource('/', 'Me\MeController')->only('index', 'store');
    Route::resource('manager-share', 'Me\ManagerShareController')->only('index', 'store');
    Route::resource('share', 'Me\ShareController')->only('index', 'store');
//    Route::post('manager-share', 'MeController@managerShareStore')->name('managerShare');
//    Route::get('share', 'MeController@share')->name('share.index');
//    Route::post('share', 'MeController@shareStore')->name('share');
//    Route::post('manager-share', 'MeController@updateStatusManagerShare')->name('update-status-manager-share');
    Route::get('access-token', 'Me\MeController@getAccessToken')->name('access-token');
    Route::get('set-access-token', 'Me\MeController@setAccessToken')->name('set-access-token');
});
