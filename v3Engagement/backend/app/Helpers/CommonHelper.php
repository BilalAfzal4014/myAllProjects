<?php
/**
 * Created by PhpStorm.
 * User: ets-rebel
 * Date: 3/11/19
 * Time: 6:14 PM
 */

namespace app\Helpers;


use App\Apps;
use App\AppUsers;
use App\AppUserTokens;
use App\Cache\AppGroupSegmentCache;
use App\Cache\AppUserLoginSignupCache;
use App\CampaignTracking;
use App\NotificationsLog;
use App\Translation;
use Carbon\Carbon;
use mysql_xdevapi\Exception;

class CommonHelper
{
    public static function saveNotificationlogs($id, $status, $message)
    {
        $notificationLogs = new NotificationsLog();
        $notificationLogs->notification_id = $id;
        $notificationLogs->status = $status;
        $notificationLogs->message = $message;
        $notificationLogs->save();
        return $notificationLogs;
    }

    public static function updateDeviceToken($token, $data)
    {
        $appResult = AppUserTokens::whereIn('device_token', $token)->update([
            'is_revoked' => $data['is_revoked'],
            'status' => $data['status'],
            'deleted_at' => $data['deleted_at']
        ]);

        $_app_tokens = AppUserTokens::whereIn('device_token', $token)->get();
        if ($_app_tokens) {
            foreach ($_app_tokens as $_token) {
                $params['user_id'] = $_token['user_id'];
                $params['app_id'] = $_token['app_id'];
                $params['app_name'] = $_token['app_name'];
                AppUserLoginSignupCache::saveAppUserLoginCache($params);
            }
        }
        return $appResult;
    }

    public static function getAppGroupId($app_id, $app_name, $device_type, $companyId)
    {
        $app_group_id = '';
        $appResult = Apps::with('group')
            ->whereHas('group', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->where('name', $app_name)
            ->where('app_id', $app_id)
            ->where('platform', $device_type)
            ->where('is_active', 1)
            ->where('deleted_at', NULL)
            ->first();
        if (!$appResult) {
            throw new \Exception('App group id not active.');
        }

        $app_group_id = $appResult->app_group_id;
        \Log::info('APP GROUP ID:' . $app_group_id);

        return $app_group_id;
    }

    public static function getAppGroupIdForImport($app_id, $app_name, $device_type, $companyId)
    {
        $app_group_id = '';
        $appResult = Apps::with('group')
            ->whereHas('group', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->where('name', $app_name)
            ->where('app_id', $app_id)
            ->where('platform', $device_type)
            ->where('is_active', 1)
            ->where('deleted_at', NULL)
            ->first();
        if ($appResult) {
            $app_group_id = $appResult->app_group_id;
            \Log::info('APP GROUP ID:' . $app_group_id);
           // throw new \Exception('App group id not active.');
        }else{
            $app_group_id='';
        }



        return $app_group_id;
    }

    public static function getAppServerKey($app_id, $app_group_id, $platform)
    {
        $server_key = '';
        $appResult = Apps::where('app_group_id', $app_group_id)
            ->where('app_id', $app_id)
            ->where('platform', $platform)
            ->where('is_active', 1)
            ->where('deleted_at', NULL)
            ->first();
        if ($appResult) {
            $server_key = $appResult->firebase_api_key;
        }
        return $server_key;
    }

    public static function getAppIcon($app_id, $app_group_id, $platform)
    {
        $app_logo = '';
        $appResult = Apps::where('app_group_id', $app_group_id)
            ->where('app_id', $app_id)
            ->where('platform', $platform)
            ->where('is_active', 1)
            ->where('deleted_at', NULL)
            ->first();
        if ($appResult) {
            $app_logo = $appResult->logo;
        }
        return $app_logo;
    }

    public static function getTrackkey($track_key, $deviceToken)
    {
        $campaignTrackey = CampaignTracking::
        leftjoin('campaign', 'campaign.id', '=', 'campaign_tracking.campaign_id')
            ->whereIn('campaign_tracking.track_key', $track_key)
            ->where('campaign_tracking.device_key', $deviceToken)
            ->first();
        if (!$campaignTrackey) {
            throw new \Exception('Campaign Does Not Exist');
        }
        return $campaignTrackey;
    }

    public function getRowIds($user)
    {
      //  dd($user);
        $rowIds = AppUsers::where('company_id','=',$user['company_id'])
            ->where('user_id', $user['user_id'])
            ->where('app_id', $user['appId'])
            ->first();
        if (!$rowIds) {
            throw new \Exception('Row Ids not found');
        }
        return $rowIds;
    }

    public function getNewFeedUsersFromSegmentCache($newsFeed, $userRowId, $appGroupId)
    {
        for ($i = 0; $i < count($newsFeed); $i++) {
            for ($j = $i + 1; $j < count($newsFeed); $j++) {
                if ($newsFeed[$i]->id == $newsFeed[$j]->id) {
                    unset($newsFeed[$j]);
                    $j--;
                    $newsFeed = array_values($newsFeed);
                }
            }
        }
        $rowIds = [];
        $segmentCache = new AppGroupSegmentCache();
        for ($val = 0; $val < count($newsFeed); $val++) {
            $rowIds = $segmentCache->getAppGroupSegmentRowsCache($appGroupId, $newsFeed[$val]->segment_id);
            if (!in_array($userRowId, $rowIds)) {
                unset($newsFeed[$val]);
                $newsFeed = array_values($newsFeed);
                $val--;
            }

        }
        return $newsFeed;

        /*
        $newsFeed = (array)$newsFeed;
        dd($newsFeed);
        $rowIds = [];
        $segmentCache = new AppGroupSegmentCache();
        for ($val = 0; $val < count($newsFeed); $val++) {

            //$rowIds = array_unique(array_merge($rowIds, $segmentCache->getAppGroupSegmentRowsCache($appGroupId,$newsFeed[$val]->segment_id) == null ? [] :
            //    $segmentCache->getAppGroupSegmentRowsCache($appGroupId,$newsFeed[$val]->segment_id)));
            $rowIds = $segmentCache->getAppGroupSegmentRowsCache($appGroupId, $newsFeed[$val]->segment_id);
            if (!in_array($userRowId, $rowIds)) {
                unset($newsFeed[$val]);
            }

        }

        return array_unique($newsFeed);*/
    }

    public function getUserDeviceInfo($params)
    {
        $appUserToken = AppUserTokens::
        leftjoin('language', 'language.code', 'app_user_token.lang')
            ->where('app_user_token.row_id', $params['row_id'])
            ->where('app_user_token.user_id', $params['user_id'])
            ->where('app_user_token.app_id', $params['appId'])
            ->orderBy('app_user_token.updated_at', 'DESC')
            ->first(['language.id as language_id', 'language.code as language_code']);
        if (!$appUserToken) {
            throw new \Exception('Device info not found for this user');
        }
        return $appUserToken;
    }

    public function getNewsFeedTranslation($newsFeed, $param)
    {
        $finalResponse = [];
        for ($val = 0; $val < count($newsFeed); $val++) {
            $translationList = Translation::where('translatable_id', $newsFeed[$val]->id)
                ->where('translatable_type', 'newsfeed')
                ->where('language_id', '=', $param['language_id'])->get();
            if (count($translationList) > 0) {
                for ($transVal = 0; $transVal < count($translationList); $transVal++) {
                    $linkTracking = \GuzzleHttp\json_decode($newsFeed[$val]->links, true);
                    $finalResponse[] = array(
                        'news_feed_id' => $newsFeed[$val]->id,
                        'news_feed_links' => $linkTracking,
                        'location_id' => $newsFeed[$val]->location_id,
                        'segment_id' => $newsFeed[$val]->segment_id,
                        'language_id' => $translationList[$transVal]['language_id'],
                        'content' => \GuzzleHttp\json_decode($translationList[$transVal]['template'], true)
                    );
                }
            }

        }
        return $finalResponse;
    }

    public static function fetchTinyUrl($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, 'http://tinyurl.com/api-create.php?url='.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
