<?php

namespace App\Cache;


class CacheKeys
{

    /**
     * Initialize with app_group_id
     *
     * @param int app_group_id
     */
    public function __construct($app_group_id = NULL)
    {
        $this->app_group_id = $app_group_id;
    }

    /**
     * generate application segment cache key
     *
     * @return string segment_key
     */
    public function generateAppGroupSegmentKey()
    {
        // build app group segment key
        $segment_key = "app_group_id_" . $this->app_group_id . "_segments";

        return $segment_key;
    }

    /**
     * generate application segment rows cache key
     *
     * @param int segment_id
     * @return string segment_key
     */
    public function generateAppGroupSegmentRowsKey($segment_id)
    {
        // build app group segment rows key
        $segment_row_key = "app_group_id_" . $this->app_group_id . "_segment_" . $segment_id . "_rows";

        return $segment_row_key;
    }

    /**
     * generate application segment rows cache key
     *
     * @param int segment_id
     * @return string segment_key
     */
    public function generateAppUserLoginSignupKey($row_id)
    {
        // build app group segment rows key
        $app_user_key = "app_group_id_" . $this->app_group_id . "_row_id_" . $row_id;

        return $app_user_key;
    }

    /**
     * generate application segment rows cache key
     *
     * @param int segment_id
     * @return string segment_key
     */
    public function generateCampaignSegmentKey($campaign_id)
    {
        // build app group segment rows key
        $campaign_segment_key = "campaign_" . $campaign_id . "_segments";

        return $campaign_segment_key;
    }

    /**
     * generate application tracking cache key
     *
     * @param int $campaign_id
     * @param int $token_id
     *
     * @return string campaign_tracking_key
     */
    public function generateCampaignTrackingKey($campaign_id, $row_id, $language, $variant_id)
    {
        // build app group segment rows key
        $campaign_tracking_key = "campaign_tracking_campaign_id_" . $campaign_id . "_row_id_" . $row_id . '_language_' . $language . "_variant_" . $variant_id;

        return $campaign_tracking_key;
    }

    /**
     * generate application campaign translation cache key
     *
     * @param int $campaign_id
     * @param int $language_id
     * @param int $variant_id
     *
     * @return string campaign_translation_key
     */
    public function generateCampaignTranslationKey($campaign_id, $language_id, $variant_id)
    {
        // build app group segment rows key
        $campaign_translation_key = "app_group_id_" . $this->app_group_id . "_campaign_" . $campaign_id . "_language_" . $language_id . "_variant_" . $variant_id;

        return $campaign_translation_key;
    }

    /**
     * generate application campaign capping cache key
     *
     * @param int $app_group_id_
     * @param int $campaign_id
     * @param int $row_id
     * @param string $language_code
     * @param int $variant_id
     *
     * @return string campaign_capping_key
     */
    public function generateCampaignCappingCacheKey($campaign_id, $row_id, $language_code, $variant_id)
    {
        $capping_cache = "app_group_id_". $this->app_group_id ."_campaign_".$campaign_id."_row_".$row_id ."_language_" . $language_code . "_variant_" . $variant_id ."_caprule";

        return $capping_cache;
    }

    /**
     * generate company export users cache key
     *
     * @param int $company_id
     *
     * @return string $export_users_key
     */
    public function generateExportUsersKey($groupId)
    {
        $export_users_key = "export_users_app_group_id_" . $groupId . "_csv";

        return $export_users_key;
    }

    public function generateProcessExportUsersKey($groupId)
    {
        $export_users_key = "process_export_users_app_group_id_" . $groupId . "_csv";

        return $export_users_key;
    }

    public function generateAppUserStatsKey()
    {
        $app_users = "dashboard_stats_app_group_id_". $this->app_group_id ."_users";

        return $app_users;
    }

    public function generateCampaignStatsKey()
    {
        $campaign_stats = "dashboard_stats_app_group_id_". $this->app_group_id ."_campaigns";

        return $campaign_stats;
    }

    public function generateCampaignConversionStatsKey()
    {
        $campaign_stats = "dashboard_stats_app_group_id_". $this->app_group_id ."_campaign_conversion";

        return $campaign_stats;
    }

    public function generateNewsfeedViewsStatsKey()
    {
        $campaign_stats = "dashboard_stats_app_group_id_". $this->app_group_id ."_newsfeed_views";

        return $campaign_stats;
    }

    public function generateNewsfeedClicksStatsKey()
    {
        $campaign_stats = "dashboard_stats_app_group_id_". $this->app_group_id ."_newsfeed_clicks";

        return $campaign_stats;
    }

    public function generatePopularAppsCacheKey()
    {
        $popular_apps = "dashboard_stats_app_group_id_". $this->app_group_id ."_popular_apps";

        return $popular_apps;
    }
}