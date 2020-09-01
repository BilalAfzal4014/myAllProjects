<?php

namespace App\Http\Controllers\Api;

use App\Components\AppStatusCodes;
use App\Components\ParseResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    use ParseResponse;

    public function __construct()
    {
        //$this->middleware(['api.version', 'web']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {

            $user = $request->user();

            $user->authAcessToken()->delete();

//            $request->session()->invalidate();
//            $user->token()->revoke();


            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                'success',
                ['User has been logged out'],
                'data'
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'error',
                [$exception->getMessage()],
//                ['User has already been logged out'],
                'error'
            );
        }
    }
}
