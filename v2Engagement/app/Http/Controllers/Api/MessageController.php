<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function __construct()
    {
        if (config('engagement.jwt_enabled')) {
            $this->middleware(['jwt.auth', 'jwt.refresh']);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->isJson()) {
            $data = $request->json()->all();
        }

        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\MessagingResource";
        $response = (new $class)->process($data);

        return $response;
    }
}
