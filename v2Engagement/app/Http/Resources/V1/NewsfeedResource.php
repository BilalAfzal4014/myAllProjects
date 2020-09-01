<?php

namespace App\Http\Resources\V1;

use App\Components\CompanyAttributeData;
use App\Engagment\Newsfeed\Newsfeeds;
use App\Engagment\Segment\SegmentHandler;
use App\Helpers\CommonHelper;
use App\Libraries\tv_jwt;
use App\NewsFeed;
use App\Segment;
use App\User;
use Carbon\Carbon;
use http\Exception\BadMessageException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class NewsfeedResource
{

    /**
     * @var $newsfeeds Newsfeeds
     */
    private $newsfeeds;

    private $location_id;
    public function setNewsFeed($newsfeeds)
    {
        $this->newsfeeds = $newsfeeds;
        return $this;
    }

    private $htmlArr = [];
    private  $newfeedTotal = 0;
    private  $newfeedTotalUnread = 0;
    public function process(Request $request,tv_jwt $jwt)
    {
        try {

            list($postedData,$userObj,$attributeDataData) = $this->newsfeeds->validateRequest($request,$jwt);

            $userAgent = $postedData['device_type'];
            $company_key = $userObj->company_key;
            $company_id = $userObj->id;
            $lang = isset($postedData['lang']) ? $postedData['lang'] : 'en';

            $newfeedObj = new NewsFeed();

            if (!empty($postedData['latitude']) && !empty($postedData['longitude'])) {
                $location_lat = $postedData['latitude'];
                $location_lng = $postedData['longitude'];
                $locations = $this->FindGPSLocations($location_lat, $location_lng,$company_id);
                $this->location_id = array_column($locations, 'id');
                $data['newsLists'] = $newfeedObj->select('news_feed.*', 'news_feed_template.name as template')
                    ->join('news_feed_template', 'news_feed.news_feed_template_id', '=', 'news_feed_template.id')
                    ->leftJoin('segment', 'news_feed.segment_id', '=', 'segment.id')
                    ->leftJoin('location', 'news_feed.location_id', '=', 'location.id')
                    ->join('users', 'news_feed.company_id', '=', 'users.id')
                    ->where('users.company_key', $company_key)
                    ->where('news_feed.is_deleted', 0)
                    ->where('news_feed.status', 'active')
                    ->where('news_feed.start_time', '<=', Carbon::now())
                    ->where(function($q) {
                        $q->where('news_feed.end_time', '>=', Carbon::now())
                            ->orWhereNull('news_feed.end_time');
                    })
                    ->where(function($q) {
                        $q->whereIn('location.id', $this->location_id)
                            ->orWhereNull('news_feed.location_id');
                    })
                    ->orderBy('id', 'desc')->get();


            } else {

                $data['newsLists'] = $newfeedObj->select('news_feed.*', 'news_feed_template.name as template')
                    ->join('news_feed_template', 'news_feed.news_feed_template_id', '=', 'news_feed_template.id')
                    ->leftJoin('segment', 'news_feed.segment_id', '=', 'segment.id')
                    ->join('users', 'news_feed.company_id', '=', 'users.id')
                    ->where('users.company_key', $company_key)
                    ->where('news_feed.is_deleted', 0)
                    ->whereNull('news_feed.location_id')
                    ->where('news_feed.status', 'active')
                    ->where('news_feed.start_time', '<=', Carbon::now())
                    ->where(function($q) {
                        $q->where('news_feed.end_time', '>=', Carbon::now())
                            ->orWhereNull('news_feed.end_time');
                    })
                    ->orderBy('id', 'desc')
                    ->get();
            }


            foreach ($data['newsLists'] as $item) {

                $segemntObj = Segment::where("id", $item->segment_id)->first();
                if ($segemntObj) {

                    $getUserList =   CompanyAttributeData::getSegmentCache($segemntObj);
                    if(in_array($attributeDataData['row_id'],$getUserList)){

                        $this->operateNewsfeed($item,$userAgent,$attributeDataData,$lang);
                    }
                }else{

                    $this->operateNewsfeed($item,$userAgent,$attributeDataData,$lang);
                }



            }
            $data['htmlArr'] = $this->htmlArr;
            $data['lang']=$lang;
            $html = view('newsfeed.getnewslist', $data)->render();
            return new JsonResponse(array(
                "meta" => array(
                    "code"=>200,
                    "status"=>'OK',
                ),
                "data" => array(
                    "total_newsfeed"=> $this->newfeedTotal,
                    "unread_newsfeed"=> $this->newfeedTotalUnread,
                    "content"=> $html

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

    public function processTemplate(Request $request,tv_jwt $jwt)
    {
        try {

//            list($postedData,$userObj,$attributeDataData) = $this->newsfeeds->validateRequest($request,$jwt);
            $postedData = $request->all();
            $company_key = '123';
            $userAgent = 'web';
            $lang = $request->lang ? $request->lang : 'en';
            $attributeDataData['row_id'] ='1122121';
            $newfeedObj = new NewsFeed();


            $data['template'] = CommonHelper::CLASSIC;


            if(isset($postedData['template'])) {
                $data['template'] = $postedData['template'];
            }
            $newfeedTemplate = \DB::table("news_feed_template")->where(["name" => $data['template']])->first();
            if (!$newfeedTemplate) {

                $newfeedTemplate = \DB::table("news_feed_template")->where(["name" => CommonHelper::CLASSIC])->first();
            }


            $data['newsLists'] = $newfeedObj->select('news_feed.*', 'news_feed_template.name as template')
                ->join('news_feed_template', 'news_feed.news_feed_template_id', '=', 'news_feed_template.id')
                ->leftJoin('segment', 'news_feed.segment_id', '=', 'segment.id')
                ->join('users', 'news_feed.company_id', '=', 'users.id')
                ->where('news_feed.is_deleted', 0)
                ->where('news_feed.status', 'active')
                ->orderBy('id', 'desc')
                ->get();



            foreach ($data['newsLists'] as $item) {


                $this->operateNewsfeed($item,$userAgent,$attributeDataData,$lang);

            }


            $data['htmlArr'] = $this->htmlArr;
            $html = view('newsfeed.getnewslist', $data)->render();
            return $html;
            return new JsonResponse(array(
                "meta" => array(
                    "code"=>200,
                    "status"=>'OK',
                ),
                "data" => array(
                    "content"=> $html

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

    public function operateNewsfeed($item,$userAgent,$attributeDataData,$lang)
    {

        $this->newfeedTotal +=1;
        $newfeedTemplate = $item->news_feed_template_id;
        $newfeedTemplate = \DB::table("news_feed_template")->find($newfeedTemplate);

        if(isset($attributeDataData['data']) && isset($attributeDataData['data']['lang'])){

            $lang = $attributeDataData['data']['lang'];
        }

        $newsfeedTranslation = $this->newsfeeds->getNewsfeedTranslation($item->id,$lang);
        if(!$newsfeedTranslation){

            $newsfeedTranslation = $this->newsfeeds->getNewsfeedTranslation($item->id,'en');
        }
        if($newsfeedTranslation) {
            $htmlContent = $newfeedTemplate->content;

            $htmlContent = str_replace("[{id}]", $item->id, $htmlContent);

            if ($userAgent == 'ios') {

                if($item->link_type_ios == 'DEEPLINK'){

                    $htmlContent = str_replace("href=\"#\"", 'target="_blank" href="' . $item->ios_url . '"', $htmlContent);
                }else{

                    $htmlContent = str_replace("href=\"#\"", 'target="_blank" href="' . $this->newsfeeds->campaignHandler->encrypt($item->ios_url, $item->id, 'Newsfeed', $attributeDataData['row_id']) . '"', $htmlContent);
                }
            } elseif ($userAgent == 'android') {
                if($item->link_type_android == 'DEEPLINK'){

                    $htmlContent = str_replace("href=\"#\"", 'target="_blank" href="' . $item->android_url . '"', $htmlContent);
                }else {
                    $htmlContent = str_replace("href=\"#\"", 'target="_blank" href="' . $this->newsfeeds->campaignHandler->encrypt($item->android_url, $item->id, 'Newsfeed', $attributeDataData['row_id']) . '"', $htmlContent);
                }
            } else {

                if($item->link_type_window == 'DEEPLINK'){

                    $htmlContent = str_replace("href=\"#\"", 'target="_blank" href="' . $item->web_url . '"', $htmlContent);
                }else{

                    $htmlContent = str_replace("href=\"#\"", 'target="_blank" href="' . $this->newsfeeds->campaignHandler->encrypt($item->web_url, $item->id, 'Newsfeed', $attributeDataData['row_id']) . '"', $htmlContent);
                }

            }

            if (!empty($newsfeedTranslation->image_url)) {
                $htmlContent = str_replace("[{icon}]", $newsfeedTranslation->image_url, $htmlContent);
            } else {

                $htmlContent = str_replace("[{icon}]", asset('/assets/images/default-news.png.jpeg'), $htmlContent);
            }


            $htmlContent = str_replace("[{title}]", $newsfeedTranslation->title, $htmlContent);
            $htmlContent = str_replace("[{description}]", $newsfeedTranslation->message, $htmlContent);
            $htmlContent = str_replace("[{link_title}]", $newsfeedTranslation->link_text, $htmlContent);


            $checkDuplication = $this->findInNewsFeedDuplication($item->company_id, $item->id, $item->location_id, $userAgent);

            array_push($this->htmlArr, $htmlContent);
            if ($checkDuplication) {

                \DB::table('news_feed_impressions')->where(['id' => $checkDuplication->id])->update(["row_id" => $attributeDataData['row_id'], 'viewed' => $checkDuplication->viewed + 1]);
            } else {

                $this->newfeedTotalUnread +=1;
                \DB::table('news_feed_impressions')->insert([
                    [
                        "row_id" => $attributeDataData['row_id'],
                        'user_id' => $item->company_id,
                        'news_feed_id' => $item->id,
                        'location_id' => $item->location_id,
                        'platform' => $userAgent,
                        'viewed' => 1,
                        'created_date' => Carbon::now()
                    ]
                ]);
            }
        }
    }

    protected function FindGPSLocations($lat, $lng, $companyId,$unit = 'Km')
    {
        if ($unit == 'Km') $circle_radius = 6371; else  $circle_radius = 3959;
        $sql = 'SELECT * FROM
             (SELECT id, (' . $circle_radius . ' * acos(cos(radians(' . $lat . ')) * cos(radians(lat)) *
             cos(radians(lng) - radians(' . $lng . ')) +
             sin(radians(' . $lat . ')) * sin(radians(lat))))
             AS distance, location.radius AS radius
             FROM location where company_id = '.$companyId.') AS distances
             WHERE distance < radius
             ORDER BY distance';
        $result = \DB::select($sql);
        return $resultArray = json_decode(json_encode($result), true);

    }

    protected function findInNewsFeedDuplication($userId, $newsfeedId, $locationId, $userAgent)
    {
        $dataImportList = \DB::table('news_feed_impressions')->where([
                'user_id' => $userId,
                'news_feed_id' => $newsfeedId,
                'location_id' => $locationId,
                'platform' => $userAgent,
            ]
        )->first();

        return $dataImportList;
    }
}