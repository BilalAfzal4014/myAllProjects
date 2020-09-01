<?php

namespace App\Http\Controllers\Api;

use App\Engagment\AttributeData\AttributeDataHandler;
use App\Engagment\AttributeData\AttributeDataWrapper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationToggleController extends Controller
{
    public function __construct()
    {
        if (config('engagement.jwt_enabled')) {
            $this->middleware(['jwt.auth', 'jwt.refresh']);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if ($request->isJson()) {
            $data = $request->json()->all();
        }

        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\NotificationResource";

        return (new $class)->process($request, $data);
    }
}
