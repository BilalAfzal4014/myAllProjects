<?php

namespace App;

use App\Components\CampaignTrackingData;
use App\Helpers\CampaignCappingHelper;
use Carbon\Carbon;

trait InteractsWithCampaignCappingCache
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $campaign
     */
    protected function setCappingInfo($campaign)
    {
        if ($campaign->isCappingEnabled() === true) {
            $cap_type = CampaignCappingHelper::cappingType($campaign->campaign_type);
            $cap_rule = $campaign->company->capRule($cap_type);

            if (!empty($cap_type) && !empty($cap_rule->company_id)) {
                $capping = CampaignCappingHelper::cappingEnabled($campaign, $cap_rule, $this->row_id, $this->field);
                $data = CampaignTrackingData::capping_rule_data($campaign, $this->row_id, $this->field);
                $cappingData = CampaignTrackingData::getCappingCacheData($campaign, $this->row_id);

                if ($capping === false) {
                    $interval = CampaignCappingHelper::setCappingIntervalMethod($cap_rule->duration_unit);
                    $duration = $cap_rule->duration_value;

                    $limit = !empty($data['limit']) ? $data['limit'] : 0;
                    if ($limit >= $cap_rule->cap_limit) {
                        unset($data);
                    }

                    $start = !empty($data['start']) ? $data['start'] : $campaign->start_time;
                    if (!empty($this->track->sent_at)) {
                        $start= $this->track->sent_at;
                    }

                    $endDate = Carbon::parse($start)->{$interval}($duration);
                    $end = !empty($data['end']) ? $data['end'] : $endDate->toDateTimeString();

                    $limit += 1;

                    $cappingData[$this->field] = [
                        'limit' => $limit,
                        'start' => $start,
                        'end'   => $end
                     ];

                    CampaignTrackingData::setCappingCacheData($campaign, $this->row_id, $cappingData);
                }
            }
        }
    }
}
