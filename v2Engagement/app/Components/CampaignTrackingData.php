<?php

namespace App\Components;

class CampaignTrackingData
{
    /**
     * Get campaigns cache data.
     *
     * @param int  $company_id
     * @param bool $tracking
     *
     * @return array
     */
    public static function campaigns($company_id, $tracking = true)
    {
        $cache_key = "company_{$company_id}_campaigns";
        $data = \Cache::get($cache_key);

        if ($tracking === false) {
            return !empty($data) ? \GuzzleHttp\json_decode($data, true) : [];
        }

        if (!empty($data)) {
            $campaignIds = \GuzzleHttp\json_decode($data, true);

            $data = self::tracks($company_id, $campaignIds);
        }

        return !empty($data) ? $data : [];
    }

    /**
     * Get campaigns cache data.
     *
     * @param int  $company_id
     *
     * @return array
     */
    public static function conversions($company_id)
    {
        $campaignIds = self::campaigns($company_id, false);
        $rows = collect();

        if (!empty($campaignIds)) {
            foreach ($campaignIds as $campaignId) {
                $cache_key = "company_{$company_id}_campaign_{$campaignId}_conversions";
                $data = \Cache::get($cache_key);

                if(!empty($data)) {
                    $rows = $rows->merge(
                        \GuzzleHttp\json_decode($data, true)
                    );
                }
            }
        }

        return $rows;
    }

    /**
     * Get campaign tracking data.
     *
     * @param $company_id
     * @param $campaignIds
     *
     * @return array
     */
    public static function tracks($company_id, $campaignIds)
    {
        $rows = collect();
        foreach ($campaignIds as $campaignId) {
            $cache_key = "company_{$company_id}_campaign_{$campaignId}_tracking";
            $data = \Cache::get($cache_key);

            if(!empty($data)) {
                $rows = $rows->merge(
                    \GuzzleHttp\json_decode($data, true)
                );
            }
        }

        return $rows;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param int    $row_id
     * @param string $field
     *
     * @return array
     */
    public static function capping_rule_data($campaign, $row_id, $field)
    {
        $cache_key = self::setCacheKey($campaign, $row_id);
        $data = \Cache::get($cache_key);

        if (empty($data)) { return []; }

        $data = \GuzzleHttp\json_decode($data, true);

        return !empty($data[$field]) ? $data[$field] : [];
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param int $row_id
     *
     * @return array
     */
    public static function getCappingCacheData($campaign, $row_id)
    {
        $cache_key = self::setCacheKey($campaign, $row_id);
        $data = \Cache::get($cache_key);

        return !empty($data) ? \GuzzleHttp\json_decode($data, true) : [];
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param int   $row_id
     * @param array $data
     *
     * @return void
     */
    public static function setCappingCacheData($campaign, $row_id, $data)
    {
        $cache_key = self::setCacheKey($campaign, $row_id);

        \Cache::forever($cache_key, \GuzzleHttp\json_encode($data));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param int    $row_id
     *
     * @return string
     */
    protected static function setCacheKey($campaign, $row_id)
    {
        return "company_{$campaign->company_id}_campaign_{$campaign->id}_row_{$row_id}_caprule";
    }
}