<?php

namespace App\Engagment\Newsfeed;

use App\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\NewsFeed;

use App\Segment;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\NewsFeedTranslation;

class NewsfeedHandler
{
    protected $newsfeed;

    public function __construct(NewsFeed $newsfeed)
    {
        $this->newsfeed = $newsfeed;
    }

    public function saveNewsfeed($companyId, $request)
    {

        try {

            $newsfeedId = 0;
            if ($request->newsfeedId != 0) {
                $newsfeedId = $request->newsfeedId;
                $newfeedObj = NewsFeed::find($request->newsfeedId);
                $newsFeedTranslation = DB::table('news_feed_translation')->where("news_feed_id", $request->newsfeedId)->where("language", 'en')->first();
            } else {
                $newfeedObj = new NewsFeed;
                $newfeedObj->created_by = $companyId;

            }

            $newfeedObj->company_id = $companyId;
            $newfeedObj->step = $request->newsfeedStep;

            if (!empty($request->type_id)) {
                $newfeedObj->news_feed_template_id = $request->type_id;
            }

            if (!empty($request->newsfeed_category)) {
                $newfeedObj->category = $request->newsfeed_category;
            }

            if (!empty($request->name)) {

                $newfeedObj->name = $request->name;
                if ($this->checkNewsFeedDuplication($request->name, $companyId, 'name', $newsfeedId)) {
                    throw new \RuntimeException('name already exist');
                }
            }

            if (!empty($request->newstags)) {
                $newfeedObj->tags = $request->newstags;
            }

            if (isset($request->text_link)) {
                $newfeedObj->link_text = $request->text_link;
            }


            if (isset($request->link_type_android)) {
                $newfeedObj->link_type_android = $request->link_type_android;
            }

            if (isset($request->android_url)) {
                $newfeedObj->android_url = $request->android_url;
            }


            if (isset($request->link_type_ios)) {
                $newfeedObj->link_type_ios = $request->link_type_ios;
            }

            if (isset($request->ios_url)) {
                $newfeedObj->ios_url = $request->ios_url;
            }

            if (isset($request->link_type_window)) {
                $newfeedObj->link_type_window = $request->link_type_window;
            }

            if (isset($request->window_url)) {
                $newfeedObj->window_url = $request->window_url;
            }

            if (isset($request->link_type_web)) {
                $newfeedObj->link_type_web = $request->link_type_web;
            }

            if (isset($request->web_url)) {
                $newfeedObj->web_url = $request->web_url;
            }


            if ($request->name) {
                $newfeedObj->save();
                foreach ($request->langArr as $reqTranslator) {
                    $translatorObj = NewsFeedTranslation::where('news_feed_id', $newsfeedId)
                        ->where('language', $reqTranslator['lang'])
                        ->first();

                    if (!$translatorObj) {
                        $translatorObj = new NewsFeedTranslation();
                        $translatorObj->news_feed_id = $newfeedObj->id;
                        $translatorObj->is_deleted = 0;
                    }

                    $translatorObj->title = $reqTranslator['m_title'];
                    $translatorObj->message = isset($reqTranslator['m_desc']) ? $reqTranslator['m_desc'] : '';
                    $translatorObj->image_url = $reqTranslator['image_url'];
                    $translatorObj->link_text = $reqTranslator['link_text'];
                    $translatorObj->language = $reqTranslator['lang'];
                    $translatorObj->save();

                }
            }

            if (isset($request->seg_id)) {
                if ($request->seg_id == '') {

                    $newfeedObj->segment_id = null;
                } else {
                    $newfeedObj->segment_id = $request->seg_id;
                }
            }

            if (isset($request->loc_id)) {

                if ($request->loc_id == '') {

                    $newfeedObj->location_id = null;
                } else {
                    $newfeedObj->location_id = $request->loc_id;
                }
            }


            if (!empty($request->startDate)) {
                $data_1s = date("Y-m-d", strtotime($request->startDate));
                //  $data_2s = date("H:i:s", strtotime($request->startDateTime));
                $newfeedObj->start_time = $data_1s . " " . $request->startHour . ":" . $request->startmin . ":00";
            }

            if (!empty($request->endDate)) {

                if (isset($request->end_tm)) {
                    $data_1e = date("Y-m-d", strtotime($request->endDate));
                    // $data_2e = date("H:i:s", strtotime($request->endDateTime));
                    $newfeedObj->end_time = $data_1e . " " . $request->endHour . ":" . $request->endmin . ":00";
                    $newfeedObj->enable_end_time = 1;
                } else {


                    $newfeedObj->end_time = null;
                    $newfeedObj->enable_end_time = 0;
                }
            }

            $newfeedObj->status = $request->is_active;
            $newfeedObj->is_deleted = 0;
            $newfeedObj->updated_by = $companyId;

            $newfeedObj->save();

            $response = array(
                'error' => false,
                'result' => $newfeedObj->id,
                'message' => 'Successfully saved'

            );
        } catch (\Exception $exception) {

            $response = array(
                'error' => true,
                'result' => null,
                'message' => $exception->getMessage()
            );

        }


        return new JsonResponse($response);

    }


    public function checkNewsFeedDuplication($name, $companyId, $column, $newsfeedId = 0)
    {

        if ($newsfeedId) {

            return NewsFeed::where($column, $name)->where("company_id", $companyId)->where("id", '!=', $newsfeedId)->count();
        } else {

            return NewsFeed::where($column, $name)->where("company_id", $companyId)->count();
        }
    }

    public function getNewsfeedTranslation($newsfeedId, $lang)
    {

        return DB::table('news_feed_translation')->where("news_feed_id", $newsfeedId)->where("language", $lang)->first();
    }

    public function saveNewsfeedTranslation($newsFeedId, $title, $message, $language, $companyId, $newsfeedTranslationId = null)
    {

        $data['news_feed_id'] = $newsFeedId;
        $data['title'] = $title;
        $data['message'] = $message;
        $data['language'] = $language;
        $data['is_deleted'] = 0;

        $data['updated_by'] = $companyId;
        $data['updated_at'] = Carbon::now();
        if ($newsfeedTranslationId) {

            try {

                DB::table('news_feed_translation')
                    ->where('id', $newsfeedTranslationId)
                    ->update($data);
                return true;
            } catch (\Exception $exception) {

                return false;
            }

        } else {
            try {
                $data['created_by'] = $companyId;
                $data['created_at'] = Carbon::now();
                DB::table('news_feed_translation')->insert($data);
                return true;
            } catch (\Exception $ex) {

                return false;
            }
        }
    }

    public function fetchDataTOCreate($companyId)
    {
        $data['companyId'] = $companyId;
        $data['locations'] = Location::where('is_active', 1)->where('is_deleted', 0)->where("company_id", $companyId)->get();
        $data['segments'] = Segment::where('is_active', 1)->where('is_deleted', 0)->where('company_id', $companyId)->get();
        $data['templates'] = \DB::table('news_feed_template')->where('is_active', 1)->where('is_deleted', 0)->get();
        return $data;
    }

    public function getViewAndClickCount($newsfeedId)
    {
        $where[] = ['nf.id', '=', $newsfeedId];
        $clickCount = \DB::table('news_feed as nf')->join('link_tracking as lt', function ($join) {
            $join->on('lt.rec_id', '=', 'nf.id');
            $join->on('lt.rec_type', '=', \DB::raw("'Newsfeed'"));
        })->where($where)->count();
        $viewCount = \DB::table('news_feed as nf')->join('news_feed_impressions as nfi', function ($join) {
            $join->on('nfi.news_feed_id', '=', 'nf.id');
        })->where($where)->sum('viewed');

        $data['totalViews'] = ($viewCount) ? $viewCount : 0;

        return array($clickCount, $data['totalViews'], 0);
    }

    public function getNewsfeedBySearch($companyId, $request)
    {

        $searchKeyWord = $request->searchKeyWord;

        if (empty($searchKeyWord)) {
            $data = NewsFeed::select('news_feed.*', 'news_feed_template.name as template')
                ->join('news_feed_template', 'news_feed.news_feed_template_id', '=', 'news_feed_template.id')
                ->where('news_feed.company_id', $companyId)
                ->where('news_feed.is_deleted', 0)
                ->orderBy('id', 'desc')
                ->get();

        } else {
            $data = NewsFeed::select('news_feed.*', 'news_feed_template.name as template')
                ->join('news_feed_template', 'news_feed.news_feed_template_id', '=', 'news_feed_template.id')
                ->where('news_feed.company_id', $companyId)
                ->where('news_feed.is_deleted', 0)
                ->where('news_feed.name', 'LIKE', "%" . $searchKeyWord . "%")
                ->orderBy('id', 'desc')
                ->get();
        }
        return $data;
    }

    public function newsfeedsListing($request, $companyId)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'location_id',
            3 => 'segment_id',
            4 => 'start_time',
            5 => 'end_time',
            6 => 'status',
        );

        $totalData = $this->newsfeed->where('company_id', $companyId)->where('is_deleted', 0)->count();
        $totalFiltered = $totalData;  // 2
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $order = $columns[$request->input('order.0.column')];  //name
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""
        $filter = $request->input('filter');
        $filterType = $request->input('filterType');
        $newsFeedListing = $this->getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $filter, $filterType);

        if (!empty($search)) {
            $totalFiltered = $this->newsfeed->where('name', 'LIKE', "%{$search}%")
                ->where('company_id', $companyId)
                ->where('is_deleted', 0)
                ->count();
        }
        return array($totalData, $totalFiltered, $newsFeedListing);
    }

    public function getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $filter, $filterType)
    {


        $newsfeed = $this->newsfeed
            ->leftJoin('location as l', 'news_feed.location_id', '=', 'l.id')
            ->leftJoin('segment as s', 'news_feed.segment_id', '=', 's.id')
            ->where('news_feed.company_id', $companyId)
            ->where('news_feed.is_deleted', 0)
            ->offset($start)
            ->orderBy('news_feed.' . $order, $dir)
            ->select("s.name as segment_id", "l.default_name as location_id", "news_feed.id", "news_feed.name", "news_feed.status", "news_feed.start_time", "news_feed.end_time", "news_feed.step", "news_feed.created_by")
            ->limit($limit);

        if (!empty($search)) {

            $newsfeed = $newsfeed->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
                $query->orWhere('tags', 'LIKE', '%' . $search . '%');
            });
        }
        if ($filterType == 'status') {

            $newsfeed = $newsfeed->where('news_feed.' . 'status', $filter);
        } elseif ($filterType == 'platform') {

            $newsfeed = $newsfeed->where($filter, '!=', '');
        } elseif ($filterType == 'types') {

            $newsfeed = $newsfeed->where('news_feed.' . 'news_feed_template_id', $filter);
        } elseif ($filterType == 'createdby') {
            $newsfeed = $newsfeed->where('news_feed.' . 'company_id', $filter);
        } elseif ($filterType == 'tags') {

            $newsfeed = $newsfeed->whereRaw("FIND_IN_SET('$filter', BINARY news_feed.$filterType) > 0");
        }

        $newsfeed = $newsfeed->get();
//        dump($newsfeed);die;
        return $newsfeed;
    }

    public function listing()
    {
        $arr = [
            [
                'id' => 1,
                'name' => 'Merchant Redemption Numbers',
                'filters' => 0,
                'campaign' => 0,
                'cards' => 0,
                'lastEdited' => '1 day ago',
                'createdBy' => 'Jawad Ashraf'
            ],
            [
                'id' => 2,
                'name' => 'Internal Test Group',
                'filters' => 4,
                'campaign' => 0,
                'cards' => 5,
                'lastEdited' => '1 day ago',
                'createdBy' => 'Edward Tucker'
            ],
            [
                'id' => 3,
                'name' => 'Egypt',
                'filters' => 1,
                'campaign' => 0,
                'cards' => 0,
                'lastEdited' => '1 day ago',
                'createdBy' => 'Ash'
            ],
        ];
        return $arr;
    }

    public function getAndroidClick($type, $where)
    {

        $sql = \DB::table('news_feed as nf')->join('link_tracking as lt', function ($join) {
            $join->on('lt.rec_id', '=', 'nf.id');
            $join->on('lt.rec_type', '=', \DB::raw("'Newsfeed'"));
            $join->on('lt.device', '=', \DB::raw("'android'"));
        })->where($where);

        if ($type == 'COUNT') {

            $result = $sql->count();
        } elseif ($type != 'COUNT') {

            if ($type == 'COUNT-BY-HOUR') {//hour

                $intervalFormat = "%H";//%H	Hour (00 to 23)
                $groupByFormat = "%Y-%m-%d-%h";
            }
//            elseif ($type == 'COUNT-BY-WEEK') {//week
//
//                $intervalFormat = "%a";//%a	Abbreviated weekday name (Sun to Sat)
//                $groupByFormat = "%Y-%m-%d";
//            }
            elseif ($type == 'COUNT-BY-DAY') {//days

                $intervalFormat = "%e";//%e	Day of the month as a numeric value (0 to 31)
                $groupByFormat = "%Y-%m-%d";
            } elseif ($type == 'COUNT-BY-MONTH') {//month

                $intervalFormat = "%b";//%b	Abbreviated month name (Jan to Dec)
                $groupByFormat = "%Y-%m";
            }
            $result = $sql->select(\DB::raw("count(*) as count, DATE_FORMAT(lt.created_date, '$intervalFormat') as intervalName"))
                ->groupBy(\DB::raw("DATE_FORMAT(lt.created_date, '$groupByFormat')"))->get();
        }
        return $result;
    }


    public function getIphoneClick($type, $where)
    {

        $sql = \DB::table('news_feed as nf')->join('link_tracking as lt', function ($join) {
            $join->on('lt.rec_id', '=', 'nf.id');
            $join->on('lt.rec_type', '=', \DB::raw("'Newsfeed'"));
            $join->on('lt.device', '=', \DB::raw("'iphone'"));
        })->where($where);

        if ($type == 'COUNT') {

            $result = $sql->count();
        } elseif ($type != 'COUNT') {

            if ($type == 'COUNT-BY-HOUR') {//hour

                $intervalFormat = "%H";//%H	Hour (00 to 23)
                $groupByFormat = "%Y-%m-%d-%h";
            }
//            elseif ($type == 'COUNT-BY-WEEK') {//week
//
//                $intervalFormat = "%a";//%a	Abbreviated weekday name (Sun to Sat)
//                $groupByFormat = "%Y-%m-%d";
//            }
            elseif ($type == 'COUNT-BY-DAY') {//days

                $intervalFormat = "%e";//%e	Day of the month as a numeric value (0 to 31)
                $groupByFormat = "%Y-%m-%d";
            } elseif ($type == 'COUNT-BY-MONTH') {//month

                $intervalFormat = "%b";//%b	Abbreviated month name (Jan to Dec)
                $groupByFormat = "%Y-%m";
            }
            $result = $sql->select(\DB::raw("count(*) as count, DATE_FORMAT(lt.created_date, '$intervalFormat') as intervalName"))
                ->groupBy(\DB::raw("DATE_FORMAT(lt.created_date, '$groupByFormat')"))->get();
        }
        return $result;
    }


    public function getAndroidViews($type, $where)
    {

        $sql = \DB::table('news_feed as nf')->join('news_feed_impressions as nfi', function ($join) {
            $join->on('nfi.news_feed_id', '=', 'nf.id');
            $join->on('nfi.platform', '=', \DB::raw("'android'"));
        })->where($where);

        if ($type == 'COUNT') {

            $result = $sql->sum('viewed');
        } elseif ($type != 'COUNT') {

            if ($type == 'COUNT-BY-HOUR') {//hour

                $intervalFormat = "%H";//%H	Hour (00 to 23)
                $groupByFormat = "%Y-%m-%d-%h";
            }
//            elseif ($type == 'COUNT-BY-WEEK') {//week
//
//                $intervalFormat = "%a";//%a	Abbreviated weekday name (Sun to Sat)
//                $groupByFormat = "%Y-%m-%d";
//            }
            elseif ($type == 'COUNT-BY-DAY') {//days

                $intervalFormat = "%e";//%e	Day of the month as a numeric value (0 to 31)
                $groupByFormat = "%Y-%m-%d";
            } elseif ($type == 'COUNT-BY-MONTH') {//month

                $intervalFormat = "%b";//%b	Abbreviated month name (Jan to Dec)
                $groupByFormat = "%Y-%m";
            }
            $result = $sql->select(\DB::raw("sum(nfi.viewed) as count, DATE_FORMAT(nfi.created_date, '$intervalFormat') as intervalName"))
                ->groupBy(\DB::raw("DATE_FORMAT(nfi.created_date, '$groupByFormat')"))->get();
        }
        return $result;
    }


    public function getIphoneViews($type, $where)
    {

        $sql = \DB::table('news_feed as nf')->join('news_feed_impressions as nfi', function ($join) {
            $join->on('nfi.news_feed_id', '=', 'nf.id');
            $join->on('nfi.platform', '=', \DB::raw("'iphone'"));
        })->where($where);

        if ($type == 'COUNT') {

            $result = $sql->sum('viewed');
        } elseif ($type != 'COUNT') {

            if ($type == 'COUNT-BY-HOUR') {//hour

                $intervalFormat = "%H";//%H	Hour (00 to 23)
                $groupByFormat = "%Y-%m-%d-%h";
            }
//            elseif ($type == 'COUNT-BY-WEEK') {//week
//
//                $intervalFormat = "%a";//%a	Abbreviated weekday name (Sun to Sat)
//                $groupByFormat = "%Y-%m-%d";
//            }
            elseif ($type == 'COUNT-BY-DAY') {//days

                $intervalFormat = "%e";//%e	Day of the month as a numeric value (0 to 31)
                $groupByFormat = "%Y-%m-%d";
            } elseif ($type == 'COUNT-BY-MONTH') {//month

                $intervalFormat = "%b";//%b	Abbreviated month name (Jan to Dec)
                $groupByFormat = "%Y-%m";
            }
            $result = $sql->select(\DB::raw("sum(nfi.viewed) as count, DATE_FORMAT(nfi.created_date, '$intervalFormat') as intervalName"))
                ->groupBy(\DB::raw("DATE_FORMAT(nfi.created_date, '$groupByFormat')"))->get();
        }
        return $result;
    }


    public function getMaxClickByDay($newsFeedId)
    {

        return \DB::select("
            SELECT MAX(countId) AS maxCount, createdDate 
            FROM (
                SELECT COUNT(lt.id) AS countId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM news_feed nf
                JOIN link_tracking lt ON nf.id = lt.rec_id AND lt.rec_type='Newsfeed'
                WHERE nf.id = :newsFeedId 
                GROUP BY createdDate ORDER BY countId DESC
            ) AS nflt", ['newsFeedId' => $newsFeedId]);
    }


    public function getMinClickByDay($newsFeedId)
    {

        return \DB::select("
            SELECT MIN(countId) AS minCount, createdDate 
            FROM (
                SELECT COUNT(lt.id) AS countId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM news_feed nf
                JOIN link_tracking lt ON nf.id = lt.rec_id AND lt.rec_type='Newsfeed'
                WHERE nf.id = :newsFeedId 
                GROUP BY createdDate ORDER BY countId ASC
            ) AS nflt", ['newsFeedId' => $newsFeedId]);
    }


    public function getMaxViewByDay($newsFeedId)
    {
        return \DB::select("
            select MAX(viewedSum) AS maxViewed, createdDate 
            FROM (
                select sum(nfi.viewed) AS viewedSum, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                from news_feed nf
                join news_feed_impressions nfi on nf.id = nfi.news_feed_id
                where nf.id = :newsFeedId
                group by createdDate ORDER BY viewedSum DESC
            ) as nfnfi", ['newsFeedId' => $newsFeedId]);
    }


    public function getMinViewByDay($newsFeedId)
    {
        return \DB::select("
            select MIN(viewedSum) AS minViewed, createdDate 
            FROM (
                select sum(nfi.viewed) AS viewedSum, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                from news_feed nf
                join news_feed_impressions nfi on nf.id = nfi.news_feed_id
                where nf.id = :newsFeedId
                group by createdDate ORDER BY viewedSum ASC
            ) as nfnfi
         ", ['newsFeedId' => $newsFeedId]);
    }


    //-------------


    public function getMinMaxClickByFD($newsFeedIds)
    {

//        $deviceTypeStr = '';
//        if( $deviceType ){
//            $deviceTypeStr = " AND lt.device='{$deviceType}' ";
//        }
//        {$deviceTypeStr}
        return \DB::select("
            SELECT MIN(countId) AS minClick,MAX(countId) AS maxClick, newsFeedId
            FROM (
                SELECT COUNT(lt.id) AS countId, lt.rec_id newsFeedId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM  link_tracking lt
                WHERE lt.rec_id IN ({$newsFeedIds}) AND lt.rec_type='Newsfeed'
                GROUP BY lt.rec_id, createdDate
            ) AS nflt GROUP BY newsFeedId");
    }


    public function getCountClickByFD($newsFeedIds, $deviceType)
    {

        $deviceTypeStr = '';
        if ($deviceType) {
            $deviceTypeStr = " AND lt.device='{$deviceType}' ";
        }
        return \DB::select("            
                SELECT COUNT(lt.id) AS countClick, lt.rec_id newsFeedId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM  link_tracking lt
                WHERE lt.rec_id IN ({$newsFeedIds}) AND lt.rec_type='Newsfeed' {$deviceTypeStr}
                GROUP BY lt.rec_id
                ");
    }


    public function getMinMaxViewByFD($newsFeedIds)
    {

//        $deviceTypeStr = '';
//        if( $deviceType ){
//            $deviceTypeStr = " AND lt.device='{$deviceType}' ";
//        }
//        {$deviceTypeStr}
        return \DB::select("
                SELECT MIN(viewedSum) AS minViewed,MAX(viewedSum) AS maxViewed, newsFeedId
            FROM (
                SELECT SUM(nfi.viewed) AS viewedSum, nfi.news_feed_id newsFeedId, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                FROM  news_feed_impressions nfi
                WHERE nfi.news_feed_id IN ({$newsFeedIds})
                GROUP BY nfi.news_feed_id,createdDate 
            ) AS nfnfi  GROUP BY newsFeedId                     
         ");
    }


    public function getSumViewByFD($newsFeedIds, $deviceType)
    {

        $deviceTypeStr = '';
        if ($deviceType) {
            $deviceTypeStr = " AND nfi.platform='{$deviceType}' ";
        }
        return \DB::select("
                SELECT SUM(nfi.viewed) AS sumViewed, nfi.news_feed_id newsFeedId, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                FROM  news_feed_impressions nfi
                WHERE nfi.news_feed_id IN ({$newsFeedIds}) {$deviceTypeStr}
                GROUP BY nfi.news_feed_id                     
         ");
    }
}