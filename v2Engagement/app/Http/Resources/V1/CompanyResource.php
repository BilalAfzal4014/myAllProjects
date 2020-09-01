<?php

namespace App\Http\Resources\V1;

use App\AttributeData;
use App\Components\CompanyAttributeData;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use stdClass;
use App\Engagment\AttributeData\AttributeDataWrapper;
use App\Libraries\tv_jwt;
use Symfony\Component\HttpFoundation\JsonResponse;
use Validator;
use DB;

//use App\Libraries\VerifyEmail;
//use App\Messaging\Message;
//use App\Notification;
use App\User;
use Carbon\Carbon;

class CompanyResource
{


    public function initializePlatform($jwt,AttributeDataWrapper $attributeDataWrapper,Request $request)
    {
        try {

            $data = CommonHelper::getUserFromKey($jwt,$request);
            $user = $data['user'];

            $postedData = $request->all();
            $postedData = $attributeDataWrapper->validateRequest($user,$request,$postedData,"platform");
            
            //file_put_contents('storage/logs/signup-'.$user->id.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n InitializePlatform API started:" . PHP_EOL ,FILE_APPEND);
            
            list($postedData,$conversionData,$actionData) = $attributeDataWrapper->validateAttributeDataAndSaveConversionAction($postedData,$user->id);
            
            //file_put_contents('storage/logs/signup-'.$user->id.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n Posted data:". print_r($postedData,true) . PHP_EOL ,FILE_APPEND);
            //file_put_contents('storage/logs/signup-'.$user->id.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n conversion data:". print_r($conversionData,true) . PHP_EOL ,FILE_APPEND);
            //file_put_contents('storage/logs/signup-'.$user->id.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n Action data:". print_r($actionData,true) . PHP_EOL ,FILE_APPEND);
            
            $dataToSaveInCache = $attributeDataWrapper->saveAttributeDataFromSDK($user->id, $postedData);
            if(!$dataToSaveInCache){

                throw new \RuntimeException('invalid app name provided');
            }

            if($actionData){

                $attributeDataWrapper->saveOtherAttributeData($user->id, $actionData, 0, 'action',$dataToSaveInCache['row_id']);

            }

            if($conversionData){

                $attributeDataWrapper->saveOtherAttributeData($user->id, $conversionData, 0, 'conversion',$dataToSaveInCache['row_id']);
            }

            return new JsonResponse(array(
                "meta" => array(
                    "code"=>200,
                    "status"=>'OK',
                ),
                "data" => array(
                    "userData"=>[
                        'user_token' => $postedData['user_token']
                    ]

                )
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code"=>$exception->getCode(),
                    "status"=>'error',
                ),
                "errors" => array(
                    $exception->getMessage()
                )
            ));
        }
    }


    public function initializeApp($jwt,AttributeDataWrapper $attributeDataWrapper,Request $request)
    {

        try {
            $data = CommonHelper::getUserFromKey($jwt,$request);
            
            $user = $data['user'];

            $postedData = $request->all();;
            
            $postedData = $attributeDataWrapper->validateRequest($user,$request,$postedData,"app");

            $dataToSaveInCache = $attributeDataWrapper->intialiazeApp($user->id,$postedData);
            if(!$dataToSaveInCache){

                throw new \RuntimeException('invalid app name provided');
            }

//            if(isset($dataToSaveInCache['data'])) {
//                $userAttributeData = $dataToSaveInCache['data'];
//            }else{
//
//            }
            $userAttributeData = CompanyAttributeData::getUserData($user->id, $postedData);
            return new JsonResponse(array(
                "meta" => array(
                    "code"=>200,
                    "status"=>'OK',
                ),
                "data" => array(
                    "userData"=> $userAttributeData

                )
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code"=>$exception->getCode(),
                    "status"=>'error',
                ),
                "errors" => array(
                    $exception->getMessage()
                )
            ));
        }
    }

    public function getUserActions(tv_jwt $jwt,AttributeDataWrapper $attributeDataWrapper,Request $request)
    {
        try {

            $data = CommonHelper::getUserFromKey($jwt,$request);
            $user = $data['user'];
            $user_token = $data['user_token'];
            $postedData = $request->all();;

            $headerData = apache_request_headers();
            $headerData = CommonHelper::changeHeader($headerData);
            $postedData = $attributeDataWrapper->validateRequest($user,$request,$postedData,"app");

            $cacheKey = CommonHelper::generateCacheKey($user->id,$headerData['app_name'],$postedData['user_id']);
            $attributeObj = Cache::get($cacheKey);

            if(!$attributeObj){

                throw new \RuntimeException("Invalid User");
            }
            $attributeData = json_decode($attributeObj,true);
            $userData = $attributeData['data'];
            if($user_token != $userData['user_token']){

                throw new \RuntimeException("invalid user");
            }

            $getCustomAttributes = $attributeDataWrapper->getCustomUserAttributes($postedData);
            return new JsonResponse(array(
                "meta" => array(
                    "code"=>200,
                    "status"=>'OK',
                ),
                "data" => ["list"=>$getCustomAttributes]
            ));
        } catch (\Exception $exception) {

            return new JsonResponse(array(
                "meta" => array(
                    "code"=>$exception->getCode(),
                    "status"=>'error',
                ),
                "errors" => array(
                    $exception->getMessage()
                )
            ));
        }
    }

    public function initializeusers($jwt, AttributeDataWrapper $attributeDataWrapper, Request $request)
    {

        try {

            $responseMessage = "successfully saved data.";
            $data = CommonHelper::getUserFromKey($jwt, $request);
            $user = $data['user'];
            $userArr = $request->all();
            $requestSize = strlen($request->getContent());

            if(count($userArr) >= config('engagement.max_import_limit')){

                throw new \RuntimeException('Maximum '.config('engagement.max_import_limit'). ' can be imported. Please reduce the number of users');
            }

            if($requestSize >= config('engagement.max_import_limit_size')){

                throw new \RuntimeException('Maximum allowed request size is '. intval(config('engagement.max_import_limit_size')/1028) . ' kb.Please reduce the data size.' );
            }
            list($appNameListing, $appIds) = CommonHelper::getAppList($user->id);

            foreach ($userArr as $postedData) {

                $postedData = CommonHelper::changeHeader($postedData);
                $validator = \Validator::make($postedData, [
                    'user_id' => 'required',
                    'app_name' => 'required|in:' . implode(',', $appNameListing),
                ]);





                if (empty($validator->errors()->all())) {


                    $checkUserCheck = CompanyAttributeData::getUserData($user->id, $postedData);
                    /*skipping use  r as they not exist*/
                    if(!$checkUserCheck){

                        $responseMessage .= ' this user '.$postedData['user_id'].' not exist,';
                        continue;
                    }

                    list($postedData, $conversionData, $actionData) = $attributeDataWrapper->validateAttributeDataAndSaveConversionAction($postedData, $user->id);
                    $dataToSaveInCache = $attributeDataWrapper->saveAttributeData($user->id, $postedData);

                    if ($actionData) {

                        $attributeDataWrapper->saveOtherAttributeData($user->id, $actionData, 0, 'action', $dataToSaveInCache['row_id']);

                    }

                    if ($conversionData) {
                        $attributeDataWrapper->saveOtherAttributeData($user->id, $conversionData, 0, 'conversion', $dataToSaveInCache['row_id']);
                    }
                }else{

                    $userId = isset($postedData['user_id']) ? 'user id: '.$postedData['user_id'] : "";
                    $responseMessage .= '  '.implode(',', $validator->errors()->all()). ' '. $userId;
                }


            }

            return new JsonResponse(array(
                "meta" => array(
                    "code"=>200,
                    "status"=>'OK',
                ),
                "data" => array(
                    "message"=>$responseMessage

                )
            ));
        }catch (\Exception $exception){

            return new JsonResponse(array(
                "meta" => array(
                    "code"=>$exception->getCode(),
                    "status"=>'error',
                ),
                "errors" => array(
                    $exception->getMessage()
                )
            ));
        }
    }
}