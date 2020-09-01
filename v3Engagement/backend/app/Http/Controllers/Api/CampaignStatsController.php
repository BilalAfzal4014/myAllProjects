<?php

namespace App\Http\Controllers\Api;

use App\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignStatsController extends Controller
{

    public function __construct()
    {
        $this->setResourceClass('campaignStats');
    }

    public function campaignTracking(Request $request, String $version, $id)
    {
        $request['campaign_id'] = $id;
        return $this->resourceClass->all($request);
    }

    public function campaignTrackingExport(Request $request, String $version, $id)
    {
        $request['campaign_id'] = $id;
        return $this->resourceClass->all($request, false);
    }

    public function actionTrigger(Request $request, String $version, $id)
    {
        $request['campaign_id'] = $id;
        return $this->resourceClass->actionTrigger($request);
    }

    public function campaignStats(String $version, $id)
    {
        return $this->resourceClass->campaignStats($id);
    }

    public function campaignViewsClicksCount(Request $request, String $version, $id)
    {
        $request['campaign_id'] = $id;
        return $this->resourceClass->getViewsClicksCount($request);
    }

    public function campaignViewsClicksChart(Request $request, String $version, $id)
    {
        $request['campaign_id'] = $id;
        return $this->resourceClass->getViewsClicksChart($request);
    }

    public function getCampaignVariants(Request $request, $version, $campaignId)
    {
        $appGroupId = $request->user()->currentAppGroup()->id;
        return $this->resourceClass->getCampaignVariants($appGroupId, $campaignId);
    }
}
