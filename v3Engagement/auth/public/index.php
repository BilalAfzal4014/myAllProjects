<?php


header('Access-Control-Allow-Origin: *'); //http://localhost:8080
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: authorization, content-type');

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__ . '/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

//if(strtolower($request->method()) == "options"){
//    header('Access-Control-Allow-Methods: GET, OPTIONS, PUT, DELETE');
//    header('Access-Control-Allow-Headers: authorization');
//}
//else{
//   header('Access-Control-Allow-Origin: *');
//   header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
//   header('Access-Control-Allow-Headers: authorization,content-type');
//}

#//header_remove('Access-Control-Allow-Origin');
#//$response->headers->set('Access-Control-Allow-Origin', '*');
#//$response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
#//$response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

#//header_remove('Access-Control-Allow-Origin');

$response->send();

$kernel->terminate($request, $response);

