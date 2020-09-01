<?php

namespace App\Http\Controllers;

use App\CampaignTracking;
use App\Helpers\AttributeDataHelper;
use App\Notification;
use App\UserAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationInAppViewController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tracking = Notification::findOrFail($request->get('id'));

            if (empty($tracking->firebase_key)) {
                throw new \Exception("");
            }

            $payload = unserialize(stripslashes(base64_decode($tracking->payload)));

            $message = $payload['alert']['data'];

            return view('campaign.inapp.view', [
                'message' => $message,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'meta' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'status' => 'error'
                ],
                'errors' => 'No InApp view template found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function optout($trackKey)
    {
        $trkKey=base64_decode($trackKey);
        $campaignTracking = CampaignTracking::join('campaign', 'campaign.id', '=', 'campaign_tracking.campaign_id')
            ->join('users', 'users.id', '=', 'campaign.company_id')
            ->where('track_key', '=', $trkKey)
            ->first(['users.name']);
        if ($campaignTracking) {
            $userObj = array(
                'trackKey' => $trkKey,
                'name' => $campaignTracking->name
            );
            return view('unsubscribeUser')->with('userObj', $userObj);
        } else {
            abort('403', 'Access Denied');
        }
    }

    public function updateTrackingStatus(Request $request)
    {
        try {
            $campaignTracking = CampaignTracking::join('campaign', 'campaign.id', '=', 'campaign_tracking.campaign_id')
                ->join('users', 'users.id', '=', 'campaign.company_id')
                ->where('track_key', '=', $request->input('trkId'))
                ->first(['campaign_tracking.row_id','users.name']);
            if ($campaignTracking) {
                $emailNotification = UserAttribute::where('row_id', '=', $campaignTracking->row_id)->first();
                $params = array(
                    'user_id' => $emailNotification->user_id,
                    'app_name' => $emailNotification->app_name
                );
                $companyId=$emailNotification->company_id;
                $status = AttributeDataHelper::saveAttributeData($companyId, $params, 'email_notification', '0');
                return view('subscribedEmail')->with('userObj', $campaignTracking);
            } else {
                abort('403', 'Access Denied');
            }
        } catch (\Exception $exception) {
            return new JsonResponse(array(
                "Exception" => $exception->getMessage(),
                "status" => 400,
                "message" => "error"
            ));
        }
    }
}
