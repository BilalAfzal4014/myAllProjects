<?php

namespace App\Http\Resources\V1;

use App\Components\AppStatusCodes;
use App\Components\ParseResponse;
use Illuminate\Http\Request;

class UsersResource
{
    use ParseResponse;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncUser(Request $request)
    {
        return (new Users\ClientApps())->process($request);
    }

    public function bulkUserImport(Request $request)
    {
        try {
            $limit = config('engagement.api.headers.limit');

            if (count($request->users) > 0) {
                for ($val = 0; $val < count($request->users) && $val < $limit; $val++) {
                    $response[] = (new Users\ClientApps())->BulkProcess($request, $request->users[$val]);
                }
            }
            if (count($request->action_data) > 0) {
                for ($val = 0; $val < count($request->action_data) && $val < $limit; $val++) {
                    $response[] = (new Users\ClientApps())->BulkActionProcess($request, $request->action_data[$val], 'action');
                }
            }
            if (count($request->conversion_data) > 0) {
                for ($val = 0; $val < count($request->conversion_data) && $val < $limit; $val++) {
                    $response[] = (new Users\ClientApps())->BulkActionProcess($request, $request->conversion_data[$val], 'conversion');
                }
            }
            /*if (count($request->user_data) > 0) {
                for ($val = 0; $val < count($request->user_data) && $val < $limit; $val++) {
                    $response[] = (new Users\ClientApps())->BulkActionProcess($request, $request->user_data[$val], 'user');
                }
            }*/

            return $this->addResponse(
                AppStatusCodes::HTTP_OK,
                'success',
                $response,
                'data'
            );
        } catch (\Exception $exception) {
            return $this->addResponse(
                AppStatusCodes::HTTP_NOT_FOUND,
                'error',
                [$exception->getMessage()],
                'error'
            );
        }
    }
}