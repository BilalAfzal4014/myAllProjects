<?php

namespace App\Components;

use App\Campaign;

/**
 * Class CampaignPayload
 * @package App\Components
 * @todo build and generate the campaign payload
 */
class CampaignPayload
{
    /**
     * generate payload for In-App or Push
     *
     * @param array $params
     *
     * @return array $payload
     */
    public static function generateInAppPushPayload($params)
    {
        // generates payload for push
        $payload = array(
            "notification" => array("title" => $params['title'],
                "body" => $params['body'],
                "sound" => "DEFAULT"),
            "data" => [
                "backgroundColor" => $params['background'],
                "campaign_code" => $params['campaign_code'],
                "campaign_id" => $params['campaign_id'],
                'row_id' => $params['row_id'],
                'user_id' => $params['user_id'],
                "icon" => ($params['app_logo'] != "") ? $params['app_logo'] : '',
                "is_hermis_platform" => true,
                "is_silent" => false,
                "message_type" => $params['message_type'],
                "sound" => "default",
                "campaign_type" => $params['campaign_type'],
                "message_position" => $params['position'],
                "view_link" => $params['campaign_inapp_view'],
                "priority" => $params['priority'],
                "auto_close" => $params['auto_close'],
                "action_type" => $params['action_type'],
                "action_value" => $params['action_value'],
                "campaign_start_date" => $params['campaign_start_time'],
                "campaign_dispatch_date" => $params['campaign_dispatch_date'],
                "language" => $params['language'],
                "tokens_data" => $params['tokens_data'],
                "data" => "success"
            ]
        );

        return $payload;
    }

    /**
     * generate payload for Email
     *
     * @param array $params
     *
     * @return array $payload
     */
    public static function generateEmailPayload($params)
    {
        // generate payload for Email
        $payload = array(
                    "data" => [
                        "campaign_code" => $params['campaign_code'],
                        "campaign_id" => $params['campaign_id'],
                        "track_key" => $params['tracking_key'],
                        "row_id" => $params['row_id'],
                        "user_id" => $params['user_id'],
                        "campaign_type" => $params['campaign_type'],
                        "view_link" => $params['campaign_inapp_view'],
                        "email_body" => $params['template_content'],
                        "email_subject" => $params['subject'],
                        "email_from" => $params['from_email'],
                        "email_from_name" => $params['from_name'],
                        "to_email" => $params['to_email'],
                        "priority" => $params['priority'],
                        "language" => $params['language'],
                        'campaign_start_date' => $params['campaign_start_time'],
                        "data" => "success"
                    ]
                );
        return $payload;
    }
}
