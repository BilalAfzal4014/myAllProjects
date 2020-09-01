<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Libraries\tv_jwt;
use App\Http\Controllers\Controller;
use App\Engagment\AttributeData\AttributeDataWrapper;

class CompanyController extends Controller
{

    public $attributeDataWrapper;


    public function __construct(AttributeDataWrapper $attributeDataWrapper)
    {
        $this->attributeDataWrapper = $attributeDataWrapper;
        if (config('engagement.jwt_enabled')) {
            $this->middleware(['jwt.auth', 'jwt.refresh']);
        }
    }


    public function initializePlatform(Request $request, tv_jwt $jwt){

        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CompanyResource";
        $response = (new $class)->initializePlatform($jwt, $this->attributeDataWrapper,$request);

        return $response;
    }


    public function getUserActions(Request $request, tv_jwt $jwt){

        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CompanyResource";
        $response = (new $class)->getUserActions($jwt, $this->attributeDataWrapper,$request);

        return $response;
    }

    public function initializeApp(Request $request, tv_jwt $jwt)
    {

        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CompanyResource";
        $response = (new $class)->initializeApp($jwt, $this->attributeDataWrapper,$request);

        return $response;
    }

    public function initializeusers(Request $request, tv_jwt $jwt)
    {

        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CompanyResource";
        $response = (new $class)->initializeusers($jwt, $this->attributeDataWrapper,$request);

        return $response;
    }

}
