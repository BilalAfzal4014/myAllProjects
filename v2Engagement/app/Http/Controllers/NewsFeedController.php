<?php

namespace App\Http\Controllers;

use App\Components\CompanyAttributeData;
use App\Engagment\AttributeData\AttributeDataWrapper;
use App\Engagment\Campaign\CampaignWrapper;
use App\Http\Resources\V1\NewsfeedResource;
use App\Libraries\tv_jwt;
use App\Segment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Engagment\Newsfeed\Newsfeeds;
use App\NewsFeed;
use App\Gallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use CommonHelper;
use App\NewsFeedTranslation;


class NewsFeedController extends Controller
{
    protected $newsfeeds;

    protected $tst;

    public function __construct(Newsfeeds $newsfeeds)
    {
        $this->newsfeeds = $newsfeeds;

    }

    public function getNews(Request $request, tv_jwt $jwt)
    {
        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\NewsfeedResource";
        $response = (new $class)->setNewsFeed($this->newsfeeds)
            ->process($request, $jwt);

        return $response;
    }

    public function getNewsCount(Request $request, tv_jwt $jwt)
    {
        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\NewsfeedResourceCount";
        $response = (new $class)->setNewsFeed($this->newsfeeds)
            ->process($request, $jwt);
        return $response;
    }

    public function getNewsModify(Request $request, tv_jwt $jwt)
    {
        $class = "App\\Http\\Resources\\" . strtoupper($request->segment(2)) . "\\NewsfeedResource";
        $response = (new $class)->setNewsFeed($this->newsfeeds)
            ->processTemplate($request, $jwt);

        return $response;
    }

    public function test()
    {

        $companyId = Auth::user()->id;
        $data['images'] = Gallery::where(['is_deleted' => 0, 'company_id' => $companyId])->get();
        $newfeedObj = new NewsFeed;
        $data['newsLists'] = $newfeedObj->where('company_id', $companyId)->where('is_deleted', 0)->get();
        return view('newsfeed.test', $data);
    }


    public function getNewsfeedBySearch(Request $request)
    {

        $companyId = Auth::user()->id;
        $data['newsLists'] = $this->newsfeeds->getNewsfeedBySearch($companyId, $request);
        $returnHTML = view('newsfeed.search', $data)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $companyId = Auth::user()->id;
        $data['segments'] = Segment::where('is_active', 1)->where('is_deleted', 0)->where('company_id', $companyId)->get();
        $data['templates'] = \DB::table('news_feed_template')->where('is_active', 1)->where('is_deleted', 0)->get();
        $data['tagsNewsFeed'] = NewsFeed::tagsCount('company_id', $companyId, 'is_deleted');
        return view('newsfeed.index', $data);
    }

    public function mobilePlateformStatics(Request $request)
    {

        $newsFeedId = $request->newsFeedId;
        $screen = $request->screen;
        $companyId = Auth::user()->id;
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');

        $where[] = ['nf.company_id', '=', $companyId];

        if (!empty($newsFeedId)) {
            $where[] = ["nf.id", '=', $newsFeedId];
        }
        $fromDate = (!empty($fromDate)) ? $fromDate : Carbon::now()->subDays(1)->format('Y-m-d');
        $where[] = [\DB::raw("DATE_FORMAT(created_date,'%Y-%m-%d')"), '>=', $fromDate];
        $toDate = (!empty($toDate)) ? $toDate : Carbon::now()->format('Y-m-d');
        $where[] = [\DB::raw("DATE_FORMAT(created_date,'%Y-%m-%d')"), '<=', $toDate];

        list($clickAndroidCount, $clickIphoneCount,
            $viewAndroidCount, $viewIphoneCount,
            $clickAndroidByInterval, $clickIphoneByInterval,
            $viewAndroidByInterval, $viewIphoneByInterval,
            $clickThroughAndroidByIntervalArr, $clickThroughIphoneByIntervalArr,
            $intervalArr) =
            $this->newsfeeds->mobilePlateformStatics($where, $screen, $fromDate, $toDate);

        return response()->json([
            'clickIphoneCount' => $clickIphoneCount,
            'viewIphoneCount' => ($viewIphoneCount) ? $viewIphoneCount : 0,
            'iphoneClickThroughCount' => $clickThroughIphoneByIntervalArr,
            'clickAndroidCount' => $clickAndroidCount,
            'viewAndroidCount' => ($viewAndroidCount) ? $viewAndroidCount : 0,
            'androidClickThroughCount' => $clickThroughAndroidByIntervalArr,
            'clickAndroidByInterval' => $clickAndroidByInterval,
            'clickIphoneByInterval' => $clickIphoneByInterval,
            'viewAndroidByInterval' => $viewAndroidByInterval,
            'viewIphoneByInterval' => $viewIphoneByInterval,
            'intervalArr' => $intervalArr,
        ]);
    }

    public function newsfeedsListing(Request $request)
    {
        $companyId = Auth::user()->id;
        list($totalData, $totalFiltered, $newsfeedsListing) = $this->newsfeeds->newsfeedsListing($request, $companyId);
        return response()->json([
            'status' => true,
            'data' => $newsfeedsListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'NewsFeed listing'
        ]);
    }

    public function getTemplateData($id)
    {
        if (!empty($id)) {
            $data = \DB::table('news_feed_template')->where('id', $id)->where('is_active', 1)->where('is_deleted', 0)->first();

            if (!empty($data)) {

                echo $data->content;
            }

        }
    }


    public function newsfeedSuspend(Request $request)
    {
        $authuser = Auth::user()->id;
        $newsfeedId = $request->id;
        $newsFeedResponse = NewsFeed::where('id', $newsfeedId)
            ->where('company_id', '=', $authuser)->update(["status" => 'suspend']);
        if ($newsFeedResponse) {
            return response()->json([
                'status' => '200',
                'message' => 'NewsFeed Suspended'
            ]);
        } else {
            abort(403, 'Unauthorized');
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newsfeed.crud');
    }

    public function newsfeedPreLoadData(Request $request)
    {

        $companyId = Auth::user()->id;
        $newsfeedId = $request->newsfeedId;
        if ($newsfeedId) {
            $newsFeed = NewsFeed::where('id', '=', $newsfeedId)->where('company_id', '=', $companyId)->first();
            if (!$newsFeed) {
                abort(403, 'Unauthorized');
            }
        }
//        if ($newsFeed) {
        $data = $this->newsfeeds->fetchDataTOCreate($companyId);
        $responseAjax['newsfeed'] = $data['newsfeed'] = (int)$newsfeedId ? NewsFeed::find($newsfeedId) : null;
        if ((int)$newsfeedId) {
            $newsFeedTranslation = NewsFeedTranslation::where('news_feed_id', $newsfeedId)
                ->where('language', 'en')->first();

            $responseAjax['newsfeed']['image_url'] = $newsFeedTranslation->image_url;
            $responseAjax['newsfeed']['link_text'] = $newsFeedTranslation->link_text;

        }
        //dd($responseAjax['newsfeed']['image_url']);
        $data['type'] = 'template';
        $responseAjax['template'] = view('newsfeed.ajax.dataForCrud', $data)->render();
        $data['type'] = 'location';
        $responseAjax['location'] = view('newsfeed.ajax.dataForCrud', $data)->render();
        $data['type'] = 'segment';
        $responseAjax['segment'] = view('newsfeed.ajax.dataForCrud', $data)->render();
        return new JsonResponse($responseAjax);
//        }else{
//        }
    }

    public function newsfeedSegmentCount(Request $request)
    {

        $segmentId = $request->id;
        if (!$segmentId) {

            $count = 0;
        } else {
            $segmentObj = Segment::find($segmentId);
            $count = count(CompanyAttributeData::getSegmentCache($segmentObj));
        }
        return new JsonResponse(array(
            "count" => $count
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $companyId = Auth::user()->id;
        return $this->newsfeeds->saveNewsfeed($companyId, $request);
    }


    public function checkDuplication(Request $request)
    {

        $companyId = Auth::user()->id;

        $newsFeedDuplication = $this->newsfeeds->newsfeedDuplication($companyId, $request->name, 'name', $request->id);
        if ($newsFeedDuplication) {


            return new JsonResponse(array(
                "status" => 411,
                "message" => "Name Already exist"
            ));
        } else {

            return new JsonResponse(array(
                "status" => 200,
                "message" => ""
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Newsfeed $newsfeed
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $companyId = Auth::user()->id;
        $newfeedObj = new NewsFeed;
        $news = $newfeedObj->where('id', $id)->where('company_id', '=', $companyId)->first();
        if (!$news) {
            abort(403, 'Unauthorized');
        }
        $data['news'] = $news;
        list($data['maxClickByDay'], $data['minClickByDay'], $data['maxViewByDay'], $data['minViewByDay']) =
            $this->newsfeeds->performanceStatics($id);
        $data['newsFeedId'] = $id;

        return view('newsfeed.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Newsfeed $newsfeed
     * @return \Illuminate\Http\Response
     */
    public function edit($newsFeedId)
    {
        $companyId = Auth::user()->id;
        $data['companyId'] = $companyId;
        list($data['totalClicks'], $data['totalViews'], $data['clickthroughRate']) = $this->newsfeeds->getViewAndClickCount($newsFeedId);
        $newsFeedResponse = NewsFeed::where('company_id', '=', $companyId)->find($newsFeedId);
        if (!$newsFeedResponse) {
            abort(403, 'Unauthorized');
        }
        $data['news'] = $newsFeedResponse;
        $data['newsFeedEn'] = $this->newsfeeds->getNewsfeedTranslation($newsFeedId, 'en');
        return view('newsfeed.crud', $data);
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Newsfeed $newsfeed
     * @return Response
     */
    public function destroy(Request $request)
    {
        $companyId = Auth::user()->id;
        $id = $request->id;
        $companyId = Auth::user()->id;
        $newfeedObj = new NewsFeed;
        $res = $newfeedObj->where('id', $id)->where('company_id', '=', $companyId)->update(['is_deleted' => 1]);
        if (!$res) {
            abort(403, 'Unauthorized');
        }
        $data['tagsNewsFeed'] = NewsFeed::tagsCount('company_id', $companyId, 'is_deleted');
        $tagsView = view("newsfeed.ajax.tags", $data)->render();
        return new JsonResponse(array(
            "status" => 200,
            "data" => $tagsView
        ));
    }

    public function updateLanguage(Request $request)
    {

        $companyId = Auth::user()->id;
        $this->newsfeeds->saveNewsfeedLanguage($request, $companyId);
        return redirect()->route('newsfeedList');

    }

    public function getTranslationData(Request $request, $id)
    {

        $newsfeedTransaltionObj = $this->newsfeeds->getNewsfeedTranslation($id, 'ar');
        return new JsonResponse(array(
            "status" => 200,
            "newsfeedObj" => $newsfeedTransaltionObj
        ));
    }

    public function newsFeedGetMultiLingual(Request $request)
    {
        $newsFeed = $this->newsfeeds->getNewsfeedTranslation($request->id, $request->lang);

        if ($newsFeed) {

            $obj = (object)[];
            $obj->m_title = $newsFeed->title;
            $obj->m_desc = $newsFeed->message;
            $obj->image_url = $newsFeed->image_url;
            $obj->link_text = $newsFeed->link_text;
            $obj->lang = $newsFeed->language;

            return \response()->json([
                "status" => true,
                "data" => $obj,
                "message" => "NewsFeed found"
            ]);

        } else {
            return \response()->json([
                "status" => false,
                "data" => [],
                "message" => "NewsFeed not found"
            ]);
        }
    }
}