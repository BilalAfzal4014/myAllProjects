<?php

namespace App\Engagment\Newsfeed;

use App\Engagment\Campaign\CampaignHandler;
use App\Engagment\Newsfeed\NewsfeedHandler;
use App\Helpers\CommonError;
use Carbon\Carbon;
use CommonHelper;
use Illuminate\Support\Facades\Validator;

class Newsfeeds
{
    protected $newsfeed;

    /**
     * @var CampaignHandler $campaignHandler
     */
    public $campaignHandler;

    public function __construct(NewsfeedHandler $newsfeed, CampaignHandler $campaignHandler)
    {
        $this->newsfeed = $newsfeed;
        $this->campaignHandler = $campaignHandler;
    }

    public function validateRequest($request,$jwt)
    {

        /*decoding jwt*/
        $data = \App\Helpers\CommonHelper::getUserFromKey($jwt,$request);
        $user = $data['user'];

        /*getting and validating user header*/
        $headerData = \App\Helpers\CommonHelper::validateHeader($request,$user);

        $postedData = $request->all();
        $validator = Validator::make($postedData, [
            'user_id' => 'required',
        ]);

        if (!empty($validator->errors()->all())) {

            throw new \RuntimeException(implode(',',$validator->errors()->all()),411);
        }

        $cacheKey = \App\Helpers\CommonHelper::generateCacheKey($user->id, $headerData['app_name'], $postedData['user_id']);
        $attributeDataObj = \Cache::get($cacheKey);
        if (!$attributeDataObj) {

            throw new \RuntimeException(CommonError::INVALID_USER_ID, CommonError::STATUS_CODE_LENGTH_REQUIRED);
        }

        $userFromCache = json_decode($attributeDataObj, true);
        $postedData = array_merge($headerData,$postedData);
        $postedData = \App\Helpers\CommonHelper::removeExtraHeader($postedData);


        return array($postedData,$user,$userFromCache);
    }
    public function getNewsfeedTranslation($newsfeedId,$language)
    {

        return $this->newsfeed->getNewsfeedTranslation($newsfeedId,$language);
    }
    public function saveNewsfeed($companyId,$request)
    {
        return $this->newsfeed->saveNewsfeed($companyId,$request);
    }
    public function newsfeedDuplication($companyId,$name,$column,$newsfeedId = 0)
    {
        return $this->newsfeed->checkNewsFeedDuplication($name,$companyId,$column,$newsfeedId);
    }
    public function saveNewsfeedLanguage($request,$companyId)
    {
        return $this->newsfeed->saveNewsfeedTranslation($request->neesfeedId,$request->title,$request->description,'ar',$companyId,$request->newsfeedTraslationId);
    }
    public function getViewAndClickCount($newsfeedId)
    {
        return $this->newsfeed->getViewAndClickCount($newsfeedId);
    }

    public function getNewsfeedBySearch($companyId,$request){

        return $this->newsfeed->getNewsfeedBySearch($companyId,$request);
    }

    public function fetchDataTOCreate($companyId){

        return $this->newsfeed->fetchDataTOCreate($companyId);
    }


    public function newsfeedsListing($request, $companyId)
    {

        list($totalData, $totalFiltered, $newsFeedListing) = $this->newsfeed->newsfeedsListing($request, $companyId);

        $newsFeedIdArr = [];
        $commonHelper = new \App\Helpers\CommonHelper();
        if ($newsFeedListing) {
            foreach ($newsFeedListing as $key => $newsFeedRow) {
                $newsFeedIdArr[] = $newsFeedRow->id;
                $newsFeedListing[$key]['reachableUsers'] = count($commonHelper->getUserAgainstNewFeed($newsFeedRow->id));
                $newsFeedListing[$key]['minClick'] = 0;
                $newsFeedListing[$key]['maxClick'] = 0;
                $newsFeedListing[$key]['countClickIphone'] = 0;
                $newsFeedListing[$key]['countClickAndroid'] = 0;
                $newsFeedListing[$key]['countClickDesktop'] = 0;

                $newsFeedListing[$key]['minViewed'] = 0;
                $newsFeedListing[$key]['maxViewed'] = 0;
                $newsFeedListing[$key]['sumViewedIphone'] = 0;
                $newsFeedListing[$key]['sumViewedAndroid'] = 0;
                $newsFeedListing[$key]['sumViewedDesktop'] = 0;
            }
            if (!empty($newsFeedIdArr)) {

                $newsFeedIds = implode($newsFeedIdArr, ',');
                $clickIdsArr = [];
                $minMaxData = $this->newsfeed->getMinMaxClickByFD($newsFeedIds);
                foreach ($minMaxData as $minMaxRow) {
                    $clickIdsArr[$minMaxRow->newsFeedId]['minClick'] = $minMaxRow->minClick;
                    $clickIdsArr[$minMaxRow->newsFeedId]['maxClick'] = $minMaxRow->maxClick;
                }
                $minMaxData = $this->newsfeed->getCountClickByFD($newsFeedIds, 'iphone');
                foreach ($minMaxData as $minMaxRow) {
                    $clickIdsArr[$minMaxRow->newsFeedId]['countClickIphone'] = $minMaxRow->countClick;
                }
                $minMaxData = $this->newsfeed->getCountClickByFD($newsFeedIds, 'android');
                foreach ($minMaxData as $minMaxRow) {
                    $clickIdsArr[$minMaxRow->newsFeedId]['countClickAndroid'] = $minMaxRow->countClick;
                }
                $minMaxData = $this->newsfeed->getCountClickByFD($newsFeedIds, 'desktop');
                foreach ($minMaxData as $minMaxRow) {
                    $clickIdsArr[$minMaxRow->newsFeedId]['countClickDesktop'] = $minMaxRow->countClick;
                }

                $viewIdsArr = [];
                $minMaxViewIdsData = $this->newsfeed->getMinMaxViewByFD($newsFeedIds);
                foreach ($minMaxViewIdsData as $minMaxClickIdsRow) {
                    $viewIdsArr[$minMaxClickIdsRow->newsFeedId]['minViewed'] = $minMaxClickIdsRow->minViewed;
                    $viewIdsArr[$minMaxClickIdsRow->newsFeedId]['maxViewed'] = $minMaxClickIdsRow->maxViewed;
                }
                $sumViewData = $this->newsfeed->getSumViewByFD($newsFeedIds, 'iphone');
                foreach ($sumViewData as $sumViewRow) {
                    $viewIdsArr[$sumViewRow->newsFeedId]['sumViewedIphone'] = $sumViewRow->sumViewed;
                }
                $sumViewData = $this->newsfeed->getSumViewByFD($newsFeedIds, 'android');
                foreach ($sumViewData as $sumViewRow) {
                    $viewIdsArr[$sumViewRow->newsFeedId]['sumViewedAndroid'] = $sumViewRow->sumViewed;
                }
                $sumViewData = $this->newsfeed->getSumViewByFD($newsFeedIds, 'desktop');
                foreach ($sumViewData as $sumViewRow) {
                    $viewIdsArr[$sumViewRow->newsFeedId]['sumViewedDesktop'] = $sumViewRow->sumViewed;
                }


                foreach ($newsFeedListing as $key => $newsFeedRow) {

                    $newsFeedId = $newsFeedRow->id;

                    if (isset($clickIdsArr[$newsFeedId])) {

                        $newsFeedListing[$key]['minClick'] = (isset($clickIdsArr[$newsFeedId]['minClick'])) ? $clickIdsArr[$newsFeedId]['minClick'] : 0;
                        $newsFeedListing[$key]['maxClick'] = (isset($clickIdsArr[$newsFeedId]['maxClick'])) ? $clickIdsArr[$newsFeedId]['maxClick'] : 0;
                        $newsFeedListing[$key]['countClickIphone'] = (isset($clickIdsArr[$newsFeedId]['countClickIphone'])) ? $clickIdsArr[$newsFeedId]['countClickIphone'] : 0;
                        $newsFeedListing[$key]['countClickAndroid'] = (isset($clickIdsArr[$newsFeedId]['countClickAndroid'])) ? $clickIdsArr[$newsFeedId]['countClickAndroid'] : 0;
                        $newsFeedListing[$key]['countClickDesktop'] = (isset($clickIdsArr[$newsFeedId]['countClickDesktop'])) ? $clickIdsArr[$newsFeedId]['countClickDesktop'] : 0;
                    }
                    if (isset($viewIdsArr[$newsFeedId])) {
                        $newsFeedListing[$key]['minViewed'] = (isset($viewIdsArr[$newsFeedId]['minViewed'])) ? $viewIdsArr[$newsFeedId]['minViewed'] : 0;
                        $newsFeedListing[$key]['maxViewed'] = (isset($viewIdsArr[$newsFeedId]['maxViewed'])) ? $viewIdsArr[$newsFeedId]['maxViewed'] : 0;
                        $newsFeedListing[$key]['sumViewedIphone'] = (isset($viewIdsArr[$newsFeedId]['sumViewedIphone'])) ? $viewIdsArr[$newsFeedId]['sumViewedIphone'] : 0;
                        $newsFeedListing[$key]['sumViewedAndroid'] = (isset($viewIdsArr[$newsFeedId]['sumViewedAndroid'])) ? $viewIdsArr[$newsFeedId]['sumViewedAndroid'] : 0;
                        $newsFeedListing[$key]['sumViewedDesktop'] = (isset($viewIdsArr[$newsFeedId]['sumViewedDesktop'])) ? $viewIdsArr[$newsFeedId]['sumViewedDesktop'] : 0;
                    }
                }
            }
        }
        return list($totalData, $totalFiltered, $newsFeedListing) = [$totalData, $totalFiltered, $newsFeedListing];
    }


    public function mobilePlateformStatics($where, $screen, $fromDate, $toDate)
    {

        $return[] = $this->newsfeed->getAndroidClick('COUNT', $where);
        $return[] = $this->newsfeed->getIphoneClick('COUNT', $where);
        $return[] = $this->newsfeed->getAndroidViews('COUNT', $where);
        $return[] = $this->newsfeed->getIphoneViews('COUNT', $where);
        if ($screen == 'VIEW') {

            $fromDateObj = Carbon::parse($fromDate);
            $toDateObj = Carbon::parse($toDate);
            $daysCount = $fromDateObj->diffInDays($toDateObj);
            $monthCount = $fromDateObj->diffInMonths($toDateObj);
//            dd($daysCount);
            if ($daysCount == 0) {//hour

                $intervalType = 'COUNT-BY-HOUR';
                $intervalArr = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23];
            }
//            elseif ( $daysCount > 1 && $daysCount <= 6  ){//week
//
//                $intervalType = 'COUNT-BY-WEEK';
//                $intervalArr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
//            }
            elseif ($daysCount >= 1 && $monthCount == 0) {//days

                $intervalType = 'COUNT-BY-DAY';
                $intervalArr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
            } elseif ($monthCount > 0) {//month

                $intervalType = 'COUNT-BY-MONTH';
                $intervalArr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            }
            $clickAndroidData = $this->newsfeed->getAndroidClick($intervalType, $where);
            $clickIphoneData = $this->newsfeed->getIphoneClick($intervalType, $where);
            $viewAndroidData = $this->newsfeed->getAndroidViews($intervalType, $where);
            $viewIphoneData = $this->newsfeed->getIphoneViews($intervalType, $where);
            //--------------
            //-click section
            //--------------
            $clickAndroidArr = [];
            $clickIphoneArr = [];
            foreach ($clickAndroidData as $clickAndroidRow) {
                $clickAndroidArr[ltrim($clickAndroidRow->intervalName, '0')] = (int)$clickAndroidRow->count;
            }
            foreach ($clickIphoneData as $clickIphoneRow) {
                $clickIphoneArr[ltrim($clickIphoneRow->intervalName, '0')] = (int)$clickIphoneRow->count;
            }
//            $clickAndroidByIntervalArr = [];
//            $clickIphoneByIntervalArr = [];
//            foreach ($intervalArr as $intervalRow) {
//                $clickAndroidByIntervalArr[] = (isset($clickAndroidArr[$intervalRow])) ? $clickAndroidArr[$intervalRow] : 0;
//                $clickIphoneByIntervalArr[] = (isset($clickIphoneArr[$intervalRow])) ? $clickIphoneArr[$intervalRow] : 0;
//            }
            //-------------
            //-view section
            //-------------
            $viewAndroidArr = [];
            $viewIphoneArr = [];
            foreach ($viewAndroidData as $viewAndroidRow) {
                $viewAndroidArr[ltrim($viewAndroidRow->intervalName, '0')] = (int)$viewAndroidRow->count;
            }
            foreach ($viewIphoneData as $viewIphoneRow) {
                $viewIphoneArr[ltrim($viewIphoneRow->intervalName, '0')] = (int)$viewIphoneRow->count;
            }
            $clickAndroidByIntervalArr = [];
            $clickIphoneByIntervalArr = [];
            $viewAndroidByIntervalArr = [];
            $viewIphoneByIntervalArr = [];
            $clickThroughAndroidByIntervalArr = [];
            $clickThroughIphoneByIntervalArr = [];
            foreach ($intervalArr as $intervalRow) {

                $clickAndroidByIntervalArr[] = $clickAndroidByInterval = (isset($clickAndroidArr[$intervalRow])) ? $clickAndroidArr[$intervalRow] : 0;
                $clickIphoneByIntervalArr[] = $clickIphoneByInterval = (isset($clickIphoneArr[$intervalRow])) ? $clickIphoneArr[$intervalRow] : 0;
                $viewAndroidByIntervalArr[] = $viewAndroidByInterval = (isset($viewAndroidArr[$intervalRow])) ? $viewAndroidArr[$intervalRow] : 0;
                $viewIphoneByIntervalArr[] = $viewIphoneByInterval = (isset($viewIphoneArr[$intervalRow])) ? $viewIphoneArr[$intervalRow] : 0;

                $clickThroughAndroidByIntervalArr[] = (!empty($viewAndroidByInterval)) ? ($clickAndroidByInterval / $viewAndroidByInterval) * 100 : 0;
                $clickThroughIphoneByIntervalArr[] = (!empty($viewIphoneByInterval)) ? ($clickIphoneByInterval / $viewIphoneByInterval) * 100 : 0;
            }
            $return[] = $clickAndroidByIntervalArr;
            $return[] = $clickIphoneByIntervalArr;
            $return[] = $viewAndroidByIntervalArr;
            $return[] = $viewIphoneByIntervalArr;
            $return[] = $clickThroughAndroidByIntervalArr;
            $return[] = $clickThroughIphoneByIntervalArr;
            $return[] = $intervalArr;
        } else {
            $return[] = 0;
            $return[] = 0;
            $return[] = 0;
            $return[] = 0;
            $return[] = 0;
            $return[] = 0;
            $return[] = 0;
        }
        return $return;
    }


    function performanceStatics($newsFeedId)
    {

        $maxClickByDay = $this->newsfeed->getMaxClickByDay($newsFeedId);
        $minClickByDay = $this->newsfeed->getMinClickByDay($newsFeedId);
        $maxViewByDay = $this->newsfeed->getMaxViewByDay($newsFeedId);
        $minViewByDay = $this->newsfeed->getMinViewByDay($newsFeedId);

        return [$maxClickByDay, $minClickByDay, $maxViewByDay, $minViewByDay];
    }

}