<?php

namespace App\Helpers;

use App\CampaignTracking;
use Carbon\Carbon;

class CampaignDeliveryControlHelper
{
    public static function canSendAgain($campaign, $row_id, $column = '')
    {
        try {
            $skipCampaign = ($campaign === null) ? true : false;

            $conditions = [];

            if ($skipCampaign === false) {
                $conditions[] = ['campaign_id', $campaign->id];
            }

            $conditions[] = ['row_id', $row_id];

            if (!empty($column)) {
                $conditions[] = [$column, '<>', ''];
            }

            $tracking = CampaignTracking::where($conditions)
                ->whereIn('status', ['added', 'executing', 'completed'])
                ->orderBy('id', 'DESC')->limit(1)
                ->firstOrFail();

            if (isset($tracking->id)) {
                if ($skipCampaign === true) {
                    return false;
                }

                $intervalDate = '';
                switch (strtolower($campaign->delivery_control_delay_unit)) {
                    case 'minute':
                        $intervalDate = Carbon::parse($tracking->sent_at)
                            ->addMinutes($campaign->delivery_control_delay_value);

                        break;
                    case 'day':
                        $intervalDate = Carbon::parse($tracking->sent_at)
                            ->addDays($campaign->delivery_control_delay_value);

                        break;
                    case 'week':
                        $intervalDate = Carbon::parse($tracking->sent_at)
                            ->addWeeks($campaign->delivery_control_delay_value);

                        break;
                    case 'month':
                        $intervalDate = Carbon::parse($tracking->sent_at)
                            ->addMonths($campaign->delivery_control_delay_value);

                        break;
                }

                if (!empty($intervalDate)) {
                    $now = Carbon::now(config('app.timezone'));
                    if ($now->lt($intervalDate)) {
                        return false;
                    }
                }
            }
        } catch (\Exception $exception) {
        }

        return true;
    }
}