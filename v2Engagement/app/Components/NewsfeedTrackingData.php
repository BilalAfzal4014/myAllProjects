<?php

namespace App\Components;


class NewsfeedTrackingData
{
    /**
     * Get newsfeed cache data.
     *
     * @param int  $company_id
     * @param bool $stats
     *
     * @return array
     */
    public static function newsfeeds($company_id, $stats = true)
    {
        $cache_key = "company_{$company_id}_newsfeeds";
        $data = \Cache::get($cache_key);

        if ($stats === false) {
            return !empty($data) ? \GuzzleHttp\json_decode($data, true) : [];
        }

        if (!empty($data)) {
            $newsfeedIds = \GuzzleHttp\json_decode($data, true);

            $data = [
                'clicks' => self::clicks($company_id, $newsfeedIds),
                'views' => self::views($company_id, $newsfeedIds),
            ];
        }

        return !empty($data) ? $data : [];
    }

    /**
     * Ger newsfeeds click data.
     *
     * @param int   $company_id
     * @param array $newsfeedIds
     *
     * @return \Illuminate\Support\Collection
     */
    public static function clicks($company_id, $newsfeedIds)
    {
        $rows = collect();

        foreach ($newsfeedIds as $newsfeedId) {
            $cache_key = "company_{$company_id}_newsfeed_{$newsfeedId}_clicks";
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
     * Ger newsfeeds views data.
     *
     * @param int   $company_id
     * @param array $newsfeedIds
     *
     * @return \Illuminate\Support\Collection
     */
    public static function views($company_id, $newsfeedIds)
    {
        $rows = collect();

        foreach ($newsfeedIds as $newsfeedId) {
            $cache_key = "company_{$company_id}_newsfeed_{$newsfeedId}_views";
            $data = \Cache::get($cache_key);

            if(!empty($data)) {
                $rows = $rows->merge(
                    \GuzzleHttp\json_decode($data, true)
                );
            }
        }

        return $rows;
    }
}