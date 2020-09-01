<?php

namespace App\Components;

use App\Campaign;
use App\Helpers\CommonHelper;
use App\Jobs\SendEmailJob;
use App\Jobs\SendInAppNotificationsJob;
use App\Jobs\SendPushNotificationsJob;
use App\Libraries\VerifyEmail;
use App\Lookup;
use App\User;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;
trait InteractsWithMessages
{
    /**
     * Send emails to users.
     *
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param array $data
     *
     * @throws \Exception
     *
     * @return array|string
     */
    protected function useEmailMessage($campaign, $data)
    {
        $emails = [];
        $row_ids = [];

        $skip_duplicate = config('engagement.message.skip_duplicate');

        $notification = false;
        $interval = $this->getInterval($campaign);
        foreach ($data['email'] as $email => $row) {
            try {
                $tracking_code = substr(bin2hex(random_bytes(40)), 0, 40);
                $campaign_tracking = config('engagement.urls.tracking') . $tracking_code;
                $unsubscribe_url = $this->fetchTinyUrl(config('engagement.urls.unsubscribe') . base64_encode($tracking_code));
                $verify_email = config('engagement.message.verify_email');
                if (isset($verify_email) && ((bool)$verify_email === true)) {
                    $verified = (new VerifyEmail($campaign->company_id))->verified($email);
                    if ($verified === false) {
                        continue;
                    }
                } else {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                        continue;
                    }
                }

                if (isset($skip_duplicate) && ((bool)$skip_duplicate === true)) {
                    if (in_array($email, $emails)) {
                        continue;
                    }
                }

                if (in_array($row['row_id'], $row_ids)) {
                    continue;
                }

                if (!in_array($email, $emails)) {
                    $emails[] = $email;
                }

                if (!in_array($row['row_id'], $row_ids)) {
                    $row_ids[] = $row['row_id'];
                }

                if ($notification === false) {
                    $notification = true;
                }

                $unsubscribe_html = '<div style="font-size:14px;color:#a79d9d;padding:10px;margin:10px;text-align: center;">Don\'t want to receive further emails? <a href="' . $unsubscribe_url . '" style="cursor: pointer" target="_blank" >Unsubscribe here</a> <br> OR <br> Paste the below URL into your browser window.<br>' . $unsubscribe_url . '</div>';

                $emailData = [
                    'email' => $email,
                    'from' => $data['from'],
                    'subject' => $data['subject'],
                    'message' => '<img src="' . $campaign_tracking . '" />' . $row['message'] . $unsubscribe_html
                ];

                foreach ($data['users'][$row['row_id']] as $key => $value) {
                    $emailData['message'] = str_replace('[[$' . $key . ']]', $value, $emailData['message']);
                }

                if (!empty($data['cc'])) {
                    $emailData['cc'] = $data['cc'];
                }

                if (!empty($data['bcc'])) {
                    $emailData['bcc'] = $data['bcc'];
                }

                $payload = [
                    'email' => $email,
                    'payload' => base64_encode(addslashes(serialize($emailData))),
                    'track_key' => $tracking_code,
                    'row_id' => $row['row_id'],
                ];

                \Queue::laterOn('email' . strtolower($campaign->campaign_priority), $interval, new SendEmailJob('campaign', $payload, $campaign));
            } catch (\Exception $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        return $notification;
    }

    /**
     * @param $campaign Campaign
     * @return float|int|mixed
     */
    public function getInterval($campaign)
    {
        $interval = config('engagement.queue.interval');

        if ($campaign->isDeliveryTypeAction() && !empty($campaign->action_trigger_delay_value) && !empty($campaign->action_trigger_delay_unit)) {
            switch ($campaign->action_trigger_delay_unit) {
                case CommonHelper::$_SECOND_API_TRIGGER:
                    $interval = $campaign->action_trigger_delay_value;
                    break;
                case CommonHelper::$_MINUTE_API_TRIGGER:
                    $interval = $campaign->action_trigger_delay_value * 60;
                    break;
                case CommonHelper::$_HOUR_API_TRIGGER:
                    $interval = $campaign->action_trigger_delay_value * 3600;
                    break;
            }
        }

        return $interval;
    }

    function fetchTinyUrl($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url=' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return '' . $data . '';
    }

    /**
     * Send iOS Push notifications.
     *
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param array $data
     *
     * @return array|string
     * @throws \Exception
     */
    protected function useIOSPushNotifications($campaign, $data)
    {
        $device_tokens = [];
        $row_ids = [];

        $company = User::find($campaign->company_id);
        $apps = $this->getActiveApps($company->apps);

        if ($apps->count() == 0) {
            throw new \Exception("No associated apps found for company");
        }

        $skip_keys = $data['skip_devices']['device_token'];
        $skip_duplicate = config('engagement.message.skip_duplicate');

        $notification = false;

        $interval = $this->getInterval($campaign);

        foreach ($data['users'] as $user) {
            $appItem = $apps->filter(function ($app) use ($user) {
                return (in_array($app->app_id, [trim($user['app_id'])]) && in_array(strtolower($app->platform), ['ios'])) ? $app : null;
            })->first();

            if (empty($appItem->id)) {
                continue;
            }

            // Skip the device if it is android.
            if (!empty($user['device_type']) && in_array(strtolower($user['device_type']), ['android'])) {
                continue;
            }
            // Skip if device token is empty.
            if (empty($user['device_token'])) {
                continue;
            }

            if (!empty($skip_keys) && in_array($user['device_token'], $skip_keys)) {
                continue;
            }

            // Skip device if is already in send device list.
            if (isset($skip_duplicate) && ((bool)$skip_duplicate === true)) {
                if (in_array($user['device_token'], $device_tokens)) {
                    continue;
                }
            }

            if (in_array($user['row_id'], $row_ids)) {
                continue;
            }

            // Add device if not available in send device list.
            if (!in_array($user['device_token'], $device_tokens)) {
                $device_tokens[] = $user['device_token'];
            }

            if (!in_array($user['row_id'], $row_ids)) {
                $row_ids[] = $user['row_id'];
            }

            if (isset($appItem->is_sandbox)) {
                $sandbox = ($appItem->isSandbox() === true) ? true : false;
            } else {
                $sandbox = (config('engagement.message.push.mode') == 'test') ? true : false;
            }

            $certificate = ($sandbox === true) ? $appItem->ios_cert_dev : $appItem->ios_cert_live;

            if ($notification === false) {
                $notification = true;
            }

            $data['message'] = $user['message'];
            if (!is_array($data['message'])) {
                $data['message'] = \GuzzleHttp\json_decode($data['message'], true);
            }

            $msg = is_array($data['message']) ? trim(strip_tags($data['message']['text'])) : trim(strip_tags($data['message']));
            $msg = preg_replace('/(\r\n|\r|\n)+/', "", $msg);
            $msg = preg_replace('/\s+/', ' ', $msg);

            foreach ($user as $key => $value) {
                $msg = str_replace('[[$' . $key . ']]', $value, $msg);
            }

            if (!empty($data['message']['title'])) {
                $msgTitle = $data['message']['title'];

                foreach ($user as $key => $value) {
                    $msgTitle = str_replace('[[$' . $key . ']]', $value, $msgTitle);
                }
            }

            if (!empty(config('engagement.message.push.bg_color'))) {
                $msgBackgroundColor = config('engagement.message.push.bg_color');
            }

            if (!empty($appItem->logo)) {
                $msgIcon = \Storage::disk('s3')->url($appItem->logo);
            }

            $company_name = $company->name;
            $passphrase = $appItem->ios_passphrase;
            $device_token = $user['device_token'];

            $tokenData = [
                'device_token' => $device_token,
                'title' => !empty($msgTitle) ? $msgTitle : '',
                'target' => !empty($data['message']['action1']['value']) ? $data['message']['action1']['value'] : '',
                'backgroundColor' => !empty($msgBackgroundColor) ? $msgBackgroundColor : '',
                'icon' => !empty($msgIcon) ? $msgIcon : '',
                'message' => $msg,
                'company_name' => $company_name,
                'passphrase' => $passphrase,
                'certificate' => $certificate,
                'sandbox' => $sandbox,
                'campaign_id' => $campaign->id,
                'campaign_code' => $campaign->code,
                'user_id' => !empty($user['user_id']) ? $user['user_id'] : '',
                'is_silent' => false,
                'is_hero_platform' => true,
                'app_id' => $user['app_id']
            ];

            $tracking_code = substr(bin2hex(random_bytes(40)), 0, 40);

            try {
                $payload = [
                    'device_key' => $device_token,
                    'payload' => base64_encode(addslashes(serialize($tokenData))),
                    'track_key' => $tracking_code,
                    'row_id' => $user['row_id'],
                    'device_type' => strtoupper($user['device_type']),
                ];

                \Queue::laterOn('push' . strtolower($campaign->campaign_priority), $interval, new SendPushNotificationsJob('campaign', $payload, $campaign));
            } catch (\Exception $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        return $notification;
    }

    /**
     * Send notifications through FireBase.
     *
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param array $data
     *
     * @return array|string
     * @throws \Exception
     */
    protected function useFireBaseNotifications($campaign, $data)
    {
        $company = User::find($campaign->company_id);
        $apps = $this->getActiveApps($company->apps);

        if ($apps->count() == 0) {
            throw new \Exception("No associated apps found for company");
        }

        $campaign_type = $campaign->campaign_type;

        $fire_base_keys = [];
        $row_ids = [];

        $skip_keys = $data['skip_devices']['firebase_key'];
        $skip_tokens = $data['device_tokens'];

        $skip_platform = '';
        if ($campaign->isPlatformAndroid() || ($campaign_type->isPush() && $campaign->isPlatformUniversal())) {
            $skip_platform = Campaign::PLATFORM_IOS;
        }
        if ($campaign->isPlatformIOS()) {
            $skip_platform = Campaign::PLATFORM_ANDROID;
        }

        $skip_duplicate = config('engagement.message.skip_duplicate');

        $notification = false;
        $interval = $this->getInterval($campaign);
        foreach ($data['users'] as $user) {
            // Skip if fire base key is empty.
            if (empty($user['fire_base_key']) || empty($user['device_type'])) {
                continue;
            }

            $userPlatform = strtolower($user['device_type']);
            $isDeviceAndroid = in_array($userPlatform, [Campaign::PLATFORM_ANDROID]) ? true : false;
            $isDeviceIOS = in_array($userPlatform, [Campaign::PLATFORM_IOS]) ? true : false;

            $appItem = $apps->filter(function ($app) use ($user, $userPlatform) {
                $appPlatform = strtolower($app->platform);

                return (in_array($app->app_id, [$user['app_id']]) && in_array($appPlatform, [$userPlatform])) ? $app : null;
            })->first();

            if (empty($appItem->id)) {
                continue;
            }

            // Dont show notification on ios if app is open.
            if ($campaign_type->isPush() && $campaign->isPlatformUniversal() && $isDeviceIOS) {
                continue;
            }

            if (!empty($skip_keys) && in_array($user['fire_base_key'], $skip_keys)) {
                continue;
            }

            if (!empty($skip_platform) && !empty($skip_tokens['firebase'][$skip_platform])) {
                if (in_array($user['fire_base_key'], $skip_tokens['firebase'][$skip_platform])) {
                    continue;
                }
            }

            if (isset($skip_duplicate) && ((bool)$skip_duplicate === true)) {
                if (in_array($user['fire_base_key'], $fire_base_keys)) {
                    continue;
                }
            }

            if (in_array($user['row_id'], $row_ids)) {
                continue;
            }

            // Add device if not available in send device list.
            if (!in_array($user['fire_base_key'], $fire_base_keys)) {
                $fire_base_keys[] = $user['fire_base_key'];
            }

            if (!in_array($user['row_id'], $row_ids)) {
                $row_ids[] = $user['row_id'];
            }

            if ($notification === false) {
                $notification = true;
            }

            $data['message'] = \GuzzleHttp\json_decode($user['message'], true);
            foreach ($data['message'] as $k => $v) {
                $msg = str_replace("\n", "", $v);
                $data['message'][$k] = $msg;
            }

            $msg_notification = [];

            if ($campaign_type->isPush()) {
                $msg = is_array($data['message']) ? trim(strip_tags($data['message']['text'])) : trim(strip_tags($data['message']));
            } else {
                $msg = $data['message']['template'];
            }

            $msg_notification['body'] = $msg;

            if (!empty($data['message']['title'])) {
                $msg_notification['title'] = $data['message']['title'];
            }

            if (!empty($data['message']['action1']['value'])) {
                $msg_notification['link'] = $data['message']['action1']['value'];
            }
            if (!empty($data['message']['action2']['label'])) {
                $autoClose = false;
            } else {
                $autoClose = true;
            }
            if (!empty(config('engagement.message.push.bg_color'))) {
                $msg_notification['backgroundColor'] = config('engagement.message.push.bg_color');
            }

            if (!empty($appItem->logo)) {
                $msg_notification['icon'] = \Storage::disk('s3')->url($appItem->logo);
            }

            $apiKey = $appItem->firebase_server_api_key;
                
                

            $deviceToken = $user['fire_base_key'];

            $message = [
                'apiKey' => $apiKey,
            ];

            $message_type = '';
            $message_position = '';

            if (!empty($campaign->message_type_id)) {
                $message_type = Lookup::find($campaign->message_type_id);
                $message_type = $message_type->name;
            }
            if (!empty($campaign->position_id) && strtolower($message_type) == 'dialogue') {
                //$msg = $this->getTemplate($msg, $campaign->id);
                $message_position = Lookup::find($campaign->position_id);
                $message_position = $message_position->name;
            }
            Log::emergency("Message Position".$message_type);
            if (strtolower($message_type) == 'banner') {
                $autoClose=true;
            }

            $tempMsg = $msg;
            foreach ($user as $key => $value) {
                $tempMsg = str_replace('[[$' . $key . ']]', $value, $tempMsg);
            }

            $tracking_code = substr(bin2hex(random_bytes(40)), 0, 40);
            $campaign_inapp_view = config('engagement.urls.inappview') . $tracking_code;

            $msg = $tempMsg;
            unset($tempMsg);

            if ($campaign_type->isPush() && $isDeviceAndroid) {
                foreach ($user as $key => $value) {
                    $msg_notification['body'] = str_replace('[[$' . $key . ']]', $value, $msg_notification['body']);

                    if (!empty($msg_notification['title'])) {
                        $msg_notification['title'] = str_replace('[[$' . $key . ']]', $value, $msg_notification['title']);
                    }
                }

                $message['notification'] = $msg_notification;
            }

            if ($isDeviceAndroid) {
                $message['android'] = [
                    'priority' => $campaign->isPriorityHigh() ? 'high' : 'normal',
                ];
            }

            $message['priority'] = $campaign->isPriorityHigh() ? 10 : 5;
            $message['alert'] = [
                'data' => $msg,
                'message_type' => strtolower($message_type),
                'position' => strtolower($message_position),
                'campaign_id' => $campaign->id,
                'campaign_code' => $campaign->code,
                'campaign_type' => $campaign_type->isPush() ? 'push' : 'inapp',
                'user_id' => !empty($user['user_id']) ? $user['user_id'] : '',
                'track_key' => $tracking_code,
                'is_silent' => false,
                'is_hero_platform' => true,
                'inapp_view_link' => $campaign_inapp_view,
                'backgroundColor' => !empty(config('engagement.message.push.bg_color')) ? config('engagement.message.push.bg_color') : '',
                'icon' => !empty($appItem->logo) ? \Storage::disk('s3')->url($appItem->logo) : '',
                'auto_close' => $autoClose
            ];

            try {
                $payload = [
                    'firebase_key' => $deviceToken,
                    'payload' => base64_encode(addslashes(serialize($message))),
                    'track_key' => $tracking_code,
                    'row_id' => $user['row_id'],
                    'device_type' => strtoupper($user['device_type']),
                ];

                \Queue::laterOn('inapp' . strtolower($campaign->campaign_priority), $interval, new SendInAppNotificationsJob('campaign', $payload, $campaign));
            } catch (\Exception $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        return $notification;
    }

    /**
     * Get template content based on provided id.
     *
     * @param string $content
     * @param int $id
     *
     * @return string
     */
    protected function getTemplate($content, $id)
    {
        try {
            $crawler = new Crawler($content);
            return trim(
                $crawler->filterXPath('//a[contains(@id, "' . $id . '")]')->parents()->html()
            );
        } catch (\Exception $exception) {
            return $content;
        }
    }
}
