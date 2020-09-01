<?php

namespace App\Http\Controllers;

use App\AttributeData;
use App\Campaign;
use App\CampaignQueue;
use App\CampaignTemplate;
use App\CampaignTracking;
use App\CampaignTrackingLogs;
use App\CampaignTypes;
use App\Components\RunExternalCommand;
use App\Queue;
use App\User;
use App\UserAttribute;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Config;

class CampaignTemplateController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('superadmin');
    }

    public function index()
    {
        $CampaignTemplate = CampaignTemplate::all();
        return view('campaignTemplate.index')->with(['CampaignTemplate' => $CampaignTemplate]);
    }

    public function listing($type)
    {

        $CampaignTemplate = \App\CampaignTemplate::where('type', '=', $type)->get();
        $arrayTemp = [];
        foreach ($CampaignTemplate as $CampaignTemplates) {
            array_push($arrayTemp, [
                $CampaignTemplates->id,
                $CampaignTemplates->title,
                $CampaignTemplates->type,
                $CampaignTemplates->created_at->format('F d, Y h:ia'),
                view('campaignTemplate.campaignTemplateAjax.actionCol', ["CampaignTemplates" => $CampaignTemplates])->render()
            ]);
        }
        $arrayToReturn['data'] = $arrayTemp;
        return new Response(json_encode($arrayToReturn));

    }

    public function campaignTemplatesCreate()
    {
        $newsTemplate = array(
            'id' => '',
            'title' => '',
            'content' => '',
            'type' => '',
            'thumbnail' => '',
        );
        $type = array(
            '' => 'Select Type',
            'PUSH' => 'PUSH',
            'EMAIL' => 'EMAIL',
            'BANNER' => 'BANNER',
            'DIALOGUE' => 'DIALOGUE',
            'FULL SCREEN' => 'FULL SCREEN'
        );

        return view('campaignTemplate.create')->with(['newsTemplate' => (object)$newsTemplate, 'type' => $type]);
    }

    public function saveCampaignTemplate(Request $request)
    {
        try {
            $allinput = $request->all();
            $id = $request->input('userid');
            $title = $request->input('title');
            $type = $request->input('type');
            $content = $request->input('content');
            $image = array_get($allinput, 'image');
            if (!empty($image)) {
                $filepath = $this->imageUpload($image);

            } else {
                $filepath = '';
            }
            if ($id != '') {
                $newsTemplate = \App\CampaignTemplate::findOrFail($id);
                if (!empty($filepath)) {
                    $filepath = $newsTemplate->thumbNail;
                }
                $update = \App\CampaignTemplate::where('id', $id)->update([
                    'title' => $title,
                    'content' => $content,
                    'type' => $type,
                    'thumbNail' => $filepath
                ]);
                if ($update) {
                    return redirect('/campaignTemplates')->with(['flash_message' => 'Campaign Update']);
                } else {
                    return redirect('/campaignTemplates/edit/' . $id)->with(['flash_message' => 'Campaign Failed']);
                }

            } else {
                // dd($filepath);
                $campaignModel = new \App\CampaignTemplate();
                $campaignTitle = \App\CampaignTemplate::where('title', $title)->get();
                if (count($campaignTitle) == 0) {
                    $campaignModel->company_id = '1';
                    $campaignModel->title = $title;
                    $campaignModel->content = $content;
                    $campaignModel->type = $type;
                    $campaignModel->thumbNail = $filepath;
                    $result = $campaignModel->save();
                    if ($result) {
                        return redirect('/campaignTemplates')->with(['flash_message' => 'Campaign Template Added']);
                    } else {
                        return redirect('/campaignTemplatesCreate')->with(['flash_message' => 'Failed Campaign']);
                    }
                } else {
                    return redirect('/campaignTemplatesCreate')->with(['flash_message' => 'Campaign Template Title ALready Exist']);
                }
            }
        } catch (\Exception $exception) {
            return redirect('/campaignTemplatesCreate')->with(['flash_message' => $exception]);

        }

    }

    public function imageUpload($thumbnail)
    {
        $destinationPath = 'assets/images';
        $extension = $thumbnail->getClientOriginalExtension();
        $fileName = rand(11111, 99999) . '.' . $extension;
        $success = $thumbnail->move($destinationPath, $fileName);
        $imagpath = $destinationPath . '/' . $fileName;
        return $imagpath;
    }

    public function edit($id)
    {
        $newsTemplate = \App\CampaignTemplate::findOrFail($id);
        $type = array(
            '' => 'Select Type',
            'PUSH' => 'PUSH',
            'EMAIL' => 'EMAIL',
            'BANNER' => 'BANNER',
            'DIALOGUE' => 'DIALOGUE',
            'FULL SCREEN' => 'FULL SCREEN'
        );
        return view('campaignTemplate.create')->with(['newsTemplate' => $newsTemplate, 'type' => $type]);
    }

    public function campaignQueue()
    {
        $users = User::all();
//        $campaignQueue = CampaignQueue::join('campaign','campaign.id','=','campaign_queues.campaign_id')
//            ->join('users','users.id','=','campaign.company_id')
//            ->orderBy('campaign_queues.id', 'desc')->get(['campaign_queues.*','users.name']);
        return view('campaignTemplate.campaignQueue')->with(['users' => $users]);
    }

    public function campaignQueueFilter(Request $request)
    {
        $companyId = Auth::user()->id;
        $columns = array(
            0 => 'id',
            1 => 'campaign_id',
            2 => 'name',
            3 => 'status',
            4 => 'details',
            5 => 'error_message',
            6 => 'created_at'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter;
        $filterType = $request->filterType;
        $myQuery = CampaignQueue::join('campaign', 'campaign.id', '=', 'campaign_queues.campaign_id')
            ->join('users', 'users.id', '=', 'campaign.company_id');
        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery->where(function ($query) use ($search) {
                $query->orWhere('campaign_queues.id', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_queues.status', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_queues.campaign_id', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_queues.details', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_queues.error_message', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_queues.created_at', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_queues.id', 'LIKE', "%{$search}%");
                $query->orWhere('users.name', 'LIKE', "%{$search}%");
            });
        }
        switch ($filterType) {
            case 'app_name':
                $myQuery->where('users.id', $filter);
                break;
        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get(['campaign_queues.id as id', 'campaign_queues.campaign_id as campaign_id', 'users.name as name', 'campaign_queues.status as status', 'campaign_queues.details as details', 'campaign_queues.error_message as error_message', 'campaign_queues.created_at as created_at']);
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign Queue'
        ]);
    }

    public function updateCampaignStatus($id)
    {
        $contents = [];

        try {
            $queue = CampaignQueue::findOrFail($id);
            $data = \GuzzleHttp\json_decode($queue->details, true);
            $campaign = Campaign::findOrFail($data['campaignId']);

            if ($queue->status === 'Complete') {
                $queue->status = 'Available';
                $queue->save();

                $contents['title'] = '<i class="fa fa-play"></i> Execute';
                $contents['status'] = 'Available';
                $contents['log'] = "Status is set to 'Available'";
            } else {
                //\Artisan::call('target:users', ['id' => $queue->id]);
                RunExternalCommand::run("/usr/bin/php " . base_path() . "/artisan target:users {$queue->id} --queue=campaign");
                $logs = [];
                $tracks = $campaign->tracks;
                if ($tracks->count() > 0) {
                    foreach ($tracks as $track) {
                        $track = $track->fresh();
                        if ($track->files->count() > 0) {
                            $files = $track->files;
                            foreach ($files as $file) {
                                $logs[] = $file->log;
                            }
                        }
                    }
                }
                if (empty($logs)) {
                    $logs[] = "Queue job has been executed successfully.";
                }
                $contents['title'] = ($queue->fresh()->status === 'Complete') ?
                    '<i class="fa fa-bookmark"></i> Set to Available' :
                    '<i class="fa fa-play"></i> Execute';
                $contents['log'] = implode("<br /><br />", $logs);
            }
        } catch (\Exception $exception) {
            $contents['title'] = '<i class="fa fa-play"></i> Execute';
            $contents['log'] = $exception->getMessage();
        }

        if (empty($contents['status'])) {
            $contents['status'] = $queue->fresh()->status;
        }

        return response()->json($contents);
    }

    public function deleteCampaignQueue($id)
    {
        try {
            $queue = CampaignQueue::findOrFail($id);
            $queue->delete();
            $response = [
                'status' => 'success',
                'data' => 'Item has been removed from queue'
            ];
        } catch (\Exception $exception) {
            $response = [
                'status' => 'danger',
                'data' => "Unable to remove item from queue table. Following error occurred!<br />" .
                    $exception->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function filter($id)
    {
        $campaignQueue = CampaignQueue::join('campaign', 'campaign.id', '=', 'campaign_queues.campaign_id')
            ->join('users', 'users.id', '=', 'campaign.company_id')->where('users.id', $id)->get(['campaign_queues.*', 'users.name']);
        $arrayTemp = [];
        foreach ($campaignQueue as $user) {
            array_push($arrayTemp, [
                $user->id,
                $user->campaign_id,
                $user->name,
                $user->status,
                $user->details,
                $user->error_message,
                $user->created_at->format('F d, Y h:ia'),
                view('CampaignTemplate.campaignTemplateAjax.campaignQueueAjax', ["user" => $user])->render()
            ]);
        }
        $arrayToReturn['data'] = $arrayTemp;
        return new Response(json_encode($arrayToReturn));
    }

    public function queueJobFilter(Request $request)
    {
        $companyId = Auth::user()->id;
        $columns = array(
            0 => 'id',
            1 => 'method',
            2 => 'data',
            3 => 'status',
            4 => 'error_message',
            5 => 'created_at'

        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter;
        $filterType = $request->filterType;
        $myQuery = DB::table('queue');
        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery->where('id', 'LIKE', "%{$search}%")
                ->orWhere('method', 'LIKE', "%{$search}%")
                ->orWhere('data', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('error_message', 'LIKE', "%{$search}%")
                ->orWhere('created_at', 'LIKE', "%{$search}%");
        }
//        switch ($filterType) {
//            case 'app_name':
//                $myQuery->where('users.id', $filter);
//                break;
//        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get(['queue.id as id', 'queue.data', 'queue.method as method', 'queue.data as data', 'queue.status as status', 'queue.error_message as error_message', 'queue.created_at as created_at']);
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
//        dd($galleryListing);
        foreach ($galleryListing as $key => $row) {
            $result = json_decode($row->data, true);
//            dd($result['campaignId']);
            $galleryListing[$key]->campaignId = $result['campaignId'];
        }
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign Queue'
        ]);
    }

    public function tracking($id)
    {


        $trackingList = CampaignTracking::leftjoin('campaign_tracking_logs', 'campaign_tracking_logs.campaign_tracking_id', '=', 'campaign_tracking.id')
            ->leftjoin('user_campaign', function ($join) {
                $join->on('user_campaign.track_key', '=', 'campaign_tracking.track_key');
                $join->where('user_campaign.rec_type', '=', "conversion");
            })->leftjoin('user_attribute', 'user_attribute.row_id', '=', 'campaign_tracking.row_id')
            ->leftjoin('lookup', 'lookup.id', '=', 'user_campaign.event_id')
            ->where('campaign_tracking.campaign_id', $id)->
            get([
                'campaign_tracking.id as id',
                'campaign_tracking.campaign_id as campaign_id',
                'campaign_tracking.track_key as track_key',
                'campaign_tracking.row_id as row_id',
                'campaign_tracking.email as email',
                'campaign_tracking.firebase_key as firebase_key',
                'campaign_tracking.device_key as device_key',
                'campaign_tracking.job as jobstatus',
                'campaign_tracking.sent_at as sent_at',
                'campaign_tracking.status as completestatus',
                'campaign_tracking.viewed_at as viewed_at',
                'campaign_tracking_logs.message as message',
                'user_campaign.id as uid',
                'user_campaign.event_value as event_value',
                'user_campaign.build as build',
                'user_campaign.version as version',
                'user_campaign.event_id as event_id',
                'user_campaign.device_type as device_type',
                'user_campaign.created_at as created_at',
                'user_attribute.company_id as company_id',
                'campaign_tracking.created_at as datetime',
                'lookup.code as event_name',
                'user_attribute.email as UserEmail'
            ]);
        //dd($trackingList);
        return view('campaign.campaignTrackingList')->with(['trackingList' => $trackingList, 'id' => $id]);
    }

    public function campaignTracklistingFilter(Request $request, $id)
    {
        $companyId = Auth::user()->id;
        $columns = array(
            0 => 'Trackkey',
            1 => 'Row_id',
            2 => 'sent_at',
            3 => 'completestatus',
            4 => 'viewed_at',
            5 => 'message'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter;
        $filterType = $request->filterType;
        $myQuery =CampaignTracking::leftjoin('campaign_tracking_logs', 'campaign_tracking_logs.campaign_tracking_id', '=', 'campaign_tracking.id')
            ->leftjoin('user_campaign', function ($join) {
                $join->on('user_campaign.track_key', '=', 'campaign_tracking.track_key');
                $join->where('user_campaign.rec_type', '=', "conversion");
            })->leftjoin('user_attribute', 'user_attribute.row_id', '=', 'campaign_tracking.row_id')
            ->leftjoin('lookup', 'lookup.id', '=', 'user_campaign.event_id')
            ->where('campaign_tracking.campaign_id', $id);
        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery->where('campaign_tracking.track_key', 'LIKE', "%{$search}%")
                ->orWhere('campaign_tracking.row_id', 'LIKE', "%{$search}%")
                ->orWhere('campaign_tracking.sent_at', 'LIKE', "%{$search}%")
                ->orWhere('campaign_tracking.status', 'LIKE', "%{$search}%")
                ->orWhere('campaign_tracking.viewed_at', 'LIKE', "%{$search}%")
                ->orWhere('campaign_tracking_logs.message', 'LIKE', "%{$search}%");
        }
        switch ($filterType) {
            case 'app_name':
                $myQuery->where('campaign_tracking.status', $filter);
                break;
        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get([
                'campaign_tracking.id as id' ,
                'campaign_tracking.campaign_id as campaign_id',
                'campaign_tracking.track_key as Trackkey',
                'campaign_tracking.row_id as Row_id',
                'campaign_tracking.email as email',
                'campaign_tracking.firebase_key as firebase_key',
                'campaign_tracking.device_key as device_key',
                'campaign_tracking.sent_at as sent_at',
                'campaign_tracking.status as completestatus',
                'campaign_tracking.viewed_at as viewed_at',
                'campaign_tracking_logs.message as message',
                'user_campaign.event_value as event_value',
                'user_campaign.build as build',
                'user_campaign.version as version',
                'user_campaign.id as uid',
                'user_campaign.company_id as company_id',
                'user_campaign.event_id as event_id',
                'user_campaign.device_type as device_type',
                'user_campaign.created_at as created_at',
                'lookup.code as event_name as event_name',
                'user_attribute.company_id as company_id'
            ]);
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign Queue'
        ]);
    }

    public function campaignResendNotification($campaignId,$id,$companyId)
    {

        $getCompany= User::where('id','=',$companyId)->get();
        if(count($getCompany)>0)
        {
            $companyKey = $getCompany['0']->company_key;
            $email = $getCompany['0']->email;
            $campaign = Campaign::find($campaignId);
            if (!empty($campaign->id)) {
                $campaign_type = $campaign->campaign_type;
                $campaignTracking = CampaignTracking::where('campaign_tracking.id', $id)
                    ->leftjoin('campaign_tracking_logs', 'campaign_tracking_logs.campaign_tracking_id', '=', 'campaign_tracking.id')->first();
                //   dd($campaignTracking);
                $rowId = $campaignTracking->row_id;
                $payload = unserialize(stripslashes(base64_decode($campaignTracking->payload)));
                $type = CampaignTypes::TYPE_EMAIL;
                if ($campaign_type->isPush()) {
                    $type = CampaignTypes::TYPE_PUSH;
                    if ($campaign->isPlatformIOS()) {
                        if (!empty($payload['certificate']) && strpos($payload['certificate'], 'development')) {
                            $sandbox = true;
                        }
                    }
                }
                if ($campaign_type->isInapp()) {
                    $type = CampaignTypes::TYPE_INAPP;
                }

                if (!empty($campaignTracking->firebase_key)) {
                    $message = $payload['alert']['data'];
                    $attributeData = UserAttribute::where('row_id', '=', $rowId)->where('company_id', '=', $companyId)->get(['device_type']);
                    if (count($attributeData) > 0) {
                        $value = $attributeData['0']->device_type;
                        if (in_array(strtolower($value), ['ios', 'iphone'])) {
                            $platform = 'ios';
                        } else {
                            $platform = 'android';
                        }
                    } else {
                        $platform = 'android';
                    }
                    $post = array(
                        'company_key' => $companyKey,
                        'row_id' => [$rowId],
                        "message" => $message,
                        "platform" => $platform,
                        "type" => $type
                    );
                } else if (!empty($campaignTracking->device_key)) {
                    $message = $payload['message'];
                    $platform = 'ios';
                    $post = array(
                        'company_key' => $companyKey,
                        'row_id' => [$rowId],
                        "message" => $message,
                        "platform" => $platform,
                        "type" => $type
                    );
                } else {
                    $message = $payload['message'];
                    $post = array(
                        'company_key' => $companyKey,
                        'row_id' => [$rowId],
                        "message" => $message,
                        "type" => $type
                    );
                }
                //dd($post);
                if (!empty($sandbox) && ($sandbox === true)) {
                    $post['is_test_device'] = true;
                }
                $ch = curl_init(config::get('app.url') . '/api/v1/message/send');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
                $res = curl_exec($ch);
                //echo $res;
                $errors = curl_error($ch);
                $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                $result = json_decode($res, true);
                //dd($result);
                if ($result['meta']['code'] == '200') {
                    if ($result['data']['0']['code'] == '200') {
                        CampaignTracking::where('campaign_tracking.id', $id)->update([
                            'status' => 'completed',
                            'sent'=>'1'
                        ]);
                        $logs = CampaignTrackingLogs::where('campaign_tracking_id', $id)->first();
                        $logs->message = $result['data']['0']['message'];
                        $logs->save();
                        $resp = $this->successResponse($result['data']['0']['message'], [], $result);
                    } else {
                        $logs = CampaignTrackingLogs::where('campaign_tracking_id', $id)->first();
                        $logs->message = $result['data']['0']['message'];
                        $logs->save();
                        $resp = $this->multipleFailedResponse('multipleFailedResponse', $result['data']);
                    }
                } else {
                    $logs = CampaignTrackingLogs::where('campaign_tracking_id', $id)->first();
                    $logs->message = $result['message'];
                    $logs->save();
                    $resp = $this->failedResponse($result['errors']['0']);
                }
                //dd($response->getBody()->getContents());

            } else {
                $resp = $this->failedResponse('No Campaign Found');
            }
        }else{
            $resp = $this->failedResponse('Company not found');
        }

        return $resp;
    }
    public function multipleFailedResponse($message, $data)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 402,
            'error' => true,
            'message' => $message,
            'data' => $data
        ));
    }

    public function failedResponse($message)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 400,
            'error' => true,
            'message' => $message
        ));
    }

    public function successResponse($message, $data, $curlResponse)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 200,
            'error' => false,
            'message' => $message,
            'data' => $data,
            'curlResponse' => $curlResponse
        ));
    }
}
