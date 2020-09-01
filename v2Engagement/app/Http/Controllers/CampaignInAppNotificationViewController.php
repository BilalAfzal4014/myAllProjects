<?php

namespace App\Http\Controllers;

use App\CampaignTracking;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;

class CampaignInAppNotificationViewController extends Controller
{
    public function index($code)
    {
        try {
            $tracking = CampaignTracking::where([
                ['track_key', $code]
            ])->firstOrFail();

            if (empty($tracking->firebase_key)) {
                throw new \Exception("");
            }

            $payload = unserialize(stripslashes(base64_decode($tracking->payload)));

            $message = $payload['alert']['data'];
            $campaign_tracking = config('engagement.urls.tracking') . $code;

            return view('campaign.inapp.view', [
                'message'    => $message,
                'track_link' => $campaign_tracking
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
}
