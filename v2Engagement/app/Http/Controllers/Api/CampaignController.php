<?php

namespace App\Http\Controllers\Api;

use App\Campaign;
use App\CampaignAction;
use App\Engagment\Campaign\CampaignWrapper;
use App\Http\Middleware\JwtAuth;
use App\Libraries\tv_jwt;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class CampaignController extends Controller
{


    protected $campaignClass;

    public function __construct(CampaignWrapper $campaign)
    {

        $this->campaignClass = $campaign;
        if (env('JWT_ENABLED')) {
            $this->middleware(['jwt.auth', 'jwt.refresh']);
        }
    }


    public function getActionList(Request $request, tv_jwt $jwt)
    {

        try {


            $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CampaignResource";
            $response = (new $class)->getActionList($this->campaignClass, $jwt, $request);


            return new JsonResponse(array(
                "meta" => array(
                    "code" => 200,
                    "status" => "OK",
                ),
                "data" => array("action_list" => $response)
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code" => 401,
                    "status" => $exception->getMessage()
                ),
                "error" => $exception->getMessage()
            ));
        }
    }

    public function createCampaign(Request $request, tv_jwt $jwt)
    {

        try {


            $postedData = $request->all();


            $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CampaignResource";
            $response = (new $class)->storeCampaign($jwt, $postedData, $this->campaignClass,$request);


            return new JsonResponse(array(
                "meta" => array(
                    "code" => 200,
                    "status" => $response,
                ),
                "data" => (object)[]
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code" => 401,
                    "status" => $exception->getMessage()
                ),
                "error" => $exception->getMessage()
            ));
        }
    }

    public function createActionTrigger(Request $request, tv_jwt $jwt)
    {

        try {

            $postedData = $request->all();;

            $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CampaignResource";
            $response = (new $class)->storeTrigger($jwt, $postedData, $this->campaignClass,$request);
            return new JsonResponse(array(
                "meta" => array(
                    "code" => 200,
                    "status" => $response,
                ),
                "data" => (object)[]
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code" => $exception->getCode(),
                    "status" => $exception->getMessage()
                ),
                "error" => $exception->getMessage()
            ));
        }
    }

    public function createTriggerActionCampaign(Request $request, tv_jwt $jwt)
    {

        try {

            $postedData = $request->all();;

            $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CampaignResource";
            $response = (new $class)->actionTriggerCampaign($jwt, $postedData, $this->campaignClass,$request);
            return new JsonResponse(array(
                "meta" => array(
                    "code" => 200,
                    "status" => $response,
                ),
                "data" => (object)[]
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code" => $exception->getCode(),
                    "status" => $exception->getMessage()
                ),
                "error" => $exception->getMessage()
            ));
        }
    }


    public function pushAnalyticalTracking(Request $request, tv_jwt $jwt)
    {
        try {

            $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\CampaignResource";
            $response = (new $class)->analyticalTrackingForPush($jwt, $this->campaignClass, $request);
            return new JsonResponse(array(
                "meta" => array(
                    "code" => 200,
                    "status" => $response,
                ),
                "data" => (object)[]
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code" => $exception->getCode(),
                    "status" => $exception->getMessage()
                ),
                "error" => $exception->getMessage()
            ));
        }
    }


}
