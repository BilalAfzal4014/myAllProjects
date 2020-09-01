<?php
/**
 * Created by PhpStorm.
 * User: ets-rebel
 * Date: 3/27/19
 * Time: 11:01 AM
 */

namespace App\Http\Resources\V1;

use App\AppUserActivity;
use App\Campaign;
use App\CampaignTracking;
use App\CampaignVariant;
use App\Components\AppStatusCodes;
use App\Components\AppStatusMessages;
use App\Components\ParseResponse;
use App\Http\Resources\ResourcesSteps;
use App\Http\Resources\Contracts\ResourcesContract;
use App\Http\Resources\Contracts\ProcessResourceDataContract;
use Carbon\Carbon;

class CampaignStatsResource implements ResourcesContract, ProcessResourceDataContract
{
    use ParseResponse, ResourcesSteps;

    public function all(\Illuminate\Http\Request $request, $isListing = true)
    {
        try {

            $trackings = CampaignTracking::with('app_user', 'campaign_tracking_log')
                ->where('campaign_id', $request['campaign_id']);

            if (isset($request['track_table_filter'])) {
                $query = $request['track_table_filter'];
                if (isset($query['start_date']) AND isset($query['end_date'])) {
                    $trackings = $trackings->whereDate('sent_at', '>=', $query['start_date'])
                        ->whereDate('sent_at', '<=', $query['end_date']);
                }
                if ($query['status']) {
                    $trackings = $trackings->where('status', $query['status']);
                }

                if ($query['variantFilter'] && $query['variantFilter'] != -1) {
                    $trackings = $trackings->where('variant_id', $query['variantFilter']);
                }

                if ($query['deviceType'] && $query['deviceType'] != -1) {
                    $trackings = $trackings->where('device_type', $query['deviceType']);
                }

                if (isset($query['global'])) {
                    $search = $query['global'];
                    $trackings->where(function ($query) use ($search) {
                        $query->where('track_key', 'LIKE', "%{$search}%");
                        $query->orWhere('email', 'LIKE', "%{$search}%");
                        $query->orWhere('sent_at', 'LIKE', "%{$search}%");
                        $query->orWhere('status', 'LIKE', "%{$search}%");
                        $query->orWhere('viewed_at', 'LIKE', "%{$search}%");
                        $query->orWhere('device_type', 'LIKE', "%{$search}%");
                    });
                }
            }
            $totalFiltered = $trackings->count();

            if ($isListing) {
                $trackings = $trackings->offset(($request['page'] - 1) * $request['limit'])->limit($request['limit']);
            }

            if (isset($request["orderBy"])) {
                $trackings = $trackings->orderBy($request["orderBy"], $request["ascending"] == 1 ? 'desc' : 'asc');
            } else {
                $trackings = $trackings->orderBy('updated_at', 'desc');
            }

            $trackings = $trackings->get();

            if ($isListing) {
                $meta = [
                    'pages' => ceil($totalFiltered / $request['limit']),
                    'page' => $request['page'],
                    'total' => $totalFiltered,
                ];
                $response = [
                    'meta' => $meta,
                    'data' => $this->getCampaignTrackingResponse($trackings, $request['campaign_id'])
                ];
                return $this->addResponse(
                    AppStatusCodes::HTTP_OK,
                    AppStatusMessages::SUCCESS,
                    $response['data'],
                    'data',
                    $response['meta']
                );


            }

            $trackRecords = $this->getCampaignTrackingResponse($trackings, $request['campaign_id']);

            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                AppStatusMessages::SUCCESS,
                $this->convertToString($trackRecords),
                'data'
            );

        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_NOT_FOUND,
                'error',
                [$exception->getMessage()],
//                ['Unable to load action stats data'],
                $exception->getMessage()
            );
        }
    }

    private function convertToString($records)
    {
        $str = "row_id, Variant, Track key, Email, Sent At, Status, Device Type, Viewed At, Message" . PHP_EOL;
        foreach ($records as $record) {
            $str .= $record['row_id'] . ',';
            $str .= $record['variant'] . ',';
            $str .= $record['track_key'] . ',';
            $str .= $record['email'] . ',';
            $str .= $record['sent_at'] . ',';
            $str .= $record['status'] . ',';
            $str .= $record['device_type'] . ',';
            $str .= $record['viewed_at'] . ',';
            $str .= $record['message'] . PHP_EOL;
        }

        return $str;
    }

    private function getCampaignTrackingResponse($trackings, $campaignId)
    {
        $allVariantsOfCampaign = CampaignVariant::where('campaign_id', $campaignId)
            ->pluck('id')->toArray();

        $response = collect();

        foreach ($trackings as $tracking) {
            $lang = json_decode($tracking->payload, true)["data"]["language"];
            $response->push([
                "id" => $tracking->id,
                /*"variant_id" => $tracking->variant_id,*/
                "row_id" => $tracking->row_id,
                "variantLang" => array_search($tracking->variant_id, $allVariantsOfCampaign) . '-' . $lang,
                "variant" => 'variant-' . (array_search($tracking->variant_id, $allVariantsOfCampaign) + 1) . ': ' . $lang,
                "track_key" => $tracking->track_key,
                "email" => $tracking->app_user ? $tracking->app_user->email : '',
                "device_type" => $tracking->device_type,
                "sent_at" => $tracking->sent_at,
                "status" => $tracking->status,
                "viewed_at" => $tracking->viewed_at,
                "message" => $tracking->campaign_tracking_log ? $tracking->campaign_tracking_log->message : ''
            ]);
        }

        return $response;
    }

    public function getCampaignVariants($appGroupId, $campaignId)
    {
        try {
            $exist = Campaign::where('app_group_id', $appGroupId)
                ->where('id', $campaignId)
                ->first();

            if ($exist) {

                $allVariantsOfCampaign = CampaignVariant::where('campaign_id', $campaignId)
                    ->pluck('id')->toArray();

                $arr = [];
                $itr = 1;
                foreach ($allVariantsOfCampaign as $variant) {
                    $obj = (object)[];
                    $obj->variantId = $variant;
                    $obj->label = 'variant-' . $itr;
                    $arr[] = $obj;
                    $itr++;
                }

                return $this->addResponse(
                    AppStatusCodes::HTTP_OK,
                    'success',
                    $arr,
                    'data'
                );

            }


            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                'success',
                'campaign doesn\'t exist',
                'data'
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_NOT_FOUND,
                'error',
                [$exception->getMessage()],
                $exception->getMessage()
            );
        }
    }

    public function process($request, \Illuminate\Database\Eloquent\Model $model)
    {
        $queryChain = $model->where('campaign_id', '=', $request['campaign_id']);
        $totalCount = clone $queryChain;
        $totalCount = $totalCount->count();
        if ($request['sideFilters'] != null && $request['sideFilters'] != []) {
        }
        if ($request['query'] != null) {
            $search = $request['query'];
            $columns = $request['columns'];
            $queryChain->where(function ($query) use ($search, $columns) {
                $query->where('languages.id', 'LIKE', "%{$search}%");
                $query->orWhere('languages.name', 'LIKE', "%{$search}%");
                $query->orWhere('languages.code', 'LIKE', "%{$search}%");
            });
        }
        $totalFiltered = clone $queryChain;
        $totalFiltered = $totalFiltered->count();
        isset($request["orderBy"]) ? $queryChain->orderBy($request["orderBy"], $request["ascending"] == 1 ? 'desc' : 'asc') : $queryChain->orderBy('updated_at', 'desc');
        $data = $queryChain->offset(($request['page'] - 1) * $request['limit'])
            ->limit($request['limit'])
            ->get();
        $meta = [
            'pages' => ceil($totalFiltered / $request['limit']),
            'page' => $request['page'],
            'total' => $totalFiltered,
        ];
        return [
            'meta' => $meta,
            'data' => $data
        ];

    }

    public function create(\Illuminate\Http\Request $request)
    {
    }

    public function show(\Illuminate\Database\Eloquent\Model $model)
    {
    }

    public function update(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Model $model)
    {
    }

    public function remove(\Illuminate\Database\Eloquent\Model $model)
    {
    }

    public function campaignStats($id)
    {
        try {
            $campaign = Campaign::with('segments')
                ->find($id);

            $composeStepVariantClass = "App\Http\Resources\V1\Campaigns\ComposeStep";

            $response = [
                "campaign" => $campaign,
                "views" => $this->getViewsCount($campaign),
                "clicks" => $this->getClicksCount($campaign),
                "variants" => (new $composeStepVariantClass)->getStep($id)
            ];

            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                AppStatusMessages::SUCCESS,
                $response,
                'data',
                []
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_NOT_FOUND,
                'error',
                ['Unable to load data'],
                $exception->getMessage()
            );
        }
    }

    private function getViewsCount($campaign)
    {
        $viewsCountCollection = $campaign->campaign_tracking()
            ->select([\DB::raw("COUNT(*) AS count_viewed"), \DB::raw("DATE(viewed_at) as viewed_date")])
            ->where('viewed', 1)
            ->groupBy("viewed_date")
            ->orderBy("count_viewed", "DESC")
            ->get();

        $views = collect();

        $views->push($viewsCountCollection->first());
        $views->push($viewsCountCollection->last());

        return $views;
    }

    private function getClicksCount($campaign)
    {
        $clicksCountCollection = $campaign->linkTracking()
            ->select([\DB::raw("COUNT(*) AS count_clicks"), \DB::raw("DATE(created_at) as click_date")])
            ->groupBy("click_date")
            ->orderBy("count_clicks", "DESC")
            ->get();

        $clicks = collect();

        $clicks->push($clicksCountCollection->first());
        $clicks->push($clicksCountCollection->last());

        return $clicks;
    }

    public function actionTrigger(\Illuminate\Http\Request $request)
    {
        try {
            if ($request['action_table_type'] != "") {
                $actions = AppUserActivity::where('campaign_id', '=', $request['campaign_id'])->where('rec_type', '=', $request['action_table_type']);
            } else {
                $actions = AppUserActivity::where('campaign_id', '=', $request['campaign_id'])->where('rec_type', '=', 'action_trigger');
            }
            if (isset($request['action_table_filter'])) {
                $query = $request['action_table_filter'];
                if ($query['type']) {
                    $actions = $actions->where('device_type', $query['type']);
                }
                if (isset($query['start_date']) AND isset($query['end_date'])) {
                    $actions = $actions->whereDate('created_at', '>=', $query['start_date'])
                        ->whereDate('created_at', '<=', $query['end_date']);
                }

                if (isset($query['global'])) {
                    $filter = $query['global'];
                    $actions = $actions->where('event_id', $filter)
                        ->orWhere('event_id', $filter)
                        ->orWhere('event_value', $filter)
                        ->orWhere('track_key', $filter)
                        ->orWhere('device_type', $filter)
                        ->orWhere('campaign_code', $filter);
                }
            }

            $totalFiltered = $actions->count();

            $actions = $actions->offset(($request['page'] - 1) * $request['limit'])->limit($request['limit']);

            if (isset($request["orderBy"])) {
                $actions = $actions->orderBy($request["orderBy"], $request["ascending"] == 1 ? 'desc' : 'asc');
            } else {
                $actions = $actions->orderBy('updated_at', 'desc');
            }

            $actions = $actions->get();

            $meta = [
                'pages' => ceil($totalFiltered / $request['limit']),
                'page' => $request['page'],
                'total' => $totalFiltered,
            ];
            $response = [
                'meta' => $meta,
                'data' => $actions
            ];
            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                AppStatusMessages::SUCCESS,
                $response['data'],
                'data',
                $response['meta']
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_NOT_FOUND,
                'error',
                [$exception->getMessage()],
//                ['Unable to load action stats data'],
                $exception->getMessage()
            );
        }
    }

    public function getViewsClicksCount(\Illuminate\Http\Request $request)
    {
        try {
            $id = $request['campaign_id'];
            $campaign = Campaign::find($id);

            $androidViewsCount = $this->getViewsByType($campaign, "android", $request);
            $iosViewsCount = $this->getViewsByType($campaign, "ios", $request);

            $androidClicksCount = $this->getClicksByType($campaign, "android", $request);
            $iosClicksCount = $this->getClicksByType($campaign, "ios", $request);

            $androidPercentage = 0;
            if ($androidViewsCount && $androidClicksCount) {
                $androidPercentage = ($androidClicksCount / $androidViewsCount) * 100;
            }

            $iosPercentage = 0;
            if ($iosViewsCount && $iosClicksCount) {
                $iosPercentage = ($iosClicksCount / $iosViewsCount) * 100;
            }

            $response = [
                "ios" => [
                    "views" => $iosViewsCount,
                    "clicks" => $iosClicksCount,
                    "percentage" => round($iosPercentage)
                ],
                "android" => [
                    "views" => $androidViewsCount,
                    "clicks" => $androidClicksCount,
                    "percentage" => round($androidPercentage)
                ]
            ];

            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                AppStatusMessages::SUCCESS,
                $response,
                'data',
                []
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_NOT_FOUND,
                'error',
                [$exception->getMessage()],
//                ['Unable to load data'],
                $exception->getMessage()
            );
        }
    }

    private function getViewsByType($campaign, $type, $request)
    {
        $viewsCountCollection = $campaign->campaign_tracking()
            ->where('device_type', $type)
            ->where('campaign_tracking.viewed', '<>', 0);

        if (!empty($request['start_date']) AND !empty($request['end_date'])) {
            $viewsCountCollection = $viewsCountCollection->whereDate('viewed_at', '>=', $request['start_date'])
                ->whereDate('viewed_at', '<=', $request['end_date']);
        }

        if (isset($request['date'])) {
            $viewsCountCollection = $viewsCountCollection->whereDate('viewed_at', $request['date']);
        }

        $viewsCountCollection = $viewsCountCollection
            ->count();

        return $viewsCountCollection;
    }

    private function getClicksByType($campaign, $type, $request)
    {
        $viewsCountCollection = $campaign->linkTracking()
            ->where('device_type', $type)
            ->where('link_tracking.viewed', 1);

        if (!empty($request['start_date']) AND !empty($request['end_date'])) {
            $viewsCountCollection = $viewsCountCollection->whereDate('created_at', '>=', $request['start_date'])
                ->whereDate('created_at', '<=', $request['end_date']);
        }

        if (isset($request['date'])) {
            $viewsCountCollection = $viewsCountCollection->whereDate('created_at', $request['date']);
        }

        $viewsCountCollection = $viewsCountCollection
            ->count();

        return $viewsCountCollection;
    }

    public function getViewsClicksChart(\Illuminate\Http\Request $request)
    {
        try {
            $id = $request['campaign_id'];
            $type = $request->get('type');
            $campaign = Campaign::find($id);
            $devices = $this->getDevices();

            $response = collect();

            foreach ($devices as $device) {
                $dates = $this->getLastSevenDays();

                $data = [
                    "type" => "column",
                    "showInLegend" => false,
                    "name" => $device['label'],
                    "color" => $device['color'],
                    "dataPoints" => []
                ];

                $options = collect();

                foreach ($dates as $date) {
                    $request['date'] = $date;

                    if ($type == "views") {
                        $viewsCount = $this->getViewsByType($campaign, $device['value'], $request);
                        $options->push([
                            "y" => $viewsCount, "label" => $date
                        ]);
                    }

                    if ($type == "clicks") {
                        $clicksCount = $this->getClicksByType($campaign, $device['value'], $request);
                        $options->push([
                            "y" => $clicksCount, "label" => $date
                        ]);
                    }

                    if ($type == "click_through") {
                        $viewsCount = $this->getViewsByType($campaign, $device['value'], $request);
                        $clicksCount = $this->getClicksByType($campaign, $device['value'], $request);
                        $percentage = 0;
                        if ($viewsCount && $clicksCount) {
                            $percentage = ($clicksCount / $viewsCount) * 100;
                        }
                        $options->push([
                            "y" => $percentage, "label" => $date
                        ]);
                    }
                }
                $data['dataPoints'] = $options;

                $response->push($data);
            }

            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                AppStatusMessages::SUCCESS,
                $response->toArray(),
                'data',
                []
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_NOT_FOUND,
                'error',
                [$exception->getMessage()],
//                ['Unable to load data'],
                $exception->getMessage()
            );
        }
    }

    public function getDevices()
    {
        $response = [];

        array_push($response, [
            "color" => "#7cb5ec",
            "label" => "android",
            "value" => "android"
        ]);
        array_push($response, [
            "color" => "#434348",
            "label" => "ios",
            "value" => "ios"
        ]);

        return $response;
    }

    private function getLastSevenDays()
    {
        $period = new \DatePeriod(
            new \DateTime(Carbon::now()->addDays(-7)->format('Y-m-d')),
            new \DateInterval('P1D'),
            new \DateTime(Carbon::now()->format('Y-m-d'))
        );
        $dates = [];
        foreach ($period as $key => $value) {
            array_push($dates, $value->format('Y-m-d'));
        }

        return $dates;
    }
}