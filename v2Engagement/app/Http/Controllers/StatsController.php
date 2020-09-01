<?php

namespace App\Http\Controllers;

use App\GathersServicesStats;
use App\Helpers\AttributeDataHelper;
use App\UserAttribute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatsController extends Controller
{
    use GathersServicesStats;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $response = [];
        $data = $request->all();

        switch ($data['filter']) {
            case 'campaign':
                $response = $this->gatherCampaignStats($data);
                break;
            case 'newsfeed':
                $response = $this->gatherNewsfeedStats($data);
                break;
            case 'conversion':
                $response = $this->gatherConversionStats($data);
                break;
        }

        return $response;
    }

    /**
     * Gather campaign stats.
     *
     * @param array $data
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function gatherCampaignStats($data)
    {
        $response = [];

        $this->isCompany = (auth()->user()->hasRole(config('engagement.roles.company'))) ? true : false;

        try {
            switch ($data['interval']) {
                case 'today':
                    $response = $this->gatherTodayCampaignStats();
                    break;

                case 'yesterday':
                    $response = $this->gatherYesterdayCampaignStats();
                    break;

                case 'last-7-days':
                case 'last-30-days':
                case 'last-60-days':
                    $limit = 7;
                    if ($data['interval'] == 'last-30-days') {
                        $limit = 30;
                    }
                    if ($data['interval'] == 'last-60-days') {
                        $limit = 60;
                    }

                    $startDate = Carbon::now()->subDays($limit)->startOfDay()->toDateTimeString();
                    $endDate = Carbon::now()->subDay()->endOfDay()->toDateTimeString();

                    $response = $this->gatherMultiDayCampaignStats($startDate, $endDate, $limit);

                    break;

                case 'last-3-months':
                case 'last-6-months':
                case 'last-12-months':
                    $limit = 3;
                    if ($data['interval'] == 'last-6-months') {
                        $limit = 6;
                    }
                    if ($data['interval'] == 'last-12-months') {
                        $limit = 12;
                    }

                    $startMonth = Carbon::now()->subMonths($limit)->startOfMonth()->toDateTimeString();
                    $endMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();
//                    echo 'startmoth'.$startMonth;
//                    echo 'endmonth'.$endMonth;
//                    die;
                    $response = $this->gatherMultiMonthsCampaignStats($startMonth, $endMonth, $limit);

                    break;
            }

            return $response;
        } catch (\Exception $exception) {

        }

        return $response;
    }

    /**
     * Gather campaign stats.
     *
     * @param array $data
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function gatherNewsfeedStats($data)
    {
        $response = [];

        $this->isCompany = (auth()->user()->hasRole(config('engagement.roles.company'))) ? true : false;

        try {
            switch ($data['interval']) {
                case 'today':
                    $response = $this->gatherTodayNewsfeedStats();
                    break;

                case 'yesterday':
                    $response = $this->gatherYesterdayNewsfeedStats();
                    break;

                case 'last-7-days':
                case 'last-30-days':
                case 'last-60-days':
                    $limit = 7;
                    if ($data['interval'] == 'last-30-days') {
                        $limit = 30;
                    }
                    if ($data['interval'] == 'last-60-days') {
                        $limit = 60;
                    }

                    $startDate = Carbon::now()->subDays($limit)->startOfDay()->toDateTimeString();
                    $endDate = Carbon::now()->subDay()->endOfDay()->toDateTimeString();

                    $response = $this->gatherMultiDayNewsfeedStats($startDate, $endDate, $limit);

                    break;

                case 'last-3-months':
                case 'last-6-months':
                case 'last-12-months':
                    $limit = 3;
                    if ($data['interval'] == 'last-6-months') {
                        $limit = 6;
                    }
                    if ($data['interval'] == 'last-12-months') {
                        $limit = 12;
                    }

                    $startMonth = Carbon::now()->subMonths($limit)->startOfMonth()->toDateTimeString();
                    $endMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();

                    $response = $this->gatherMultiMonthNewsfeedStats($startMonth, $endMonth, $limit);

                    break;
            }

            return $response;
        } catch (\Exception $exception) {

        }

        return $response;
    }

    /**
     * Gather conversion stats.
     *
     * @param array $data
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function gatherConversionStats($data)
    {
        $response = [];

        $this->isCompany = (auth()->user()->hasRole(config('engagement.roles.company'))) ? true : false;

        $this->conversionAppId = null;
        if (!empty($data['app_name'])) {
            $this->conversionAppId = $data['app_name'];
        }

        try {
            switch ($data['interval']) {
                case 'today':
                    $response = $this->gatherTodayConversionStats();
                    break;

                case 'yesterday':
                    $response = $this->gatherYesterdayConversionStats();
                    break;

                case 'last-7-days':
                case 'last-30-days':
                case 'last-60-days':
                    $limit = 7;
                    if ($data['interval'] == 'last-30-days') {
                        $limit = 30;
                    }
                    if ($data['interval'] == 'last-60-days') {
                        $limit = 60;
                    }

                    $startDate = Carbon::now()->subDays($limit)->startOfDay()->toDateTimeString();
                    $endDate = Carbon::now()->subDay()->endOfDay()->toDateTimeString();

                    $response = $this->gatherMultiDayConversionStats($startDate, $endDate, $limit);

                    break;

                case 'last-3-months':
                case 'last-6-months':
                case 'last-12-months':
                    $limit = 3;
                    if ($data['interval'] == 'last-6-months') {
                        $limit = 6;
                    }
                    if ($data['interval'] == 'last-12-months') {
                        $limit = 12;
                    }

                    $startMonth = Carbon::now()->subMonths($limit)->startOfMonth()->toDateTimeString();
                    $endMonth = Carbon::now()->subMonth()->endOfMonth()->toDateTimeString();

                    $response = $this->gatherMultiMonthsConversionStats($startMonth, $endMonth, $limit);

                    break;
            }

            return $response;
        } catch (\Exception $exception) {

        }

        return $response;
    }

    public function changeNotification(Request $request)
    {
        try {
            $id = Auth::user()->id;
            $key = $request->input('key');
            $value = $request->input('value');
            $rowId = $request->input('rowId');
            $response = UserAttribute::where('row_id', '=', $rowId)->where('company_id','=',$id)->first();
            if ($response) {
                $params = array(
                    'user_id' => $response->user_id,
                    'app_name' => $response->app_name
                );
                $status = AttributeDataHelper::saveAttributeData($id, $params, $key, $value);
                if ($status) {
                    return new JsonResponse(array(
                        "status" => 200,
                        "message" => "Record Updated"
                    ));

                } else {
                    return new JsonResponse(array(
                        "status" => 400,
                        "message" => "Record not updated"
                    ));

                }
            } else {
                abort('403', 'UnAuthorized');
            }
        } catch (\Exception $exception) {
            return new JsonResponse(array(
                "Exception" => $exception->getMessage(),
                "status" => 400,
                "message" => "error"
            ));
        }
    }

}
