<?php

namespace App\Components;

use App\Campaign;
use App\CampaignQueue;
use App\Helpers\CampaignCappingHelper;
use App\Helpers\CampaignDeliveryControlHelper;
use App\Queue;
use Carbon\Carbon;

class CampaignComponent
{
    /**
     * @param int    $itemId
     * @param string $itemType
     * @param int    $rowId
     *
     * @throws \Exception
     *
     * @return array
     */
    public function dispatch($itemId, $itemType = 'queue', $rowId = null)
    {
        if (in_array($itemType, ['queue'])) {
            $queue = Queue::where([
                ['id', '=', $itemId],
                ['status', '<>', 'Completed']
            ])->firstOrFail();

            $queue->status = 'Processing';
            $queue->save();

            $data = \GuzzleHttp\json_decode($queue->data, true);
            $rowId = !empty($data['rowId']) ? $data['rowId'] : '';

            $campaign = Campaign::findOrFail($data['campaignId']);
        } elseif (in_array($itemType, ['campaign'])) {
            if (empty($rowId)) {
                throw new \Exception("Row ID is required when dispatching action/api trigger campaigns.");
            }

            $campaign = Campaign::findOrFail($itemId);
        } elseif (in_array($itemType, ['campaign_queue'])) {
            $itemId->status = CampaignQueue::STATUS_PROCESSING;
            $campaign = Campaign::findOrFail($itemId->campaign_id);
        }

        if ($campaign->isDraft()) {
            throw new \Exception("Cannot dispatch campaign with status 'draft'", 422);
        } elseif ($campaign->isSuspended()) {
            throw new \Exception("Cannot dispatch campaign with status 'suspended'", 422);
        } elseif ($campaign->isExpired()) {
            throw new \Exception("Cannot dispatch campaign with status 'expired'", 422);
        } else {
            if ($campaign->isDeliveryTypeSchedule()) {
                $now = Carbon::now(config('app.timezone'));
                $startDate = Carbon::parse($campaign->start_time);

                if ($now->lt($startDate)) {
                    throw new \Exception("Cannot dispatch campaign as its start date is greater than current date", 422);
                }
            }
        }

        $company = $campaign->company;
        if (empty($company)) {
            throw new \Exception("Company not found.", 404);
        }

        if ((bool)$company->status === false) {
            throw new \Exception("Company is currently disabled.", 401);
        }

        if ((bool)$company->is_deleted === true) {
            throw new \Exception("Company is currently disabled.", 403);
        }

        $campaign_type = $campaign->campaign_type;

        $segment_rowIds = CompanyAttributeData::campaignSegments($campaign->company_id, $campaign->id, true);
        if (empty($segment_rowIds)) {
            throw new \Exception("No segment data found", 404);
        }

        if (!empty($rowId)) {
            if (!in_array($rowId, $segment_rowIds)) {
                throw new \Exception("Invalid User ID", 404);
            }

            $segment_rowIds = [$rowId];
        }

        $company_rows = CompanyAttributeData::rows($campaign->company_id);
        if (empty($company_rows)) {
            throw new \Exception("No attribute data cache found", 404);
        }

        $campaign_rows = collect($company_rows)->sortByDesc('last_login')->filter(function ($row, $key) use ($segment_rowIds) {
            return in_array($key, $segment_rowIds) ? $row : null;
        });
        if ($campaign_type->isEmail()) {
            $campaign_rows = $campaign_rows->filter(function ($row) {
                if (isset($row['email_notification'])) {
                    return ((bool) $row['email_notification'] === true) ? $row : null;
                }

                return $row;
            });
        } else {
            $campaign_rows = $campaign_rows->filter(function ($row) {
                if (isset($row['enable_notification'])) {
                    return ((bool) $row['enable_notification'] === true) ? $row : null;
                }

                return $row;
            });
        }

        if ($campaign_type->isInapp()) {
            $campaign_rows = $campaign_rows->filter(function ($row) {
                return (!empty($row['is_login']) && ((bool) $row['is_login'] === true)) ? $row : null;
            });
        }

        $skip_devices = [
            'device_token' => [],
            'firebase_key' => []
        ];

        $field = 'email';
        if ($campaign_type->isPush()) { $field = $campaign->isPlatformAndroid() ? 'firebase_key' : 'device_key'; }
        if ($campaign_type->isInapp()) { $field = 'firebase_key'; }

        $campaign_rows = $campaign_rows->toArray();

        if (!empty($campaign_rows) && ($campaign->isDeliveryControlEnabled() === true)) {
            foreach ($campaign_rows as $row_id => $campaign_row) {
                if ($campaign_type->isPush() && $campaign->isPlatformUniversal()) {
                    $apns = CampaignDeliveryControlHelper::canSendAgain($campaign, $row_id, 'device_key');
                    $fcm = CampaignDeliveryControlHelper::canSendAgain($campaign, $row_id, 'firebase_key');

                    if (($apns === false) && ($fcm === false)) {
                        unset($campaign_rows[$row_id]);
                    } else {
                        if ($apns === false) {
                            $skip_devices['device_token'][] = $campaign_row['device_token'];
                        }

                        if ($fcm === false) {
                            $skip_devices['firebase_key'][] = $campaign_row['fire_base_key'];
                        }
                    }
                } else {
                    $deliverAgain = CampaignDeliveryControlHelper::canSendAgain($campaign, $row_id, $field);
                    if ($deliverAgain === false) {
                        unset($campaign_rows[$row_id]);
                    }
                }
            }
        }

        if (!empty($campaign_rows) && ($campaign->isCappingEnabled() === true)) {
            $cap_type = CampaignCappingHelper::cappingType($campaign_type);
            $cap_rule = $company->capRule($cap_type);

            if (!empty($cap_type) && !empty($cap_rule->company_id)) {
                foreach ($campaign_rows as $row_id => $campaign_row) {
                    if ($campaign_type->isPush() && $campaign->isPlatformUniversal()) {
                        $apns = CampaignCappingHelper::cappingEnabled($campaign, $cap_rule, $row_id, 'device_key');
                        $fcm = CampaignCappingHelper::cappingEnabled($campaign, $cap_rule, $row_id, 'firebase_key');

                        if (($apns === true) && ($fcm === true)) {
                            unset($campaign_rows[$row_id]);
                        } else {
                            if ($apns === true) {
                                $skip_devices['device_token'][] = $campaign_row['device_token'];
                            }

                            if ($fcm === true) {
                                $skip_devices['firebase_key'][] = $campaign_row['fire_base_key'];
                            }
                        }
                    } else {
                        $deliverAgain = CampaignCappingHelper::cappingEnabled($campaign, $cap_rule, $row_id, $field);
                        if ($deliverAgain === true) {
                            unset($campaign_rows[$row_id]);
                        }
                    }
                }
            }
        }

        if (empty($campaign_rows)) {
            $errorMsg = [];
            $errorMsg[] = "No attribute data cache found for campaign. This might be due to one of the following scenarios:";
            $errorMsg[] = "1. No attribute data cache was created for the campaign.";
            $errorMsg[] = "2. Delivery control is enabled for campaign, and associated users have already been sent the campaign and delivery limit time hasn't been reached yet";
            throw new \Exception(implode("\n", $errorMsg), 404);
        }

        if ($campaign_type->isPush()) {
            $code_clause = ['device_token', 'firebase_key', 'fire_base_key'];
            if ($campaign->isPlatformAndroid()) { $code_clause = ['firebase_key', 'fire_base_key', 'device_type']; }
            if ($campaign->isPlatformIOS()) { $code_clause = ['device_token']; }
        } elseif ($campaign_type->isInapp()) {
            $code_clause = ['firebase_key', 'fire_base_key', 'device_type'];
        }

        $device_tokens = [];
        $temp = [];
        if (!empty($campaign_rows)) {
            if (!empty($code_clause) && in_array('device_type', $code_clause)) {
                foreach ($campaign_rows as $row_id => $campaign_row) {
                    if (!isset($device_tokens[$row_id])) {
                        $device_tokens[$row_id] = [];
                    }

                    foreach ($campaign_row as $key => $value) {
                        if (!in_array($key, $code_clause)) {
                            continue;
                        }

                        $device_tokens[$row_id][$key] = $value;
                    }
                }

                $temp['firebase']['android'] = collect($device_tokens)->filter(function ($device) {
                    return (!empty($device['device_type']) && in_array(strtolower($device['device_type']), ['android'])) ? $device : null;
                })->pluck('fire_base_key')->unique()->toArray();
                $temp['firebase']['ios'] = collect($device_tokens)->filter(function ($device) {
                    return (!empty($device['device_type']) && in_array(strtolower($device['device_type']), ['ios','iphone'])) ? $device : null;
                })->pluck('fire_base_key')->unique()->toArray();
                $device_tokens = $temp;
            }
        }

        foreach ($campaign_rows as $row_id => $campaign_row) {
            $lang = !empty($campaign_row['lang']) ? $campaign_row['lang'] : config('engagement.language');
            $message = $campaign->{$lang};

            $campaign_rows[$row_id]['row_id'] = $row_id;

            if (!empty($lang) && !empty($message)) {
                $campaign_rows[$row_id]['message'] = $message;
            } else {
                $campaign_rows[$row_id]['message'] = $campaign->{config('engagement.language')};
            }
        }

        $response = [
            'campaign_id'   => $campaign->id,
            'users'         => $campaign_rows,
            'subject'       => $campaign->subject,
            'device_tokens' => $device_tokens,
            'skip_devices'  => $skip_devices,
        ];

        if (empty($code_clause)) {
            $emails = [];

            foreach ($response['users'] as $key => $user) {
                $emails[$user['email']] = $user;
            }

            $response['email'] = $emails;
            $response['from'] = ['address' => $campaign->from_email, 'name' => $campaign->from_name];
        }

        return $response;
    }


    public function dispatchCampaign(Campaign $campaign, $rowIds)
    { 
        $campaign_type = $campaign->campaign_type;
        $segment_rowIds = $rowIds;


        $company_rows = CompanyAttributeData::rowsData($campaign->company_id,$rowIds);
        if (empty($company_rows)) {
           return null;
           // throw new \Exception("No attribute data cache found", 404);
        }

        $campaign_rows = collect($company_rows)->sortByDesc('last_login')->filter(function ($row, $key) use ($segment_rowIds) {
            return in_array($key, $segment_rowIds) ? $row : null;
        });
        if ($campaign_type->isEmail()) {
            $campaign_rows = $campaign_rows->filter(function ($row) {
                if (isset($row['email_notification'])) {
                    return ((bool) $row['email_notification'] === true) ? $row : null;
                }

                return $row;
            });
        } else {
            $campaign_rows = $campaign_rows->filter(function ($row) {
                if (isset($row['enable_notification'])) {
                    return ((bool) $row['enable_notification'] === true) ? $row : null;
                }

                return $row;
            });
        }

        if ($campaign_type->isInapp()) {
            $campaign_rows = $campaign_rows->filter(function ($row) {
                return (!empty($row['is_login']) && ((bool) $row['is_login'] === true)) ? $row : null;
            });
        }

        $skip_devices = [
            'device_token' => [],
            'firebase_key' => []
        ];

        $field = 'email';
        if ($campaign_type->isPush()) { $field = $campaign->isPlatformAndroid() ? 'firebase_key' : 'device_key'; }
        if ($campaign_type->isInapp()) { $field = 'firebase_key'; }

        $campaign_rows = $campaign_rows->toArray();

        if (!empty($campaign_rows) && ($campaign->isDeliveryControlEnabled() === true)) {
            foreach ($campaign_rows as $row_id => $campaign_row) {
                if ($campaign_type->isPush() && $campaign->isPlatformUniversal()) {
                    $apns = CampaignDeliveryControlHelper::canSendAgain($campaign, $row_id, 'device_key');
                    $fcm = CampaignDeliveryControlHelper::canSendAgain($campaign, $row_id, 'firebase_key');

                    if (($apns === false) && ($fcm === false)) {
                        unset($campaign_rows[$row_id]);
                    } else {
                        if ($apns === false) {
                            $skip_devices['device_token'][] = $campaign_row['device_token'];
                        }

                        if ($fcm === false) {
                            $skip_devices['firebase_key'][] = $campaign_row['fire_base_key'];
                        }
                    }
                } else {
                    $deliverAgain = CampaignDeliveryControlHelper::canSendAgain($campaign, $row_id, $field);
                    if ($deliverAgain === false) {
                        unset($campaign_rows[$row_id]);
                    }
                }
            }
        }

        if (!empty($campaign_rows) && ($campaign->isCappingEnabled() === true)) {
            $cap_type = CampaignCappingHelper::cappingType($campaign_type);
            $cap_rule = $company->capRule($cap_type);

            if (!empty($cap_type) && !empty($cap_rule->company_id)) {
                foreach ($campaign_rows as $row_id => $campaign_row) {
                    if ($campaign_type->isPush() && $campaign->isPlatformUniversal()) {
                        $apns = CampaignCappingHelper::cappingEnabled($campaign, $cap_rule, $row_id, 'device_key');
                        $fcm = CampaignCappingHelper::cappingEnabled($campaign, $cap_rule, $row_id, 'firebase_key');

                        if (($apns === true) && ($fcm === true)) {
                            unset($campaign_rows[$row_id]);
                        } else {
                            if ($apns === true) {
                                $skip_devices['device_token'][] = $campaign_row['device_token'];
                            }

                            if ($fcm === true) {
                                $skip_devices['firebase_key'][] = $campaign_row['fire_base_key'];
                            }
                        }
                    } else {
                        $deliverAgain = CampaignCappingHelper::cappingEnabled($campaign, $cap_rule, $row_id, $field);
                        if ($deliverAgain === true) {
                            unset($campaign_rows[$row_id]);
                        }
                    }
                }
            }
        }

        if (empty($campaign_rows)) {
            $errorMsg = [];
            $errorMsg[] = "No attribute data cache found for campaign. This might be due to one of the following scenarios:";
            $errorMsg[] = "1. No attribute data cache was created for the campaign.";
            $errorMsg[] = "2. Delivery control is enabled for campaign, and associated users have already been sent the campaign and delivery limit time hasn't been reached yet";
            
            return null;
            //throw new \Exception(implode("\n", $errorMsg), 404);
        }

        if ($campaign_type->isPush()) {
            $code_clause = ['device_token', 'firebase_key', 'fire_base_key'];
            if ($campaign->isPlatformAndroid()) { $code_clause = ['firebase_key', 'fire_base_key', 'device_type']; }
            if ($campaign->isPlatformIOS()) { $code_clause = ['device_token']; }
        } elseif ($campaign_type->isInapp()) {
            $code_clause = ['firebase_key', 'fire_base_key', 'device_type'];
        }

        $device_tokens = [];
        $temp = [];
        if (!empty($campaign_rows)) {
            if (!empty($code_clause) && in_array('device_type', $code_clause)) {
                foreach ($campaign_rows as $row_id => $campaign_row) {
                    if (!isset($device_tokens[$row_id])) {
                        $device_tokens[$row_id] = [];
                    }

                    foreach ($campaign_row as $key => $value) {
                        if (!in_array($key, $code_clause)) {
                            continue;
                        }

                        $device_tokens[$row_id][$key] = $value;
                    }
                }

                $temp['firebase']['android'] = collect($device_tokens)->filter(function ($device) {
                    return (!empty($device['device_type']) && in_array(strtolower($device['device_type']), ['android'])) ? $device : null;
                })->pluck('fire_base_key')->unique()->toArray();
                $temp['firebase']['ios'] = collect($device_tokens)->filter(function ($device) {
                    return (!empty($device['device_type']) && in_array(strtolower($device['device_type']), ['ios','iphone'])) ? $device : null;
                })->pluck('fire_base_key')->unique()->toArray();
                $device_tokens = $temp;
            }
        }

        foreach ($campaign_rows as $row_id => $campaign_row) {
            $lang = !empty($campaign_row['lang']) ? $campaign_row['lang'] : config('engagement.language');
            $message = $campaign->{$lang};

            $campaign_rows[$row_id]['row_id'] = $row_id;

            if (!empty($lang) && !empty($message)) {
                $campaign_rows[$row_id]['message'] = $message;
            } else {
                $campaign_rows[$row_id]['message'] = $campaign->{config('engagement.language')};
            }
        }

        $response = [
            'campaign_id'   => $campaign->id,
            'users'         => $campaign_rows,
            'subject'       => $campaign->subject,
            'device_tokens' => $device_tokens,
            'skip_devices'  => $skip_devices,
        ];

        if (empty($code_clause)) {
            $emails = [];

            foreach ($response['users'] as $key => $user) {
                $emails[$user['email']] = $user;
            }

            $response['email'] = $emails;
            $response['from'] = ['address' => $campaign->from_email, 'name' => $campaign->from_name];
        }

        return $response;
    }
}
