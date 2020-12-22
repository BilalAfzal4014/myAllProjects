<?php

namespace App\Http\Resources\V1\Campaigns;

use App\Cache\CampaignSegmentCache;
use App\Campaign;
use App\CampaignRateLimitRules;
use App\CampaignVariant;
use App\Concerns\exportUsers;
use Illuminate\Support\Facades\DB;


class TargetStep
{
    use exportUsers;

    /**
     * @param array $data
     * @param \App\Campaign $campaign
     *
     * @throws \Exception
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function process($data, Campaign $campaign)
    {

        if (array_search(Campaign::STEP_TARGET, Campaign::STEP_LEVEL) > array_search($campaign->step, Campaign::STEP_LEVEL))
            $campaign->step = Campaign::STEP_TARGET;

        $campaign->segments()->sync($data['targetObj']['segments']);
        $campaign->save();

        $campaign_segment_cache = new CampaignSegmentCache();
        $campaign_segment_cache->saveCampaignSegmentCache($campaign);

        if ($data['targetObj']['rateLimit']['enable']) {
            $rateLimit = CampaignRateLimitRules::where("campaign_id", $campaign->id)->first();
            if (!$rateLimit)
                $rateLimit = new CampaignRateLimitRules();

            $rateLimit->campaign_id = $campaign->id;
            $rateLimit->rate_limit = $data['targetObj']['rateLimit']['campaignRateLimit'];
            $rateLimit->duration_value = $data['targetObj']['rateLimit']['durationValue'];
            $rateLimit->duration_unit = $data['targetObj']['rateLimit']['durationUnit'];
            $rateLimit->save();

        } else {
            CampaignRateLimitRules::where("campaign_id", $campaign->id)->delete();
        }

        $i = 0;
        $campaignVariantDistribution = CampaignVariant::where("campaign_id", $campaign->id)->get();
        foreach ($data['targetObj']['variantDistribution']['distribution'] as $distribution) {
            $campaignVariantDistribution[$i]->distribution = $distribution['value'];
            $campaignVariantDistribution[$i]->save();
            $i++;
        }

        $campaign->fresh();
        $campaign->reachableUsers = exportUsers::exportUsers($campaign->id, "campaign", $campaign->app_group_id, true);
        return $campaign;
    }

    public function getStep($campaignId)
    {
        $colors = ["#2a8689", "#7abbbd", "#5b8688", "#ffbdd2"];
        $targetObj = (object)[];
        $targetObj->totalSelectedSegments = DB::table("campaign_segment")
            ->join("segment", "campaign_segment.segment_id", "=", "segment.id")
            ->where("campaign_segment.campaign_id", $campaignId)
            ->select("segment.name as label", "segment.id as value")
            ->get();

        $targetObj->rateLimit = (object)[];
        $rateLimit = CampaignRateLimitRules::where("campaign_id", $campaignId)->first();
        $targetObj->rateLimit->enable = $rateLimit ? true : false;
        if ($targetObj->rateLimit->enable) {
            $targetObj->rateLimit->campaignRateLimit = $rateLimit->rate_limit;
            $targetObj->rateLimit->durationValue = $rateLimit->duration_value;
            $targetObj->rateLimit->durationUnit = $rateLimit->duration_unit;
        } else {
            $targetObj->rateLimit->campaignRateLimit = 1;
            $targetObj->rateLimit->durationValue = 100;
            $targetObj->rateLimit->durationUnit = "minutes";
        }

        $targetObj->variantsDistribution = (object)[];
        $targetObj->variantsDistribution->distribution = [];

        $campaignVariantDistribution = CampaignVariant::where("campaign_id", $campaignId)->get();

        $i = 0;
        foreach ($campaignVariantDistribution as $distribution) {
            $obj = (object)[];
            $obj->value = $distribution->distribution;
            $obj->color = $colors[$i];
            $targetObj->variantsDistribution->distribution[] = $obj;
            $i++;
        }

        $appGroupId = Campaign::where("id", $campaignId)->first()->app_group_id;
        $targetObj->reachableUsers = exportUsers::exportUsers($campaignId, "campaign", $appGroupId, true);
        return $targetObj;
    }
}