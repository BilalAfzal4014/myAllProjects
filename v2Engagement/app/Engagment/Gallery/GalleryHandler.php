<?php

namespace App\Engagment\Gallery;

use Illuminate\Support\Facades\DB;


class GalleryHandler
{
    protected $gallery;

    public function __construct(\App\Gallery $gallery)
    {
        $this->gallery= $gallery;
    }


    public function galleryListing($request, $companyId)
    {
        $columns = array(
            0 => 'image_name',
            1 => 'created_at',
            3 => 'created_at',
        );

        $totalData = $this->gallery->where('company_id', $companyId)->where('is_deleted', 0)->count();
        $totalFiltered = $totalData;  // 2
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $order = $columns[$request->input('order.0.column')];  //name
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""
        $filter = $request->input('filter');
        $filterType = $request->input('filterType');
        $galleryListing = $this->getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $filter, $filterType);

        if (!empty($search)) {
            $totalFiltered = $this->gallery->where('image_name', 'LIKE', "%{$search}%")
                ->where('company_id', $companyId)
                ->where('is_deleted', 0)
                ->count();
        }
        return array($totalData, $totalFiltered, $galleryListing);
    }

    public function getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $filter, $filterType)
    {

            if (empty($search)) {


                $segment = $this->gallery
                    ->where('company_id', $companyId)->where('is_deleted', 0)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            } else {

                $segment = $this->gallery->where(function ($query) use ($search) {
                    $query->where('image_name', 'LIKE', '%' . $search . '%');
                })
                    ->where('company_id', $companyId)
                    ->where('is_deleted', 0)
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();
            }

        return $segment;
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
        } elseif ($type != 'COUNT'){

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
            }elseif( $type == 'COUNT-BY-MONTH' ) {//month

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
            }elseif( $type == 'COUNT-BY-MONTH' ) {//month

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
            }elseif( $type == 'COUNT-BY-MONTH' ) {//month

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
            }elseif( $type == 'COUNT-BY-MONTH' ) {//month

                $intervalFormat = "%b";//%b	Abbreviated month name (Jan to Dec)
                $groupByFormat = "%Y-%m";
            }
            $result = $sql->select(\DB::raw("sum(nfi.viewed) as count, DATE_FORMAT(nfi.created_date, '$intervalFormat') as intervalName"))
                ->groupBy(\DB::raw("DATE_FORMAT(nfi.created_date, '$groupByFormat')"))->get();
        }
        return $result;
    }


    public function getMaxClickByDay($newsFeedId){

       return  \DB::select("
            SELECT MAX(countId) AS maxCount, createdDate 
            FROM (
                SELECT COUNT(lt.id) AS countId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM news_feed nf
                JOIN link_tracking lt ON nf.id = lt.rec_id AND lt.rec_type='Newsfeed'
                WHERE nf.id = :newsFeedId 
                GROUP BY createdDate ORDER BY countId DESC
            ) AS nflt", ['newsFeedId'=>$newsFeedId]);
    }


    public function getMinClickByDay($newsFeedId){

        return  \DB::select("
            SELECT MIN(countId) AS minCount, createdDate 
            FROM (
                SELECT COUNT(lt.id) AS countId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM news_feed nf
                JOIN link_tracking lt ON nf.id = lt.rec_id AND lt.rec_type='Newsfeed'
                WHERE nf.id = :newsFeedId 
                GROUP BY createdDate ORDER BY countId ASC
            ) AS nflt", ['newsFeedId'=>$newsFeedId]);
    }


    public function getMaxViewByDay($newsFeedId){
       return  \DB::select("
            select MAX(viewedSum) AS maxViewed, createdDate 
            FROM (
                select sum(nfi.viewed) AS viewedSum, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                from news_feed nf
                join news_feed_impressions nfi on nf.id = nfi.news_feed_id
                where nf.id = :newsFeedId
                group by createdDate ORDER BY viewedSum DESC
            ) as nfnfi", ['newsFeedId'=>$newsFeedId]);
    }


    public function getMinViewByDay($newsFeedId){
        return  \DB::select("
            select MIN(viewedSum) AS minViewed, createdDate 
            FROM (
                select sum(nfi.viewed) AS viewedSum, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                from news_feed nf
                join news_feed_impressions nfi on nf.id = nfi.news_feed_id
                where nf.id = :newsFeedId
                group by createdDate ORDER BY viewedSum ASC
            ) as nfnfi
         ", ['newsFeedId'=>$newsFeedId]);
    }


    //-------------



    public function getMinMaxClickByFD($newsFeedIds){

//        $deviceTypeStr = '';
//        if( $deviceType ){
//            $deviceTypeStr = " AND lt.device='{$deviceType}' ";
//        }
//        {$deviceTypeStr}
        return  \DB::select("
            SELECT MIN(countId) AS minClick,MAX(countId) AS maxClick, newsFeedId
            FROM (
                SELECT COUNT(lt.id) AS countId, lt.rec_id newsFeedId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM  link_tracking lt
                WHERE lt.rec_id IN ({$newsFeedIds}) AND lt.rec_type='Newsfeed'
                GROUP BY lt.rec_id, createdDate
            ) AS nflt GROUP BY newsFeedId");
    }


    public function getCountClickByFD($newsFeedIds, $deviceType){

        $deviceTypeStr = '';
        if( $deviceType ){
            $deviceTypeStr = " AND lt.device='{$deviceType}' ";
        }
        return  \DB::select("            
                SELECT COUNT(lt.id) AS countClick, lt.rec_id newsFeedId, DATE_FORMAT(lt.created_date,'%Y-%m-%d') createdDate
                FROM  link_tracking lt
                WHERE lt.rec_id IN ({$newsFeedIds}) AND lt.rec_type='Newsfeed' {$deviceTypeStr}
                GROUP BY lt.rec_id
                ");
    }


    public function getMinMaxViewByFD($newsFeedIds){

//        $deviceTypeStr = '';
//        if( $deviceType ){
//            $deviceTypeStr = " AND lt.device='{$deviceType}' ";
//        }
//        {$deviceTypeStr}
        return  \DB::select("
                SELECT MIN(viewedSum) AS minViewed,MAX(viewedSum) AS maxViewed, newsFeedId
            FROM (
                SELECT SUM(nfi.viewed) AS viewedSum, nfi.news_feed_id newsFeedId, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                FROM  news_feed_impressions nfi
                WHERE nfi.news_feed_id IN ({$newsFeedIds})
                GROUP BY nfi.news_feed_id,createdDate 
            ) AS nfnfi  GROUP BY newsFeedId                     
         ");
    }


    public function getSumViewByFD($newsFeedIds, $deviceType){

        $deviceTypeStr = '';
        if( $deviceType ){
            $deviceTypeStr = " AND nfi.platform='{$deviceType}' ";
        }
        return  \DB::select("
                SELECT SUM(nfi.viewed) AS sumViewed, nfi.news_feed_id newsFeedId, DATE_FORMAT(nfi.created_date,'%Y-%m-%d') createdDate
                FROM  news_feed_impressions nfi
                WHERE nfi.news_feed_id IN ({$newsFeedIds}) {$deviceTypeStr}
                GROUP BY nfi.news_feed_id                     
         ");
    }
}