<?php

namespace App\Http\Controllers;

use App\Cache\CampaignTrackingCache;
use App\Cache\CampaignTranslationCache;
use App\Campaign;
use App\Language;
use App\LinkTrackings;
use App\CampaignTracking;
use App\Notification;
use App\Translation;
use Illuminate\Http\Request;


class ViewInappController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return redirect('/horizon');
        //return view('home');
    }

    public function trackLink(Request $request)
    {
        //http://dev.engagiv.backend.local/trackLink?enc=bmV3c2ZlZWQvMS8yL2h0dHBzOi8vd3d3Lmdvb2dsZS5jb20=
        $encodedLink = base64_decode($request->input('enc'));
        $encodedArr = explode("/", $encodedLink);

        $counter = 0;
        $i = 0;
        for ($i; $i < strlen($encodedLink); $i++) {
            if ($counter == 3) {
                break;
            }

            if ($encodedLink[$i] == "/") {
                $counter++;
            }
        }

        $tracking = new LinkTrackings();
        $tracking->rec_type = $encodedArr[0];
        $tracking->rec_id = $encodedArr[1];
        $tracking->row_id = $encodedArr[2];
        $tracking->actual_url = substr($encodedLink, $i);
        $tracking->created_date = date("Y-m-d h:i:s");
        $tracking->ip_address = $request->ip();
        $tracking->user_agent = $request->header('user-agent');
        $tracking->device_type = $this->findDeviceType($request->header('user-agent'));
        $tracking->viewed = 1;
        $tracking->save();
        return redirect()->away($tracking->actual_url);

    }

    public function findDeviceType($userAgent)
    {
        $device = 'web';
        if (strpos($userAgent, 'iPad') || strpos($userAgent, 'iPhone')) {
            $device = 'ios';
        } elseif (strpos($userAgent, 'Android')) {
            $device = 'android';
        }
        return $device;
    }

    public function viewInappURL($track_key=null)
    {
        if($track_key){

            // getting payload from tracking
            $result = CampaignTracking::where('track_key', $track_key)->first();
            if ($result) {

                // getting device type
                $platform = (isset($result->device_type)) ? $result->device_type : "";
                $campaign_id = (isset($result->campaign_id)) ? $result->campaign_id : "";
                $variant_id = (isset($result->variant_id)) ? $result->variant_id : "";

                $payload = (isset($result->payload)) ? $result->payload : "";
                $payload = \GuzzleHttp\json_decode($payload);

                $language_code = (isset($payload->data->language)) ? $payload->data->language : 'en';
                $language = Language::where('code', $language_code)->first();
                $language_id = $language->id;

                // get contents from cache and
                $_template = CampaignTranslationCache::getCampaignTranslationCache('', $campaign_id, $language_id, $variant_id);

                //$data['html_content'] = (isset($payload->data->message)) ? $payload->data->message : "";

                // render html to page
                $data['html_content'] = (isset($_template->templateInfo->template)) ? $_template->templateInfo->template : "";

                return view('inapp', compact('data'));
            }
            else{
                print "No data found.";
            }

        }else{
            echo "Track key not found";
        }
    }

    public function viewInappNotificationURL($notification_id=null)
    {
        if($notification_id){

            // getting payload from notification
            $result = Notification::find($notification_id);
            if (!empty($result)) {

                // getting platform
                $data['html_content'] = (isset($result->message)) ? $result->message : "";

                // prepare and parse payload
                //$payload = (isset($result->payload)) ? $result->payload : "";
                //$payload = \GuzzleHttp\json_decode($payload);

                //$data['html_content'] = (isset($payload->data->message)) ? $payload->data->message : "";

                // render html to page
                //$data['html_content'] = html_entity_decode($data['html_content']);

                return view('inapp', compact('data'));
            }
            else{
                echo "No data found.";
            }

        }else{
            echo "Track key not found";
        }
    }
}
