<?php

namespace App;

use App\Components\CampaignTrackingData;
use App\Components\CompanyAttributeData;
use App\Components\NewsfeedTrackingData;

trait GathersServicesStats
{
    use GatherCampaignStats, GatherNewsfeedStats, GatherConversionStats;

    /**
     * @var bool
     */
    private $isCompany;

    /**
     * @var array
     */
    private $campaignIds;

    /**
     * @var array
     */
    private $newsfeedIds;

    /**
     * @var array
     */
    private $colors = [
        'sent'      => '#2b8689',
        'failed'    => '#ff2c4d',
        'queued'    => '#f4d63a',
        'clicks'    => '#75acef',
        'views'     => '#45444a',
        'android'   => '#00a6d0',
        'ios'       => '#fe8743',
        'web'       => '#f4d63a',
    ];

    /**
     * @throws \Exception
     *
     * @return array
     */
    protected function getFilterzero($array)
    {
        foreach ($array as $array_key => $array_item) {
            if ($array[$array_key] === 0) {
               $array[$array_key]=0;
            }
        }
        return array_values($array);
    }

    protected function gatherUserInfoStats()
    {
        $data = [
            'users' => [
                'total' => 0,
                'email' => 0,
                'push'  => 0,
                'inapp' => 0,
            ],
            'newsfeed' => [
                'clicks' => [
                    'total'     => 0,
                    'ios'       => 0,
                    'android'   => 0,
                    'web'       => 0,
                ],
                'views' => [
                    'total'     => 0,
                    'ios'       => 0,
                    'android'   => 0,
                    'web'       => 0,
                ],
            ],
            'campaign' => [
                'sent' => [
                    'total' => 0,
                    'email' => 0,
                    'push'  => 0,
                    'inapp' => 0,
                ],
                'failed' => [
                    'total' => 0,
                    'email' => 0,
                    'push'  => 0,
                    'inapp' => 0,
                ],
                'queued' => [
                    'total' => 0,
                    'email' => 0,
                    'push'  => 0,
                    'inapp' => 0,
                ],
            ],
        ];

        $columnRefs = [
            'email' => 'email',
            'firebase_key' => 'inapp',
            'device_key' => 'push'
        ];

        $defaultData = $data;

        $userAttributes = collect();
        $campaigns = collect();
        $newsfeed_clicks = collect();
        $newsfeed_views = collect();

        try {
            $user = auth()->user();

            if ($user->hasRole(config('engagement.roles.company'))) {
                $campaigns = $campaigns->merge(
                    CampaignTrackingData::campaigns($user->id)
                );

                $userAttributes = $userAttributes->merge(
                    CompanyAttributeData::rows($user->id, true)
                );

                $newsfeeds = NewsfeedTrackingData::newsfeeds($user->id);

                if (!empty($newsfeeds['clicks'])) {
                    $newsfeed_clicks = $newsfeed_clicks->merge($newsfeeds['clicks']);
                }

                if (!empty($newsfeeds['views'])) {
                    $newsfeed_views = $newsfeed_views->merge($newsfeeds['views']);
                }
            } elseif ($user->hasRole(config('engagement.roles.super'))) {
                $companyRole = Role::findByName(config('engagement.roles.company'));
                if (!empty($companyRole->id)) {
                    $companies = $companyRole->users;

                    if ($companies->count() > 0) {
                        foreach ($companies as $company) {
                            $userAttributes = $userAttributes->merge(
                                CompanyAttributeData::rows($company->id, true)
                            );

                            $campaigns = $campaigns->merge(
                                CampaignTrackingData::campaigns($company->id)
                            );

                            $newsfeeds = NewsfeedTrackingData::newsfeeds($company->id);

                            if (!empty($newsfeeds['clicks'])) {
                                $newsfeed_clicks = $newsfeed_clicks->merge($newsfeeds['clicks']);
                            }

                            if (!empty($newsfeeds['views'])) {
                                $newsfeed_views = $newsfeed_views->merge($newsfeeds['views']);
                            }
                        }
                    }

                    $userAttributes = $userAttributes->filter();
                    $campaigns = $campaigns->filter();
                    $newsfeed_clicks = $newsfeed_clicks->filter();
                    $newsfeed_views = $newsfeed_views->filter();
                }
            } else {
                throw new \Exception("No stats found");
            }

            if ($userAttributes->count() > 0) {
                $userCount = $userAttributes->sum();

                $data['users']['total'] += $userCount;
            }

            if ($campaigns->count() > 0) {
                foreach (array_keys($columnRefs) as $column) {
                    $sent = $campaigns->filter(function ($track) use($column) {
                        return (($track[$column] != '') && ((bool)$track['sent'] === true)) ? $track : null;
                    })->count();

                    $failed = $campaigns->filter(function ($track) use($column) {
                        return (($track[$column] != '') && ((bool)$track['sent'] === false) && isset($track['log']['id'])) ? $track : null;
                    })->count();

                    $queued = $campaigns->filter(function ($track) use($column) {
                        return (($track[$column] != '') && ((bool)$track['sent'] === false) && !isset($track['log']['id'])) ? $track : null;
                    })->count();

                    $data['campaign']['sent'][$columnRefs[$column]] += $sent;
                    $data['campaign']['failed'][$columnRefs[$column]] += $failed;
                    $data['campaign']['queued'][$columnRefs[$column]] += $queued;

                    $data['campaign']['sent']['total'] += $sent;
                    $data['campaign']['failed']['total'] += $failed;
                    $data['campaign']['queued']['total'] += $queued;
                }
            }

            if ($newsfeed_clicks->count() > 0 ) {
                $data['newsfeed']['clicks']['total'] += $newsfeed_clicks->count();

                $ios_clicks = $newsfeed_clicks->filter(function ($tracking) {
                    return in_array($tracking['device'], ["iphone","iPad","ios"]) ? $tracking : null;
                })->count();

                $android_clicks = $newsfeed_clicks->filter(function ($tracking) {
                    return in_array($tracking['device'], ["android"]) ? $tracking : null;
                })->count();

                $web_clicks = $newsfeed_clicks->filter(function ($tracking) {
                    return !in_array($tracking['device'], ["iphone","ios","iPad","android"]) ? $tracking : null;
                })->count();

                $data['newsfeed']['clicks']['ios'] += $ios_clicks;
                $data['newsfeed']['clicks']['android'] += $android_clicks;
                $data['newsfeed']['clicks']['web'] += $web_clicks;
            }

            if ($newsfeed_views->count() > 0 ) {
                $data['newsfeed']['views']['total'] += $newsfeed_views->sum('viewed');

                $ios_views = $newsfeed_views->filter(function ($feed) {
                    return in_array($feed['platform'], ["iphone","ipad","ios"]) ? $feed : null;
                })->sum('viewed');

                $android_views = $newsfeed_views->filter(function ($feed) {
                    return in_array($feed['platform'], ["android"]) ? $feed : null;
                })->sum('viewed');

                $web_views = $newsfeed_views->filter(function ($feed) {
                    return !in_array($feed['platform'], ["iphone","ios","ipad","android"]) ? $feed : null;
                })->sum('viewed');

                $data['newsfeed']['views']['ios'] += $ios_views;
                $data['newsfeed']['views']['android'] += $android_views;
                $data['newsfeed']['views']['web'] += $web_views;
            }
        } catch (\Exception $exception) {
            $data = $defaultData;
        }

        return $data;
    }
}