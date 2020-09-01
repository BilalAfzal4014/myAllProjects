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

Route::get('download/sample-file', 'Api\ImportDataController@downloadSampleFile');
Route::get('trackLink', 'ViewInappController@trackLink');

Route::get('campaign/inapp/view/{key}', 'ViewInappController@viewInappURL');
Route::get('campaign/inapp/view/notification/{id}', 'ViewInappController@viewInappNotificationURL');

Route::get('unsubscribe/user', 'UnsubscribeController@unsubscribeEmail')->name('unsubscribe-email');
Route::post('unsubscribe/user', 'UnsubscribeController@unsubscribedUserFromEmail')->name('unsubscribe-user');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('export', 'TestController@index');
Route::get('/backend/attribute-data/download-sample-file', 'Api\AttributeDataController@downloadSampleFile')->name('downloadSampleFile');