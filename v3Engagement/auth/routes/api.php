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

Route::post('login', 'Api\LoginController@store')->name('api.login');
Route::post('forgot', 'Api\LoginController@forgot')->name('api.forgot');
Route::post('signup', 'Api\LoginController@signup')->name('api.signup');
Route::post('language', 'Api\LoginController@language')->name('api.login');

Route::group(['middleware' => ['api.auth:api']], function () {
    Route::resource('imports', 'Api\ImportDataController');

    Route::post('/', 'Api\HomeController@store')->name('api.home');
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', 'Api\LogoutController@store')->name('api.logout');
});