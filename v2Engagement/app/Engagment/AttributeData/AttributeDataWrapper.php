<?php

namespace App\Engagment\AttributeData;

use App\Apps;
use App\Components\CompanyAttributeData;
use App\Engagment\AttributeData\AttributeDataHandler;
use App\Helpers\CommonHelper;
use App\Http\Controllers\AttributeDataController;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AttributeDataWrapper
{
    protected $attributeDataHandler;

    public function getListingForDataTable($companyId)
    {

        return $this->attributeDataHandler->getListingForDataTable("", 4000, 0, 'row_id', 'row_id', $companyId, "comssds");
    }

    public function getFileCount($fileId, $comapnyId)
    {

        return $this->attributeDataHandler->getFileCount($fileId, $comapnyId);
    }

    public function removeExtraData($postedData)
    {

        unset($postedData['extra_params']);
        return $postedData;
    }

    public function validateAttributeDataAndSaveConversionAction($postedData, $companyId)
    {

        $attributeData = null;
        $userAttributeData = null;
        $actionAttributeData = null;
        $conversionAttributeData = null;

        if (isset($postedData['extra_params']) and !empty($postedData['extra_params'])) {

            $extraParams = $postedData['extra_params'];
            $userAttributeData = isset($extraParams['user_data']) ? $extraParams['user_data'] : null;
            $actionAttributeData = isset($extraParams['action_data']) ? $extraParams['action_data'] : null;
            $conversionAttributeData = isset($extraParams['conversion_data']) ? $extraParams['conversion_data'] : null;

        }

        if ($userAttributeData) {

            $postedData = $this->removeExtraData($postedData);
            $postedData = array_merge($postedData, $userAttributeData);
        }

        return array($postedData, $conversionAttributeData, $actionAttributeData);
    }

    public function validateRequest($user, $request, $postedData, $type)
    {

        $headerData = CommonHelper::validateHeader($request,$user);

        if ($type == "platform") {

            if (empty($postedData['extra_params'])) {

                $validator = Validator::make($postedData, [
                    'user_id' => 'required',
                    'email' => 'required',
                ]);
            } else {

                $validator = Validator::make($postedData, [
                    'user_id' => 'required',
                ]);

            }

            if ($validator->errors()->all()) {

                throw new \RuntimeException(implode(',', $validator->errors()->all()), 411);
            }

            if (!isset($postedData['is_active'])) {

                $postedData['is_active'] = 1;
            }

            $postedData['is_import'] = 0;
        } else if ($type == "app") {

            $validator = Validator::make($postedData, [
                'user_id' => 'required',
            ]);
            if ($validator->errors()->all()) {

                throw new \RuntimeException(implode(',', $validator->errors()->all()), 411);
            }

        }

        $postedData['user_token'] = $userToken = bin2hex(random_bytes(32));
        $postedData = array_merge($postedData, $headerData);
        $postedData = CommonHelper::removeExtraHeader($postedData);
        return $postedData;
    }


    public function __construct(AttributeDataHandler $attributeDataHandler)
    {
        $this->attributeDataHandler = $attributeDataHandler;
    }

    public function getAttributeData()
    {
        return $this->attributeDataHandler->getAttributeData();
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function generateCacheKey($companyId, $appName, $userId)
    {
        return $companyId . "_" . strtolower(str_replace(" ", "_", $appName)) . "_" . $userId;

    }

    public function getAttributeDataFromCache($cacheKey)
    {

        $data = Cache::get($cacheKey);
        if (!empty($data)) {
            return json_decode($data, true);
        }

        return null;
    }

    public function operateDelete($companyId, $appName, $userId, $action)
    {

        $dataToUpdate['is_active'] = $action;
        $dataToUpdate['app_name'] = $appName;
        $dataToUpdate['user_id'] = $userId;
        $this->intialiazeApp($companyId, $dataToUpdate, 'admin');
        return true;
    }

    public function saveAttributeData($companyId, $postedData, $importDataId = false)
    {

        $cacheKey = CommonHelper::generateCacheKey($companyId, $postedData['app_name'], $postedData['user_id']);
        $dataFromCache = Cache::get($cacheKey);

        $dataToSaveInCache = $this->attributeDataHandler->storeAttributeData($postedData, $companyId, $dataFromCache, $importDataId);

        if (!$dataToSaveInCache) {

            return null;
        }

        Cache::forget($cacheKey);
        Cache::forever($cacheKey, json_encode($dataToSaveInCache));

        $postedData['row_id'] = $dataToSaveInCache['row_id'];
        return $postedData;
    }
    public function saveAttributeDataFromSDK($companyId, $postedData, $importDataId = false)
    {

        $cacheKey = CommonHelper::generateCacheKey($companyId, $postedData['app_name'], $postedData['user_id']);
        $dataFromCache = Cache::get($cacheKey);       
        $dataToSaveInCache = $this->attributeDataHandler->storeAttributeDataFromSDK($postedData, $companyId, $dataFromCache, $importDataId);
        //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n in saveAttributeDataFromSDK function - dataToSaveInCache is: " .print_r($dataToSaveInCache, true). PHP_EOL ,FILE_APPEND);
        if (!$dataToSaveInCache) {

            return null;
        }

        Cache::forget($cacheKey);
        Cache::forever($cacheKey, json_encode($dataToSaveInCache));

        $postedData['row_id'] = $dataToSaveInCache['row_id'];
        return $postedData;
    }
    public function intialiazeApp($companyId, $postedData, $adminControl = null)
    {

        $cacheKey = CommonHelper::generateCacheKey($companyId, $postedData['app_name'], $postedData['user_id']);
        $dataFromCache = Cache::get($cacheKey);

        if (empty($dataFromCache)) {

            $checkAndRowId = CompanyAttributeData::getUserData($companyId, $postedData);
            if (!$checkAndRowId) {

                throw new \RuntimeException("User not register");

            }
            $extraData = CommonHelper::getAttributeDataExtra($checkAndRowId->row_id,$companyId);
            $dataTOSaveInCache = (object) array_merge((array) $extraData, $checkAndRowId->toArray()    );

            $dataTOSave = json_encode(array("row_id" => $checkAndRowId->row_id, "company_id" => $companyId, "data" => $dataTOSaveInCache));

            Cache::forget($cacheKey);
            Cache::forever($cacheKey, $dataTOSave);

        }

        $postedData['last_login'] = Carbon::now();
        $dataToSaveInCache = $this->attributeDataHandler->storeAttributeData($postedData, $companyId, $dataFromCache);
        if (!$dataToSaveInCache) {

            return null;
        }
        Cache::forget($cacheKey);
        Cache::forever($cacheKey, json_encode($dataToSaveInCache));

        return $dataToSaveInCache;
    }

    public function saveOtherAttributeData($companyId, $formatedArr, $importDataId = null, $dataType, $maxRawId = null)
    {
        $this->attributeDataHandler->saveOtherAttribute($companyId, $formatedArr, $importDataId = null, $dataType, $maxRawId);

    }


    public function getOtherAttributeDataDT($request, $companyId)
    {
        return list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataHandler->getOtherAttributeDataDT($request, $companyId);
    }


    public function getEmailListDT($request, $companyId)
    {
        return list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataHandler->getEmailListDT($request, $companyId);
    }


    public function getImportFileListing($request, $companyId)
    {
        return list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataHandler->getImportFileListing($request, $companyId);
    }

    public function getAttributeDataListing($request, $companyId)
    {
        return list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataHandler->getAttributeDataListing($request, $companyId);
    }

    public function getProfileDetails($companyId)
    {
        return $this->attributeDataHandler->getProfileDetails($companyId);
    }

    public function getCustomAttributes($companyId)
    {
        return $this->attributeDataHandler->getCustomAttributes($companyId);
    }

    public function getTokens($companyId)
    {
        return $this->attributeDataHandler->getTokens($companyId);
    }

    public function getCampaignClick($companyId)
    {
        return $this->attributeDataHandler->getCampaignClick($companyId);
    }

    public function getSegmentsInfo($companyId)
    {
        return $this->attributeDataHandler->getSegmentsInfo($companyId);
    }

    public function getNewsfeedClick($rowId)
    {
        return $this->attributeDataHandler->getNewsfeedClickUser($rowId);
    }

    public function getUserAttributeData($rowId)
    {
        return $this->attributeDataHandler->getUserObject($rowId);
    }

    public function getAttributeDataExtra($rowId, $comapanyID, $type = null)
    {

        return $this->attributeDataHandler->getAttributeExtraData($rowId, $comapanyID, $type);
    }

    public function getCampaignUserStatType($type, $rowId)
    {
        return $this->attributeDataHandler->getCampaignUserType($type, $rowId);
    }

    public function getCampaignConversionStatType($type, $rowId)
    {
        return $this->attributeDataHandler->getCampaignConversionType($type, $rowId);
    }

    public function getCompanyLastLogin($companyId, $rowId)
    {
        return $this->attributeDataHandler->getCompanyLastLogin($companyId, $rowId);
    }

    public function getCompanyAppListing($companyId, $rowId)
    {
        return $this->attributeDataHandler->getCompanyAppListing($companyId, $rowId);
    }

    public function getCustomUserAttributes($postedData)
    {
        return $this->attributeDataHandler->getCustomUserAttributes($postedData);
    }

    public function successResponse($message, $data)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 200,
            'error' => false,
            'message' => $message,
            'data' => $data,
        ));
    }

    public function failedResponse($message)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 400,
            'error' => true,
            'message' => $message,
            'data' => '',
        ));
    }

    public function conversionAndAction($filePath, $companyId, $importDataId, $dataType)
    {
        $this->attributeDataHandler->conversionAndAction($filePath, $companyId, $importDataId, $dataType);
    }

}