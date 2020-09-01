<?php

namespace App\Http\Controllers;

use App\CampaignTracking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CampaignTrackingController extends Controller
{
    public function index($code)
    {
        $trackings = CampaignTracking::where([
            ['track_key', $code]
        ]);

        if ($trackings->count() > 0) {
            $tracking = $trackings->first();

            if (((bool)$tracking->viewed_at === false) && (empty($tracking->viewed_at))) {
                $tracking->viewed = true;
                $tracking->viewed_at = Carbon::now()->toDateTimeString();
                $tracking->save();
            }

            $disk = \Storage::disk('local');
            $file = 'space.png';

            if ($disk->exists($file)) {
                header('Content-Type: ' . $disk->mimeType($file));
                header('Content-Length: ' . $disk->size($file));
                echo $disk->get($file);
                exit;
            }
        }
    }

    public function tracking($id)
    {
        return view('campaign.campaignTrackingList')->with(['id' => $id]);
    }
}
