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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('notifications/{code}', function ($code) {
    $cacheKey = "notifications_{$code}";
    $data = \Cache::get($cacheKey);
    if (!empty($data)) {
        $message = \GuzzleHttp\json_decode($data, true);
        if ((bool)$message['viewed'] === false) {
            $message['viewed'] = true;
            \Cache::forever($cacheKey, \GuzzleHttp\json_encode($message));

            $cacheKey = "notifications_viewed";
            $data = \Cache::get($cacheKey);
            $data = isset($data) ? \GuzzleHttp\json_decode($data) : [];

            if (!in_array($code, $data)) {
                $data[] = $code;
                \Cache::forever($cacheKey, \GuzzleHttp\json_encode($data));
            }
        }

        $data = $message;

        return view('notification', compact('data'));
    }
});

Route::get('campaign/tracking/{key}', 'Api\BackendController@update_tracking');

