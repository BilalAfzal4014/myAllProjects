<?php

namespace App\Http\Resources\V1;

use App\Engagment\AttributeData\AttributeDataWrapper;
use App\Helpers\AttributeDataHelper;
use App\Helpers\CommonError;
use App\Helpers\CommonHelper;
use App\Libraries\tv_jwt;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationResource
{
    public function process(Request $request, $params = [])
    {
        try {
            if (empty($params['user_id'])) {
                throw new \Exception(CommonError::INVALID_USER_ID, CommonError::STATUS_CODE_LENGTH_REQUIRED);
            }

            if (empty($params['type']) || (!empty($params['type']) && !in_array($params['type'], AttributeDataHelper::notificationTypes()))) {
                throw new \Exception(CommonError::NOTIFICATION_TYPE_INVALID, CommonError::STATUS_CODE_LENGTH_REQUIRED);
            }

            if (!isset($params['status'])) {
                throw new \Exception(CommonError::NOTIFICATION_ENABLED, CommonError::STATUS_CODE_LENGTH_REQUIRED);
            }

            $headers = CommonHelper::changeHeader($request->headers->all());

            $jwt = new tv_jwt();

            $data = CommonHelper::getUserFromKey($jwt, $request);
            $user = $data['user'];

            $notification_type = $params['type'];
            $enabled = $params['status'];

            $field = in_array($notification_type, ['email']) ? 'email_notification' : 'enable_notification';
            $value = (isset($enabled) && ((bool)$enabled === false)) ? 0 : 1;

            $params['app_name'] = $headers['app_name'][0];
            unset($params['type']);
            unset($params['status']);

            $status = AttributeDataHelper::saveAttributeData($user->id, $params, $field, $value);
            if ($status === true) {
                return response()->json([
                    'meta' => [
                        'code'  => Response::HTTP_OK,
                        'status' => 'success'
                    ],
                    'data' => "notification preference updated successfully!"
                ], Response::HTTP_OK);
            } else {
                throw new \Exception(CommonError::NOTIFICATION_PREFERENCE_UPDATE, CommonError::STATUS_CODE_UNPROCESSABLE_ENTITY);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'meta' => [
                    'code'  => $exception->getCode(),
                    'status' => 'error'
                ],
                'errors' => $exception->getMessage()
            ], $exception->getCode());
        }
    }
}