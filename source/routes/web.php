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

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login/gmail/redirect', 'Auth\LoginController@redirectToProvider')->name('login.gmail.redirect');
Route::get('/login/gmail/callback', 'Auth\LoginController@handleProviderCallback')->name('login.gmail.callback');

//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['namespace' => 'Facebook', 'prefix' => 'facebook'], function () {
    Route::get('webhook', 'WebHookController@webhook');
    Route::post('webhook', 'WebHookController@store');
});

Route::group(['namespace' => 'Process', 'prefix' => 'process'], function () {
    Route::get('/{id}', 'ProcessController@index')->middleware('role.page');
});

Route::resource('page', 'PageController')->except('create', 'edit', 'update');

Route::resource('role', 'RoleController')->only('index', 'store', 'destroy')->middleware('role.user');
Route::resource('message', 'MessageController')->except('update', 'show');
Route::get('message/search/data', 'MessageController@searchData')->name('search-data');
Route::get('message/search/data/head-event', 'MessageController@searchDataHeadEvent')->name('search-data-head-event');
Route::put('message/update/status/{id}', 'MessageController@updateStatus')->name('update-status');
Route::get('message/count/message', 'MessageController@countMessage')->name('count-message');

Route::group(['namespace' => 'Me', 'prefix' => 'me', 'as' => 'me.'], function () {
    Route::resource('/', 'MeController')->only('index', 'store');
    Route::resource('manager-share', 'ManagerShareController')->only('index', 'store', 'destroy');
    Route::resource('share', 'ShareController')->only('index', 'store');
    Route::resource('page-use', 'PageUseController')->only('index', 'store');
    Route::get('access-token', 'MeController@getAccessToken')->name('access-token');
    Route::get('set-access-token', 'MeController@setAccessToken')->name('set-access-token');

    ##save page selected
    Route::post('page-selected', 'MeController@pageSelected')->name('page-selected');
});

Route::resource('event', 'Event\EventController');

Route::resource('gift', 'Gift\GiftController');

Route::group(['namespace' => 'Setting', 'prefix' => 'setting', 'as' => 'setting.'], function () {
    Route::get('index', 'SettingController@index');
    Route::get('/', 'SettingController@index')->name('index');
    Route::resource('message', 'MessageController');
    ##message-head
    Route::get('message-head', 'MessageController@messageHead')->name('message-head');
    Route::post('message-head', 'MessageController@storeMessageHead')->name('store-message-head');
    Route::get('message-head/{id}', 'MessageController@showMessageHead')->name('show-message-head');
    Route::get('message-head/{id}/edit', 'MessageController@editMessageHead')->name('edit-message-head');
    Route::delete('message-head/{id}', 'MessageController@destroyMessageHead')->name('destroy-message-head');

    Route::get('message-reply', 'MessageController@messageReply')->name('message-reply');
    Route::resource('persistent-menu', 'PersistentMenuController')->except('edit');
//    Route::resource('message', 'MessageController')->only('index', 'store');
});

//test
Route::get('test/text', 'TestController@text');
Route::get('test/', 'TestController@index');
