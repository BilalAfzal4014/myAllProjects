<?php

namespace App\Http\Resources\V1;

use App\Components\CompanyAttributeData;
use App\Components\CompileActiveAppsList;
use App\Libraries\VerifyEmail;
use App\Messaging\Message;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;

class MessagingResource
{
    use CompileActiveAppsList;

    /**
     * Send notification.
     *
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function process($data)
    {
        if (isset($data['auto_close'])) {
            $autoClose =  $data['auto_close'];
            if($autoClose=="false")
            {
                $autoClose=false;
            }else{
                $autoClose=true;
            }
        } else {
            $autoClose = true;
        }
        $custom = !empty($data['custom']) ? $data['custom'] : [];

        $errors = $this->validateRequest($data, $custom);
        if (!empty($errors)) {
            return $errors;
        }

        if (in_array($data['type'], ['inapp', 'push'])) {
            if (in_array(strtolower($data['platform']), ['ios', 'android'])) {
                $platform = [strtolower($data['platform'])];
            } else {
                $platform = ['ios', 'android'];
            }

            $data['campaign_type'] = in_array($data['type'], ['push']) ? 'push' : 'inapp';
        }

        $company = User::where('company_key', $data['company_key'])->first();

        if (empty($company)) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ],
                'errors' => ['No company found'],
            ], Response::HTTP_NOT_FOUND);
        }

        if ((bool)$company->status === false) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'status' => 'error',
                ],
                'errors' => ['Company is currently disabled.'],
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ((bool)$company->is_deleted === true) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_FORBIDDEN,
                    'status' => 'error',
                ],
                'errors' => ['Company is currently temporarily suspended.'],
            ], Response::HTTP_FORBIDDEN);
        }

        if (in_array($data['type'], ['inapp', 'push']) && in_array(strtolower($data['platform']), ['ios', 'android', 'universal'])) {
            $apps = $this->getActiveApps($company->apps);

            if ($apps->count() > 0) {
                $apps = $apps->filter(function ($app) use ($platform) {
                    return in_array(strtolower($app->platform), $platform) ? $app : null;
                });
            }

            if ($apps->count() == 0) {
                return response()->json([
                    'meta' => [
                        'code' => Response::HTTP_NOT_FOUND,
                        'status' => 'error',
                    ],
                    'errors' => ['No associated apps found for company'],
                ], Response::HTTP_NOT_FOUND);
            }
        }

        if (in_array($data['type'], ['push']) && in_array(strtolower($data['platform']), ['android'])) {
            $data['type'] = 'inapp';
        }

        $data['from'] = [];
        if (in_array($data['type'], ['email'])) {
            if (!empty($data['sender_email'])) {
                $data['from']['address'] = $data['sender_email'];
                $data['from']['name'] = !empty($data['sender_name']) ? $data['sender_name'] : "";
            }
        }

        $filter_type = !empty($data['filter_type']) ? $data['filter_type'] : "";
        $items = [];
        if (!empty($filter_type)) {
            if ($filter_type == 'user_id') {
                foreach ($data['items'] as $key => $value) {
                    $items[] = (int)$value;
                }
            } else {
                $items = $data['items'];
            }
        }

        $row_id = '';
        if (!empty($data['row_id'])) {
            $row_id = $data['row_id'];
        }

        $code_clause = 'email';
        if (in_array($data['type'], ['push'])) {
            $code_clause = 'device_token';
        } elseif (in_array($data['type'], ['inapp'])) {
            $code_clause = 'fire_base_key';
        }

        $attributes = collect(CompanyAttributeData::rowsData($company->id,$row_id));
        if ($attributes->count() > 0) {
            $attributes = $attributes->filter(function ($attribute, $key) use ($filter_type, $items, $row_id) {
                if (!empty($row_id)) {
                    return in_array($key, $row_id) ? $attribute : null;
                } else {
                    return (!empty($attribute[$filter_type]) && in_array($attribute[$filter_type], $items)) ? $attribute : null;
                }
            })->filter(function ($attribute) use ($filter_type, $data) {
                if (in_array($data['type'], ['email'])) {
                    if (isset($attribute['email_notification'])) {
                        return ((bool)$attribute['email_notification'] === true) ? $attribute : null;
                    }
                } else {
                    if (isset($attribute['enable_notification'])) {
                        return ((bool)$attribute['enable_notification'] === true) ? $attribute : null;
                    }
                }

                return $attribute;
            });

            try {
                if (!empty($data['device_token'])) {
                    $attributes = $attributes->filter(function ($attribute) use ($data) {
                        return in_array($attribute['device_token'], [$data['device_token']]) ? $attribute : null;
                    });
                }

                if (!empty($data['firebase_key'])) {
                    $attributes = $attributes->filter(function ($attribute) use ($data) {
                        return in_array($attribute['fire_base_key'], [$data['firebase_key']]) ? $attribute : null;
                    });
                }

                if (in_array($data['type'], ['inapp']) && in_array(strtolower($data['platform']), ['ios', 'android'])) {
                    $attributes = $attributes->filter(function ($attribute) use ($platform) {
                        return in_array(strtolower($attribute['device_type']), $platform) ? $attribute : null;
                    });
                }
            } catch (\Exception $exception) {

            }
        }

        if ($attributes->count() == 0) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ],
                'errors' => ['No attribute data found'],
            ], Response::HTTP_NOT_FOUND);
        }

        $device_tokens = [];
        $device_keys = [];
        $device_apps = [];

        $skip_duplicate = config('engagement.message.skip_duplicate');

        if (in_array($data['type'], ['inapp', 'push']) && in_array(strtolower($data['platform']), ['ios', 'android', 'universal'])) {
            $app_ids = $attributes->pluck('app_id')->unique()->toArray();

            $device_apps = $apps->filter(function ($app) use ($app_ids, $platform) {
                return (in_array($app->app_id, $app_ids) && in_array(strtolower($app->platform), $platform)) ? $app : null;
            });

            if ($device_apps->count() == 0) {
                return response()->json([
                    'meta' => [
                        'code' => Response::HTTP_NOT_FOUND,
                        'status' => 'error',
                    ],
                    'errors' => ['No device data found'],
                ], Response::HTTP_NOT_FOUND);
            }

            $apps = [];
            $apps['ios'] = $device_apps->filter(function ($app) {
                return in_array(strtolower($app->platform), ['ios']) ? $app : null;
            })->map(function ($app) {
                return [$app->app_id => $app];
            })->flatten(1)->toArray();

            $apps['android'] = $device_apps->filter(function ($app) {
                return in_array(strtolower($app->platform), ['android']) ? $app : null;
            })->map(function ($app) {
                return [$app->app_id => $app];
            })->flatten(1)->toArray();

            $device_apps = $apps;
            unset($apps);
        }

        foreach ($attributes as $row_id => $attribute) {
            if (empty($attribute[$code_clause])) {
                continue;
            }

            if (in_array($data['type'], ['email', 'inapp', 'push'])) {
                if (isset($skip_duplicate) && ((bool)$skip_duplicate === true)) {
                    if (in_array($attribute[$code_clause], $device_keys)) {
                        continue;
                    }

                    if (!in_array($attribute[$code_clause], $device_keys)) {
                        $device_keys[] = $attribute[$code_clause];
                    }
                } else {
                    $device_keys[] = $attribute[$code_clause];
                }
            }
            $data['auto_close'] = $autoClose;
            switch ($data['type']) {
                case 'push':
                    $iosPayload = $this->setIOSPushPayload($data, $attribute, $device_apps, $company);
                    if (!empty($iosPayload)) {
                        $iosPayload['row_id'] = $row_id;
                        $device_tokens[] = $iosPayload;
                    }

                    if (strtolower($data['platform']) === 'universal') {
                        $fcmPayload = $this->setFirebasePayload($data, $attribute, $device_apps);
                        if (!empty($fcmPayload)) {
                            foreach ($fcmPayload as $k => $item) {
                                $fcmPayload[$k]['row_id'] = $row_id;
                            }

                            $device_tokens = array_merge($device_tokens, $fcmPayload);
                        }
                    }

                    break;
                case 'inapp':

                    $fcmPayload = $this->setFirebasePayload($data, $attribute, $device_apps);

                    if (!empty($fcmPayload)) {
                        foreach ($fcmPayload as $k => $item) {
                            $fcmPayload[$k]['row_id'] = $row_id;
                        }
                        $device_tokens = array_merge($device_tokens, $fcmPayload);
                    }

                    break;
                case 'email':
                    $emailPayload = $this->setEmailPayload($data, $attribute);
                    $emailPayload['row_id'] = $row_id;

                    $verify_email = config('engagement.message.verify_email');
                    if (isset($verify_email) && ((bool)$verify_email === true)) {
                        $verified = (new VerifyEmail($company->id))->verified($emailPayload['value']);
                        if ($verified == false) {
                            continue;
                        }
                    } else {
                        if (filter_var($emailPayload['value'], FILTER_VALIDATE_EMAIL) === false) {
                            continue;
                        }
                    }

                    $device_tokens[] = $emailPayload;
                    break;
            }
        }

        if (empty($device_tokens)) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'status' => 'error',
                ],
                'errors' => ['No data found'],
            ], Response::HTTP_NOT_FOUND);
        }

        $response = [];
        foreach ($device_tokens as $device_token) {
            $message = new Notification();

            try {
                if ($device_token['type'] == 'push') {
                    $adapter = config('engagement.message.push.adapter');
                    $notification = new Message($adapter);

                    $tokenData = [
                        'device_token' => $device_token['value'],
                        'title' => $device_token['title'],
                        'target' => $device_token['target'],
                        'backgroundColor' => $device_token['backgroundColor'],
                        'icon' => $device_token['icon'],
                        'message' => (is_array($device_token['message'])) ? $device_token['message'] : trim($device_token['message'], '"'),
                        'company_name' => $device_token['company_name'],
                        'passphrase' => $device_token['passphrase'],
                        'certificate' => $device_token['certificate'],
                        'sandbox' => $device_token['sandbox'],
                        'is_silent' => $device_token['is_silent'],
                        'is_hero_platform' => $device_token['is_hero_platform'],
                        'params' => $device_token['params'],
                        'app_id' => $device_token['app_id'],
                        'auto_close' => $autoClose
                    ];

                    if (!empty($device_token['user_id'])) {
                        $tokenData['user_id'] = $device_token['user_id'];
                    }

                    // Set payload info for notifications.
                    $message->device_key = $device_token['value'];
                    $message->payload = base64_encode(serialize($tokenData));
                    $message->save();

                    $notification->setMessageTokens($tokenData);
                    $notification->compileData();

                    $notifyResponse = $notification->send();
                    //      dd($notifyResponse);
                    if (in_array(strtolower($notifyResponse['type']), ['error'])) {
                        throw new \Exception($notifyResponse['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    // Set sent status & time if message is successfully sent.
                    $message->sent = true;
                    $message->sent_at = Carbon::now();
                    $message->save();

                    $respStatus = 'success';
                    $respMessage = 'Push   notification sent successfully!';

                    $status = [
                        'code' => Response::HTTP_OK,
                        'status' => $respStatus,
                        'message' => $respMessage,
                        'row_id' => $device_token['row_id'],
                    ];

                    $message->log()->create([
                        'status' => $respStatus,
                        'message' => $respMessage,
                    ]);

                    $response[] = $status;
                } elseif ($device_token['type'] == 'inapp') {
                    $notification = new Message('firebase');

                    $tokenData = [
                        'alert' => $device_token['message'],
                    ];

                    $dataColumns = ['notification', 'android'];
                    foreach ($dataColumns as $column) {
                        if (!empty($tokenData['alert'][$column])) {
                            $tokenData[$column] = $tokenData['alert'][$column];
                            unset($tokenData['alert'][$column]);
                        }
                    }

                    if (!empty($device_token['user_id'])) {
                        $tokenData['alert']['user_id'] = $device_token['user_id'];
                    }

                    $tokenData['alert']['campaign_type'] = $device_token['campaign_type'];
                    $tokenData['alert']['backgroundColor'] = $device_token['backgroundColor'];
                    $tokenData['alert']['icon'] = $device_token['icon'];
                    $tokenData['alert']['is_silent'] = $device_token['is_silent'];
                    $tokenData['alert']['is_hero_platform'] = $device_token['is_hero_platform'];
                    $tokenData['alert']['params'] = $device_token['params'];
                    $tokenData['alert']['auto_close'] = $autoClose;
                    $tokenData['priority'] = 10;

                    // Set payload info for notifications.
                    $message->firebase_key = $device_token['value'];
                    $message->save();

                    if ($device_token['campaign_type'] == 'inapp') {
                        $tokenData['alert']['inapp_view_link'] = url('/notification/inapp/view/?id=' . $message->id);
                    }

                    $message->payload = base64_encode(serialize($tokenData));
                    $message->save();

                    if ($device_token['campaign_type'] == 'inapp') {
                        $tokenData['alert']['data'] = "success";
                    }

                    $notification->setApiKey($device_token['apiKey']);
                    $notification->setMessageTokens($device_token['value']);
                    $notification->compileData($tokenData);

                    $notifyResponse = $notification->send();
                    if (!empty($notifyResponse['results']['error'])) {
                        throw new \Exception($notifyResponse['results']['error'], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    // Set sent status & time if message is successfully sent.
                    $message->sent = true;
                    $message->sent_at = Carbon::now();
                    $message->save();

                    $respStatus = 'success';
                    $respMessage = ($device_token['campaign_type'] == 'push') ?
                        'Push notification sent successfully!' :
                        'InApp notification sent successfully!';

                    $status = [
                        'code' => Response::HTTP_OK,
                        'status' => $respStatus,
                        'message' => $respMessage,
                        'row_id' => $device_token['row_id'],
                    ];

                    $message->log()->create([
                        'status' => $respStatus,
                        'message' => $respMessage,
                    ]);

                    $response[] = $status;
                } elseif ($device_token['type'] == 'email') {
                    $notification = new Message();
                    $notification->setAdapter('email', 'ses');

                    $tokenData = [
                        'email' => $device_token['value'],
                        'subject' => $device_token['subject'],
                        'message' => $device_token['message'],
                        'from' => $data['from'],
                    ];

                    $notification->compileData($tokenData);

                    // Set payload info for notifications.
                    $message->email = $device_token['value'];
                    $message->payload = base64_encode(serialize($tokenData));
                    $message->save();

                    $messageResponse = $notification->send();
                    if (in_array($messageResponse['type'], ['error'])) {
                        throw new \Exception($messageResponse['message'], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    // Set sent status & time if message is successfully sent.
                    $message->sent = true;
                    $message->sent_at = Carbon::now();
                    $message->save();

                    $respStatus = 'success';
                    $respMessage = 'Email sent successfully!';

                    $status = [
                        'code' => Response::HTTP_OK,
                        'status' => $respStatus,
                        'message' => $respMessage,
                        'row_id' => $device_token['row_id'],
                    ];

                    $message->log()->create([
                        'status' => $respStatus,
                        'message' => $respMessage,
                    ]);

                    $response[] = $status;
                }
            } catch (\Exception $exception) {
                $error_message = $exception->getMessage();

                $respStatus = 'error';

                $status = [
                    'code' => $exception->getCode(),
                    'status' => $respStatus,
                    'message' => $error_message,
                    'row_id' => $device_token['row_id'],
                ];

                $message->log()->create([
                    'status' => $respStatus,
                    'message' => $error_message,
                ]);

                $response[] = $status;
            }
        }

        return response()->json([
            'meta' => [
                'code' => Response::HTTP_OK,
                'status' => 'success'
            ],
            'data' => $response,
        ]);
    }

    /**
     * @param array $data
     * @param array $custom
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    private function validateRequest($data, $custom)
    {
        $errors = [];

        if (empty($data['company_key'])) {
            $errors[] = "company key is required";
        }

        if (empty($data['row_id'])) {
            if (empty($data['filter_type'])) {
                $errors[] = "filter type is required";
            }

            if (!empty($data['filter_type']) && !in_array(strtolower($data['filter_type']), ["user_id", "email"])) {
                $errors[] = "Invalid filter type provided. It should be either 'email', 'user_id'";
            }

            if (empty($data['items']) || !is_array($data['items'])) {
                $errors[] = "items are required & they should be an array";
            }

            if (!empty($data['items'])) {
                $filter = (!empty($data['filter_type']) && ($data['filter_type'] == 'email')) ? FILTER_VALIDATE_EMAIL : FILTER_VALIDATE_INT;
                foreach ($data['items'] as $key => $item) {
                    if (!filter_var($item, $filter)) {
                        $errors[] = "Invalid items provided for filter type {$data['filter_type']}";
                        break;
                    }
                }
            }
        } else {
            if (!is_array($data['row_id'])) {
                $errors[] = "Row ID required & it should be an array";
            }
        }

        if (empty($data['message'])) {
            $errors[] = "message is required";
        }

        if (!empty($data['message']) && is_array($data['message'])) {
            $errors[] = "message should always be sent as text";
        }

        if (empty($data['type'])) {
            $errors[] = "notification type is required";
        }

        if (!empty($data['type']) && !in_array(strtolower($data['type']), ["push", "inapp", "email"])) {
            $errors[] = "Invalid notification type provided. It should be either 'Email', 'Push' or 'Inapp'";
        }

        if (!empty($data['type']) && !in_array(strtolower($data['type']), ["email"])) {
            if (empty($data['platform'])) {
                $errors[] = "platform is required";
            }

            if (!empty($data['platform']) && !in_array(strtolower($data['platform']), ["ios", "android", "universal"])) {
                $errors[] = "Invalid platform provided. Platform should be either 'Universal', 'IOS' or 'Android'";
            }

            if (!empty($data['platform']) && in_array(strtolower($data['platform']), ["ios", "android", "universal"]) &&
                !empty($data['type']) && in_array(strtolower($data['type']), ["inapp"]) &&
                !empty($custom)
            ) {
                if (empty($custom['message_type'])) {
                    $errors[] = "Message type is required.";
                }

                if (!empty($custom['message_type']) && !in_array(strtolower($custom['message_type']), ["dialogue", "banner", "full screen", "push"])) {
                    $errors[] = "Invalid message type provided. It should be 'push', 'dialogue', 'banner' or 'full screen'";
                }

                if (!empty($custom['message_type']) && in_array(strtolower($custom['message_type']), ["dialogue"])) {
                    if (empty($custom['message_position'])) {
                        $errors[] = "Message position is required for 'dialogue' type notification on " . ucfirst(strtolower($data['platform']));
                    }

                    if (!empty($custom['message_position']) && !in_array(strtolower($custom['message_position']), ["top", "middle", "bottom"])) {
                        $errors[] = "Invalid message position provided for 'dialogue' type notification on " . ucfirst(strtolower($data['platform'])) . ". It should be 'top', 'middle' or 'bottom'";
                    }
                }
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'status' => 'error'
                ],
                'errors' => $errors
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $errors;
    }

    /**
     * @param array $data
     * @param array $attribute
     * @param array $apps
     * @param \Illuminate\Database\Eloquent\Model $company
     *
     * @return array
     */
    protected function setIOSPushPayload($data, $attribute, $apps, $company)
    {
        $app = !empty($apps['ios'][$attribute['app_id']]) ? $apps['ios'][$attribute['app_id']] : [];
        if (empty($app) || (strtolower($app['platform']) != 'ios') || (strtolower($attribute['device_type']) != 'ios')) {
            return [];
        }

        if (!empty($app['is_sandbox'])) {
            $sandbox = ((bool)$app['is_sandbox'] === true) ? true : false;
        } else {
            $sandbox = (!empty($data['is_test_device']) && ((bool)$data['is_test_device'] === true)) ? true : false;
        }

        $certificate = ($sandbox === true) ? $app['ios_cert_dev'] : $app['ios_cert_live'];

        $tempMsg = $data['message'];
        foreach ($attribute as $k => $v) {
            $tempMsg = str_replace('[[$' . $k . ']]', $v, $tempMsg);
        }

        if (!empty($data['title'])) {
            $tempMsgTitle = $data['title'];
            foreach ($attribute as $k => $v) {
                $tempMsgTitle = str_replace('[[$' . $k . ']]', $v, $tempMsgTitle);
            }
        }

        if (!isset($data['bg_color'])) {
            $data['bg_color'] = config('engagement.message.push.bg_color');
        }

        if (!empty($data['bg_color'])) {
            $backgroundColor = $data['bg_color'];
        }

        if (!empty($app['logo'])) {
            $icon = \Storage::disk('s3')->url($app['logo']);
        }

        return [
            'type' => 'push',
            'value' => $attribute['device_token'],
            'company_name' => $company->name,
            'passphrase' => $app['ios_passphrase'],
            'title' => !empty($tempMsgTitle) ? $tempMsgTitle : '',
            'target' => !empty($data['target']) ? $data['target'] : '',
            'backgroundColor' => !empty($backgroundColor) ? $backgroundColor : '',
            'icon' => !empty($icon) ? $icon : '',
            'message' => $tempMsg,
            'certificate' => $certificate,
            'sandbox' => $sandbox,
            'app_id' => $attribute['app_id'],
            'user_id' => $attribute['user_id'],
            'campaign_type' => (!empty($data['type']) && in_array(strtolower($data['type']), ['push'])) ? 'push' : 'inapp',
            'is_silent' => (isset($data['is_silent']) && ((bool)$data['is_silent'] === true)) ? true : false,
            'is_hero_platform' => (isset($data['is_hero_platform']) && ((bool)$data['is_hero_platform'] === false)) ? false : true,
            'params' => (!empty($data['params']) && is_array($data['params'])) ? $data['params'] : [],
            'auto_close' => $data['auto_close']
        ];
    }

    /**
     * @param array $data
     * @param array $attribute
     * @param array $apps
     *
     * @return array
     */
    protected function setFirebasePayload($data, $attribute, $apps)
    {
        $payload = [];
        $device_apps = [];
        $custom = !empty($data['custom']) ? $data['custom'] : [];

        $isPlatformUniversal = in_array(strtolower($data['platform']), ['universal']) ? true : false;
        $isPush = (!empty($data['campaign_type']) && in_array(strtolower($data['campaign_type']), ['push'])) ? true : false;
        $isDeviceIOS = in_array(strtolower($attribute['device_type']), ['ios']) ? true : false;
        $isDeviceAndroid = in_array(strtolower($attribute['device_type']), ['android']) ? true : false;

        if ($isPush && $isPlatformUniversal && $isDeviceIOS) {
            return $payload;
        }

        if ($isPlatformUniversal) {
            if (!empty($apps['ios'][$attribute['app_id']])) {
                $device_apps[] = $apps['ios'][$attribute['app_id']];
            }

            if (!empty($apps['android'][$attribute['app_id']])) {
                $device_apps[] = $apps['android'][$attribute['app_id']];
            }
        } else {
            if (!empty($apps[strtolower($data['platform'])][$attribute['app_id']])) {
                $device_apps[] = $apps[strtolower($data['platform'])][$attribute['app_id']];
            }
        }

        if (empty($device_apps)) {
            return $payload;
        }

        $tempMsg = $data['message'];
        foreach ($attribute as $k => $v) {
            $tempMsg = str_replace('[[$' . $k . ']]', $v, $tempMsg);
        }

        $msg_notification = [
            'body' => $tempMsg
        ];

        if ($isPush) {
            if (!empty($data['title'])) {
                $tempMsgTitle = $data['title'];
                foreach ($attribute as $k => $v) {
                    $tempMsgTitle = str_replace('[[$' . $k . ']]', $v, $tempMsgTitle);
                }

                $msg_notification['title'] = $tempMsgTitle;
            }

            if (!empty($data['target'])) {
                $msg_notification['link'] = $data['target'];
            }

            if (!isset($data['bg_color'])) {
                $data['bg_color'] = config('engagement.message.push.bg_color');
            }
        }

        $msg = [
            'data' => $tempMsg,
            'position' => !empty($custom['message_position']) ? $custom['message_position'] : "",
            'message_type' => !empty($custom['message_type']) ? $custom['message_type'] : ''
        ];

        if ($isPush && $isDeviceAndroid) {
            $msg['notification'] = $msg_notification;
        }

        if ($isDeviceAndroid) {
            $msg['android'] = [
                'priority' => 'high'
            ];
        }

        foreach ($device_apps as $app) {
            if (strtolower($app['platform']) == strtolower($attribute['device_type'])) {
                $payload[] = [
                    'type' => 'inapp',
                    'campaign_type' => $isPush ? 'push' : 'inapp',
                    'value' => $attribute['fire_base_key'],
                    'apiKey' => $app['firebase_server_api_key'],
                    'message' => $msg,
                    'platform' => strtolower($attribute['device_type']),
                    'user_id' => $attribute['user_id'],
                    'is_silent' => (isset($data['is_silent']) && ((bool)$data['is_silent'] === true)) ? true : false,
                    'is_hero_platform' => (isset($data['is_hero_platform']) && ((bool)$data['is_hero_platform'] === false)) ? false : true,
                    'params' => (!empty($data['params']) && is_array($data['params'])) ? $data['params'] : [],
                    'backgroundColor' => !empty($data['bg_color']) ? $data['bg_color'] : '',
                    'icon' => !empty($app['logo']) ? \Storage::disk('s3')->url($app['logo']) : '',
                    'auto_close' => $data['auto_close']
                ];
            }
        }

        return $payload;
    }

    /**
     * @param array $data
     * @param array $attribute
     *
     * @return array
     */
    protected function setEmailPayload($data, $attribute)
    {
        $emailBody = $data['message'];

        foreach ($attribute as $k => $v) {
            $emailBody = str_replace('[[$' . $k . ']]', $v, $emailBody);
        }

        return [
            'type' => 'email',
            'value' => $attribute['email'],
            'message' => $emailBody,
            'subject' => isset($data['subject']) ? $data['subject'] : ""
        ];
    }
}
