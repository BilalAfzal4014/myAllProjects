<?php

namespace App\Components;

use App\CampaignRateLimitRules;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RateLimitingComponents
{
    /**
     * setting the interval time for specfic campaign messages
     * adding the time interval according the rate limit mean 2 and duration value  5 minutes
     * in campaign_start_date and created new field interval
     *
     * @param @int $campaignId
     * @param @array $campaignObj
     * @param int $rate_limit
     * @param int $duration_value
     * @param string $duration_unit
     *
     * @return array
     */
    public static function rateLimitingRules($campaignId, $campaignObj, $rate_limit, $duration_value, $duration_unit)
    {
        try {
            /**
             * Fetching the rate limit rule from campaignRateLimitRule
             */
            //$campaignRateLimitRules = CampaignRateLimitRules::where('campaign_id', $campaignId)
            //                                                            ->first();
            //if ($campaignRateLimitRules) {
                /**
                 * Fetched the rate limit rule  it will be integer
                 */
                //$rate_limit = $campaignRateLimitRules->rate_limit;
                /**
                 * Fetched the duration  it will be integer
                 */
                //$duration_value = $campaignRateLimitRules->duration_value;
                /**
                 * Fetched the duration_unit  it will be varchar
                 */
                //$duration_unit = $campaignRateLimitRules->duration_unit;
                /**
                 * Getting the count ot campaignobj and divide them with rate limit to get chunks count
                 */
                $chunksCount = ceil(count($campaignObj) / $duration_value);
                $skip = 0;
                $itr = 0;
                /**
                 * implementing the loop on chunks count
                 */
                for ($val = 0; $val < $chunksCount; $val++) {
                    for ($i = 0; $i < $duration_value && ($skip + $i) < sizeof($campaignObj); $i++) {
                        //dd($campaignObj[$i]['payload']['data']);
                        $datetime = new Carbon($campaignObj[$skip + $i]['payload']['data']['campaign_start_date']);
                        //$datetime = new Carbon($campaignObj[$skip + $i]['campaign_start_date']);
                        $seconds = self::getSeconds($itr * $rate_limit, $duration_unit);
                        //$campaignObj[$skip + $i]['interval'] = $datetime->addSeconds($seconds)->format('Y-m-d h:i:s');
                        $campaignObj[$skip + $i]['payload']['data']['interval'] = $datetime->addSeconds($seconds)->format('Y-m-d H:i:s');
                        //dd($campaignObj[$skip + $i]['payload']['data']);
                    }

                    $skip = $skip + $duration_value;
                    $itr++;
                }

                return $campaignObj;

            //}
        } catch (\Exception $exception) {
            //return $exception;
            $error = $exception->getMessage();
            Log::error($error);
        }
    }

    public static function getSeconds($value, $unit)
    {
        $timeUnit = [
            "minutes" => 60,
            "days" => 86400,
            "weeks" => 604800,
        ];
        $seconds = $value * $timeUnit[$unit];
        return $seconds;
    }
}