<?php

namespace App\Engagment\Campaign;

use App\Attribute;
use App\Helpers\CommonHelper;
use App\Lookup;
use App\Segment;
use App\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Campaign;
use App\CampaignTemplate;
use App\CampaignAttributes;
use App\CampaignSegments;
use App\CampaignSchedule;
use App\LinkTracking;
use DateTime;
use App\AttributeData;
use App\CampaignAction;
use App\Apps;
use App\CampaignApp;
use App\Components\CompanyAttributeData;
use App\CampaignCapRule;

class CampaignHandler
{
    protected $campaignModal;
    protected $campaignTemplateModal;
    protected $campaignAttributesModal;
    protected $campaignSegmentsModal;
    protected $campaignActionModal;
    protected $campaignScheduleModal;
    protected $linkTrackingModal;
    protected $campaignAppModal;
    private $user_id;

    public function __construct(Campaign $campaign, CampaignTemplate $campaignTemplate, CampaignAttributes $campaignAttributes, CampaignSegments $campaignSegments, CampaignSchedule $campaignSchedule, LinkTracking $linkTracking, AttributeData $attributeData, CampaignAction $campaignAction, CampaignApp $campaignApp)
    {
        $this->campaignModal = $campaign;
        $this->campaignTemplateModal = $campaignTemplate;
        $this->campaignAttributesModal = $campaignAttributes;
        $this->campaignSegmentsModal = $campaignSegments;
        $this->campaignScheduleModal = $campaignSchedule;
        $this->campaignActionModal = $campaignAction;
        $this->linkTrackingModal = $linkTracking;
        $this->attributeDataModal = $attributeData;
        $this->campaignAppModal = $campaignApp;
        $this->steps = ['GENERAL', 'COMPOSE', 'DELIVERY', 'TARGET', 'CONVERSION'];
    }

    public function submitStep1($requestBody)
    {
        $nameExist = DB::table('campaign')
            ->where('company_id', $requestBody['obj']['companyId'])
            ->where('name', $requestBody['obj']['campaignTitle']);
        if ($requestBody['obj']['campaignId'] != '') {
            $nameExist->where('id', '<>', $requestBody['obj']['campaignId']);
        }
        if ($nameExist->first()) {
            return false;
        }

        if ($requestBody['obj']['campaignId'] == '') {
            $this->campaignModal->code = $unique = substr(base64_encode(md5(mt_rand())), 0, 4) . '-' . substr(base64_encode(md5(mt_rand())), 0, 8) . '-' . substr(base64_encode(md5(mt_rand())), 0, 12);
            $this->campaignModal->step = $this->steps[$requestBody['obj']['step'] - 1];
            $this->campaignModal->created_by = Auth::user()->id;
        } else {
            $this->campaignModal = Campaign::find($requestBody['obj']['campaignId']);
        }
        $this->campaignModal->company_id = $requestBody['obj']['companyId'];
        $this->campaignModal->name = $requestBody['obj']['campaignTitle'];
        $this->campaignModal->tags = $requestBody['obj']['tagsInput'];
        //$this->campaignModal->type_id = $requestBody['obj']['activeClassId'];
        $this->campaignModal->type_id = $requestBody['obj']['campaignType'];

        if ($this->campaignModal->type_id == 1) {
            $this->campaignModal->subject = $requestBody['obj']['subject'];
            $this->campaignModal->from_email = $requestBody['obj']['email'];
            $this->campaignModal->from_name = $requestBody['obj']['name'];
            if (isset($requestBody['obj']['htmlContent']))
                //$this->campaignModal->en = $requestBody['obj']['htmlContent'];
                $this->campaignModal->en = base64_decode($requestBody['obj']['htmlContent']);
        }


        //$this->campaignModal->action_target = $requestBody['obj']['campaignType'];

        $this->campaignModal->updated_by = Auth::user()->id;
        $this->campaignModal->save();

        return $this->campaignModal->id;
    }

    public function submitStep2($requestBody)
    {
        $this->campaignModal = Campaign::find($requestBody['obj']['campaignId']);

        if ($requestBody['obj']['step'] > (array_search($this->campaignModal->step, $this->steps) + 1)) {
            $this->campaignModal->step = $this->steps[$requestBody['obj']['step'] - 1];
        }

        if ($this->campaignModal->type_id == 1) {

            /**
             * @var  $disk FilesystemAdapter
             */

            /*$disk = \Storage::disk("s3");
            $path = 'company_' . $requestBody['obj']['companyId'] . '/campaign_' . $requestBody['obj']['campaignId'];
            if (!$disk->exists($path)) {
                $disk->makeDirectory($path);
            }*/

            foreach ($requestBody['obj']['editorContent'] as $contentObj) {

                switch ($contentObj['lang']) {
                    case 'en':
                        $this->campaignModal->en = base64_encode($this->anchorTagOperation(base64_decode($contentObj['content']), 'encrypt', $this->campaignModal->id));
                        /*if ($disk->exists($path . '/en.html')) {
                            $disk->delete($path . '/en.html');
                        }
                        $disk->put($path . '/en.html', $this->campaignModal->en, 'public');*/
                        break;
                    case 'ar':
                        $this->campaignModal->ar = base64_encode($this->anchorTagOperation(base64_decode($contentObj['content']), 'encrypt', $this->campaignModal->id));
                        /*if ($disk->exists($path . '/ar.html')) {
                            $disk->delete($path . '/ar.html');
                        }
                        $disk->put($path . '/ar.html', $this->campaignModal->ar, 'public');*/
                        break;
                    case 'ca':
                        $this->campaignModal->ca = base64_encode($this->anchorTagOperation(base64_decode($contentObj['content']), 'encrypt', $this->campaignModal->id));
                        /*if ($disk->exists($path . '/ca.html')) {
                            $disk->delete($path . '/ca.html');
                        }
                        $disk->put($path . '/ca.html', $this->campaignModal->ca, 'public');*/
                        break;
                    default:
                        $this->campaignModal->rs = base64_encode($this->anchorTagOperation(base64_decode($contentObj['content']), 'encrypt', $this->campaignModal->id));
                    /*if ($disk->exists($path . '/rs.html')) {
                        $disk->delete($path . '/rs.html');
                    }
                    $disk->put($path . '/rs.html', $this->campaignModal->rs, 'public');*/
                }
            }
        } else if ($this->campaignModal->type_id == 2) {
            foreach ($requestBody['obj']['templatesInfo'] as $contentObj) {

                $contentObj['templateInfo']['template'] = base64_decode($contentObj['templateInfo']['template']);

                switch ($contentObj['language']) {
                    case 'en':
                        //$contentObj['templateInfo']['template'] = $this->anchorTagOperation(base64_decode($contentObj['templateInfo']['template']), 'encrypt', $this->campaignModal->id);
                        $this->campaignModal->en = json_encode($contentObj['templateInfo']);
                        break;
                    case 'ar':
                        //$contentObj['templateInfo']['template'] = $this->anchorTagOperation(base64_decode($contentObj['templateInfo']['template']), 'encrypt', $this->campaignModal->id);
                        $this->campaignModal->ar = json_encode($contentObj['templateInfo']);
                        break;
                    case 'ca':
                        //$contentObj['templateInfo']['template'] = $this->anchorTagOperation(base64_decode($contentObj['templateInfo']['template']), 'encrypt', $this->campaignModal->id);
                        $this->campaignModal->ca = json_encode($contentObj['templateInfo']);
                        break;
                    default:
                        //$contentObj['templateInfo']['template'] = $this->anchorTagOperation(base64_decode($contentObj['templateInfo']['template']), 'encrypt', $this->campaignModal->id);
                        $this->campaignModal->rs = json_encode($contentObj['templateInfo']);
                }
            }
            $this->campaignModal->platform_id = Lookup::where('name', $requestBody['obj']['plateform'])->first()->id;
        } else {
            $this->campaignModal->platform_id = Lookup::where('name', $requestBody['obj']['plateform'])->first()->id;
            $this->campaignModal->message_type_id = Lookup::where('name', $requestBody['obj']['messageType'])->first()->id;
            $this->campaignModal->orientation_id = Lookup::where('name', $requestBody['obj']['orientation'])->first()->id;

            if ($requestBody['obj']['position'] != -1) {
                $this->campaignModal->position_id = Lookup::where('name', $requestBody['obj']['position'])->first()->id;
            }

            foreach ($requestBody['obj']['templatesInfo'] as $contentObj) {
                $contentObj['templateInfo']['action1']['encryptUrl'] = $this->encrypt($contentObj['templateInfo']['action1']['value'], $requestBody['obj']['campaignId']);
                $contentObj['templateInfo']['action2']['encryptUrl'] = $this->encrypt($contentObj['templateInfo']['action2']['value'], $requestBody['obj']['campaignId']);

                /*if (strtolower($contentObj['templateInfo']['action1']['type']) != 'deep link') {
                    $contentObj['templateInfo']['template'] = $this->anchorTagOperation(base64_decode($contentObj['templateInfo']['template']), 'encrypt', $this->campaignModal->id);
                }*/


                $contentObj['templateInfo']['template'] = base64_decode($contentObj['templateInfo']['template']);
                switch ($contentObj['language']) {
                    case 'en':
                        $this->campaignModal->en = json_encode($contentObj['templateInfo']);
                        break;
                    case 'ar':
                        $this->campaignModal->ar = json_encode($contentObj['templateInfo']);
                        break;
                    case 'ca':
                        $this->campaignModal->ca = json_encode($contentObj['templateInfo']);
                        break;
                    default:
                        $this->campaignModal->rs = json_encode($contentObj['templateInfo']);
                }
            }

        }

        $this->campaignModal->save();

        return [
            "code" => $this->campaignModal->code,
            "cappingRuleControl" => DB::table("campaign_types")
                ->join("campaign_cap_rules", "campaign_types.name", "=", "campaign_cap_rules.campaign_type")
                ->where("campaign_cap_rules.company_id", $this->campaignModal->company_id)
                ->where("campaign_types.id", $this->campaignModal->type_id)
                ->first()
        ];
    }

    public function submitStep3($requestBody)
    {
        $this->campaignModal = Campaign::find($requestBody['obj']['campaignId']);

        if ($requestBody['obj']['step'] > (array_search($this->campaignModal->step, $this->steps) + 1)) {
            $this->campaignModal->step = $this->steps[$requestBody['obj']['step'] - 1];
        } else {
            if ($requestBody['obj']['deliveryType'] == 'action') {
                CampaignAction::where('campaign_id', $requestBody['obj']['campaignId'])
                    ->where("action_type", "trigger")
                    ->delete();
            }
        }

        $this->campaignModal->delivery_type = $requestBody['obj']['deliveryType'];
        $this->campaignModal->start_time = $requestBody['obj']['startDate'];

        if ($requestBody['obj']['endDate'] == " 00:00:00") {
            $this->campaignModal->end_time = null;
        } else {
            $this->campaignModal->end_time = $requestBody['obj']['endDate'];
        }

        if ($requestBody['obj']['deliveryType'] == 'action') {

            foreach ($requestBody['obj']['actions'] as $action) {
                $this->campaignActionModal = new CampaignAction();
                $this->campaignActionModal->campaign_id = $requestBody['obj']['campaignId'];
                $this->campaignActionModal->action_id = Lookup::where('name', $action['actionType'])->first()->id;
                $this->campaignActionModal->value = $action['actionValue'];
                $this->campaignActionModal->action_type = 'trigger';
                $this->campaignActionModal->save();
            }
            $this->campaignModal->action_trigger_delay_value = $requestBody['obj']['actionTriggerDelivery']['input'];
            $this->campaignModal->action_trigger_delay_unit = $requestBody['obj']['actionTriggerDelivery']['value'];

        } else {

            $this->campaignModal->schedule_type = $requestBody['obj']['campaignRepitition'];

            CampaignSchedule::where('campaign_id', $requestBody['obj']['campaignId'])->delete();
            if ($this->campaignModal->schedule_type == 'WEEEKLY') {
                foreach ($requestBody['obj']['campaignAll'] as $day) {
                    $this->campaignScheduleModal = new CampaignSchedule();
                    $this->campaignScheduleModal->campaign_id = $requestBody['obj']['campaignId'];
                    $this->campaignScheduleModal->day = $day;
                    $this->campaignScheduleModal->save();
                }
            }

        }

        $this->campaignModal->delivery_control_delay_value = $requestBody['obj']['delivery']['input'];
        $this->campaignModal->delivery_control_delay_unit = $requestBody['obj']['delivery']['value'];
        $this->campaignModal->campaign_priority = $requestBody['obj']['delivery']['priority'];

        if ($requestBody['obj']['delivery']['isChecked'] == "true") {
            $this->campaignModal->enable_delivery_control = 1;
        } else {
            $this->campaignModal->enable_delivery_control = 0;
        }

        $this->campaignModal->updated_by = Auth::user()->id;


        if ($requestBody['obj']['enableCapping'] == "true") {
            $this->campaignModal->enable_capping = 1;
        } else {
            $this->campaignModal->enable_capping = 0;
        }

        $this->campaignModal->save();

        return $this->campaignModal->id;
    }

    public function submitStep4($requestBody)
    {
        $mode = "create";
        $this->campaignModal = Campaign::find($requestBody['obj']['campaignId']);

        if ($requestBody['obj']['step'] > (array_search($this->campaignModal->step, $this->steps) + 1)) {
            $this->campaignModal->step = $this->steps[$requestBody['obj']['step'] - 1];
            $this->campaignModal->save();

            foreach ($requestBody['obj']['segmentIds'] as $segmentId) {
                $this->campaignSegmentsModal = new CampaignSegments();
                $this->campaignSegmentsModal->campaign_id = $requestBody['obj']['campaignId'];
                $this->campaignSegmentsModal->segment_id = $segmentId;
                $this->campaignSegmentsModal->save(['timestamps' => false]);
            }

        } else {
            $mode = "edit";
            CampaignSegments::where('campaign_id', $requestBody['obj']['campaignId'])->delete();
            foreach ($requestBody['obj']['segmentIds'] as $segmentId) {
                $this->campaignSegmentsModal = new CampaignSegments();
                $this->campaignSegmentsModal->campaign_id = $requestBody['obj']['campaignId'];
                $this->campaignSegmentsModal->segment_id = $segmentId;
                $this->campaignSegmentsModal->save(['timestamps' => false]);
            }
        }

        $segments = $this->campaignModal->segments;
        if (($segments instanceof \Illuminate\Support\Collection) && ($segments->count() > 0)) {

            if ($mode == "edit") {

                CompanyAttributeData::clearAllCache($this->campaignModal->company_id, $requestBody['obj']['campaignId']);
            }

            foreach ($segments as $segment) {
                CompanyAttributeData::segments($segment);

                $status = CompanyAttributeData::segmentCache($segment, true);

                if ($status === false) {
                    CompanyAttributeData::segmentCache($segment);
                }

                CompanyAttributeData::campaignSegmentsCache($segment);
            }
        }

        $this->campaignModal->count = count($this->getUsersOfSegmentsAgainstCampaign($requestBody['obj']['campaignId'], true));
        return $this->campaignModal->count;
    }

    public function submitStep5($requestBody)
    {
        $this->campaignModal = Campaign::find($requestBody['obj']['campaignId']);

        if ($requestBody['obj']['step'] > (array_search($this->campaignModal->step, $this->steps) + 1)) {
            $this->campaignModal->step = $this->steps[$requestBody['obj']['step'] - 1];
            $this->campaignModal->save();

            if (isset($requestBody['obj']['conversion'])) {
                foreach ($requestBody['obj']['conversion'] as $conversionObj) {
                    $this->campaignActionModal = new CampaignAction();
                    $this->campaignActionModal->campaign_id = $requestBody['obj']['campaignId'];
                    $this->campaignActionModal->action_id = Lookup::where('name', $conversionObj['conversionType'])->first()->id;
                    $this->campaignActionModal->value = $conversionObj['conversionValue'];
                    $this->campaignActionModal->action_type = "conversion";
                    $this->campaignActionModal->validity = $conversionObj['conversionValidity'];
                    $this->campaignActionModal->period = $conversionObj['period'];
                    $this->campaignActionModal->save();
                }
            }

            if (isset($requestBody['obj']['apps'])) {
                foreach ($requestBody['obj']['apps'] as $app) {
                    $this->campaignAppModal = new CampaignApp();
                    $this->campaignAppModal->campaign_id = $requestBody['obj']['campaignId'];
                    $this->campaignAppModal->app_id = $app;
                    $this->campaignAppModal->save(['timestamps' => false]);
                }
            }

        } else {

            CampaignAction::where('campaign_id', $requestBody['obj']['campaignId'])
                ->where("action_type", "conversion")
                ->delete();
            if (isset($requestBody['obj']['conversion'])) {
                foreach ($requestBody['obj']['conversion'] as $conversionObj) {
                    $this->campaignActionModal = new CampaignAction();
                    $this->campaignActionModal->campaign_id = $requestBody['obj']['campaignId'];
                    $this->campaignActionModal->action_id = Lookup::where('name', $conversionObj['conversionType'])->first()->id;
                    $this->campaignActionModal->value = $conversionObj['conversionValue'];
                    $this->campaignActionModal->action_type = "conversion";
                    $this->campaignActionModal->validity = $conversionObj['conversionValidity'];
                    $this->campaignActionModal->period = $conversionObj['period'];
                    $this->campaignActionModal->save();
                }
            }
            CampaignApp::where('campaign_id', $requestBody['obj']['campaignId'])->delete();
            if (isset($requestBody['obj']['apps'])) {
                foreach ($requestBody['obj']['apps'] as $app) {
                    $this->campaignAppModal = new CampaignApp();
                    $this->campaignAppModal->campaign_id = $requestBody['obj']['campaignId'];
                    $this->campaignAppModal->app_id = $app;
                    $this->campaignAppModal->save(['timestamps' => false]);
                }
            }
        }

        return $this->campaignModal->id;
    }

    public function getCompanyTemplate($companyId)
    {
        /*return CampaignTemplateController::where('company_id', $companyId)
            ->select('id', 'content', 'thumbNail')
            ->get();*/

        return CampaignTemplate::where('type', 'EMAIL')
            ->select('id', 'content', 'thumbNail as halfUrl')
            ->get();
    }

    public function getCampaignTemplate($companyId)
    {
        return Campaign::where('company_id', $companyId)
            ->where('type_id', '1')
            ->whereNotIn('status', array('expired', 'suspend'))
            ->orderBy('id', 'desc')
            ->select('id', 'name', 'en as content', 'created_at')
            ->take(5)
            ->get();
        //dd($a[0]->mutateCode);
    }

    public function getInAppData($companyId)
    {
        $dataObj = (object)[];
        $platformId = DB::table('lookup')->where('code', 'Platform')
            ->first()->id;

        $platformList = DB::table('lookup')->where('parent_id', $platformId)
            ->pluck('name');

        $messageTypeId = DB::table('lookup')->where('code', 'Message_Type')
            ->first()->id;

        $messageTypeList = DB::table('lookup')->where('parent_id', $messageTypeId)
            ->pluck('name');

        $layoutTypeId = DB::table('lookup')->where('code', 'Layout')
            ->first()->id;

        $layoutTypeList = DB::table('lookup')->where('parent_id', $layoutTypeId)
            ->pluck('name');

        $devicePositionId = DB::table('lookup')->where('code', 'Device_Position')
            ->first()->id;

        $devicePositionList = DB::table('lookup')->where('parent_id', $devicePositionId)
            ->pluck('name');

        $actionId = DB::table('lookup')->where('code', 'Action')
            ->first()->id;

        $actionList = DB::table('lookup')->where('parent_id', $actionId)
            ->pluck('name');

        $inAppTemplates = CampaignTemplate::where('type', 'DIALOGUE')
            ->orwhere('type', 'FULL SCREEN')
            ->orwhere('type', 'BANNER')
            ->orwhere('type', 'PUSH')
            ->select('content', 'type')
            ->get();

        $dataObj->platformList = $platformList;
        $dataObj->messageTypeList = $messageTypeList;
        $dataObj->layoutTypeList = $layoutTypeList;
        $dataObj->devicePositionList = $devicePositionList;
        $dataObj->actionList = $actionList;
        $dataObj->inAppTemplates = $inAppTemplates;

        return $dataObj;
    }

    public function getCampaignActionData($companyId)
    {
        $actionId = DB::table('lookup')
            ->where('code', 'ACTION_TRIGGERS')
            ->first()->id;

        $actionLookUpData = DB::table('lookup')
            ->where('parent_id', $actionId)
            ->where('company_id', $companyId)
            ->select('code', 'name')
            ->get();

        $arr = [];
        foreach ($actionLookUpData as $data) {
            $obj = (object)[];
            $obj->name = $data->name;
            $value = DB::table('attribute_data')
                ->where('company_id', $companyId)
                ->where('attribute_data.code', $data->code)
                ->where('data_type', 'action')
                ->pluck('value');
            $obj->values = $value;
            if (!empty($obj->values))
                $arr[] = clone $obj;
        }
        return $arr;
    }

    public function campaignListing($request, $companyId)
    {
        $columns = array(
            1 => 'name',
            2 => 'typeName',
            4 => 'totalSend',
            5 => 'totalSuccess',
            6 => 'totalFailed',
            7 => 'totalView',
            8 => 'updated_at',
        );

        $totalData = Campaign::where('company_id', $companyId)
            ->count();
        //$totalFiltered = $totalData;  // 2
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $order = $columns[$request->input('order.0.column')];  //name
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""
        $column = $request['column'];

        $value = $request['value'];

        $campaignListing = $this->getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $column, $value);

        if (!empty($search)) {

            $myQuery = DB::table('campaign')
                ->join('users', 'campaign.created_by', '=', 'users.id')
                ->where('company_id', $companyId)
                ->where('campaign.is_deleted', 0);
            if ($column != 'all') {
                if ($column == 'tags') {
                    $myQuery->whereRaw("FIND_IN_SET('$value', BINARY $column) > 0");
                } else
                    $myQuery->where('campaign.' . $column, $value);
            }
            $myQuery->where(function ($query) use ($search) {
                $query->where('campaign.name', 'LIKE', "%{$search}%")
                    ->orWhere('campaign.updated_at', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%");
            });
            $totalFiltered = $myQuery->count();
        } else {
            $myQuery = DB::table('campaign')
                ->join('users', 'campaign.created_by', '=', 'users.id')
                ->where('company_id', $companyId)
                ->where('campaign.is_deleted', 0);
            if ($column != 'all') {
                if ($column == 'tags') {
                    $myQuery->whereRaw("FIND_IN_SET('$value', BINARY $column) > 0");
                } else
                    $myQuery->where('campaign.' . $column, $value);
            }
            $totalFiltered = $myQuery->count();
        }
        return array($totalData, $totalFiltered, $campaignListing);
    }

    public function getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $column, $value)
    {
        $myQuery = DB::table('campaign')
            ->join('users', 'campaign.created_by', '=', 'users.id')
            ->join('campaign_types', 'campaign_types.id', '=', 'campaign.type_id')
            ->leftJoin('link_tracking', function ($join) {
                $join->on('link_tracking.rec_id', '=', 'campaign.id')
                    ->where('link_tracking.rec_type', '=', DB::raw("Email"));
            })
            ->select(
                DB::raw('(select count(if(campaign_tracking.viewed=0,null,1)) as totalView 
                                 from campaign_tracking where campaign_tracking.campaign_id=campaign.id) as totalView'),
                DB::raw('(SELECT count(*) as totalSend FROM campaign_tracking ct WHERE ct.campaign_id = campaign.id AND ct.status NOT IN (\'added\',\'executing\')) as totalSend'),
                DB::raw('(SELECT count(*) as totalSend FROM campaign_tracking ct WHERE ct.campaign_id = campaign.id AND ct.status  IN (\'completed\')) as totalSuccess'),
                DB::raw('(SELECT count(*) as totalSend FROM campaign_tracking ct WHERE ct.campaign_id = campaign.id AND ct.status IN (\'failed\')) as totalFailed'),
                DB::raw('count(link_tracking.id) as totalClick'),
                'campaign_types.name as typeName', 'campaign.id', 'campaign.created_at', 'campaign.status', 'campaign.name', 'campaign.updated_at',
                'users.name as created_by', 'en', 'ar'/*, 'rs', 'ca'*/)
            ->where('company_id', $companyId)
            ->where('campaign.is_deleted', 0);
        if ($column != 'all') {
            if ($column == 'tags') {
                $myQuery->whereRaw("FIND_IN_SET('$value', BINARY $column) > 0");
            } else {

                $myQuery->where('campaign.' . $column, $value);
            }
        }
        if (empty($search)) {


            $campaign = $myQuery
                ->groupBy('campaign.id')
                ->orderBy($order, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        } else {

            $myQuery->where(function ($query) use ($search) {
                $query->where('campaign.name', 'LIKE', "%{$search}%")
                    ->orWhere('campaign.updated_at', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%");
            });
            $campaign = $myQuery
                ->groupBy('campaign.id')
                ->orderBy($order, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }
        return $campaign;
    }

    public function getSteps($campaignId)
    {
        $this->campaignModal = Campaign::find($campaignId);
        return array_search($this->campaignModal->step, $this->steps) + 1;
    }

    public function getStep1($campaignId)
    {
        $this->campaignModal = Campaign::find($campaignId);
        $step1Obj = (object)[];
        $step1Obj->name = $this->campaignModal->name;
        $step1Obj->tags = $this->campaignModal->tags;
        $step1Obj->type_id = $this->campaignModal->type_id;
        $step1Obj->subject = $this->campaignModal->subject;
        $step1Obj->from_email = $this->campaignModal->from_email;
        $step1Obj->from_name = $this->campaignModal->from_name;
        $step1Obj->status = $this->campaignModal->status;
        $step1Obj->action_target = $this->campaignModal->action_target;
        return $step1Obj;

    }

    public function getStep2($campaignId)
    {
        $this->campaignModal = Campaign::find($campaignId);
        $step2Obj = (object)[];

        if ($this->campaignModal->type_id == 1) {
            $emailContentData = '';
            if ($this->campaignModal->ar /*&& $this->campaignModal->ar != ''*/) {
                $emailContentData = $this->anchorTagOperation($this->campaignModal->ar, 'dcrypt', $campaignId);
                $step2Obj->content = $this->is_base64_encoded($emailContentData) ? base64_decode($emailContentData) : $emailContentData;
                $step2Obj->lang = 'ar';
            } else if ($this->campaignModal->rs /*&& $this->campaignModal->rs != ''*/) {
                $emailContentData = $this->anchorTagOperation($this->campaignModal->rs, 'dcrypt', $campaignId);
                $step2Obj->content = $this->is_base64_encoded($emailContentData) ? base64_decode($emailContentData) : $emailContentData;
                $step2Obj->lang = 'ru';
            } else if ($this->campaignModal->ca /*&& $this->campaignModal->ca != ''*/) {
                $emailContentData = $this->anchorTagOperation($this->campaignModal->ca, 'dcrypt', $campaignId);
                $step2Obj->content = $this->is_base64_encoded($emailContentData) ? base64_decode($emailContentData) : $emailContentData;
                $step2Obj->lang = 'ca';
            } else {
                $emailContentData = $this->anchorTagOperation($this->campaignModal->en, 'dcrypt', $campaignId);
                $step2Obj->content = $this->is_base64_encoded($emailContentData) ? base64_decode($emailContentData) : $emailContentData;
                $step2Obj->lang = 'en';
            }
        } else if ($this->campaignModal->type_id == 2) {
            if ($this->campaignModal->ar) {
                $this->campaignModal->ar = json_decode($this->campaignModal->ar);
                $step2Obj->templateInfo = $this->campaignModal->ar;
                $step2Obj->lang = 'ar';
            } else if ($this->campaignModal->rs) {
                $this->campaignModal->rs = json_decode($this->campaignModal->rs);
                $step2Obj->templateInfo = $this->campaignModal->rs;
                $step2Obj->lang = 'rs';
            } else if ($this->campaignModal->ca) {
                $this->campaignModal->ca = json_decode($this->campaignModal->ca);
                $step2Obj->templateInfo = $this->campaignModal->ca;
                $step2Obj->lang = 'ca';
            } else {
                $this->campaignModal->en = json_decode($this->campaignModal->en);
                $step2Obj->templateInfo = $this->campaignModal->en;
                $step2Obj->lang = 'en';
            }
            $step2Obj->plateform = Lookup::where('id', $this->campaignModal->platform_id)->first()->name;
        } else {
            $step2Obj->plateform = Lookup::where('id', $this->campaignModal->platform_id)->first()->name;
            $step2Obj->messageType = Lookup::where('id', $this->campaignModal->message_type_id)->first()->name;
            $step2Obj->orientation = Lookup::where('id', $this->campaignModal->orientation_id)->first()->name;
            $step2Obj->position = Lookup::where('id', $this->campaignModal->position_id)->first();

            if ($step2Obj->position) {
                $step2Obj->position = $step2Obj->position->name;
            } else {
                $step2Obj->position = -1;
            }

            if ($this->campaignModal->ar) {
                $this->campaignModal->ar = json_decode($this->campaignModal->ar);
                $step2Obj->templateInfo = $this->campaignModal->ar;
                $step2Obj->lang = 'ar';
            } else if ($this->campaignModal->rs) {
                $this->campaignModal->rs = json_decode($this->campaignModal->rs);
                $step2Obj->templateInfo = $this->campaignModal->rs;
                $step2Obj->lang = 'rs';
            } else if ($this->campaignModal->ca) {
                $this->campaignModal->ca = json_decode($this->campaignModal->ca);
                $step2Obj->templateInfo = $this->campaignModal->ca;
                $step2Obj->lang = 'ca';
            } else {
                $this->campaignModal->en = json_decode($this->campaignModal->en);
                $step2Obj->templateInfo = $this->campaignModal->en;
                $step2Obj->lang = 'en';
            }
        }
        $step2Obj->code = $this->campaignModal->code;

        $step2Obj->cappingRuleControl = DB::table("campaign_types")
            ->join("campaign_cap_rules", "campaign_types.name", "=", "campaign_cap_rules.campaign_type")
            ->where("campaign_cap_rules.company_id", $this->campaignModal->company_id)
            ->where("campaign_types.id", $this->campaignModal->type_id)
            ->first();

        return $step2Obj;
    }

    public function getStep3($campaignId)
    {
        $this->campaignModal = Campaign::find($campaignId);
        $arr = [];
        $step3Obj = (object)[];
        $step3Obj->delivery_type = $this->campaignModal->delivery_type;
        $step3Obj->start_time = $this->campaignModal->start_time;
        $step3Obj->end_time = $this->campaignModal->end_time;

        if ($step3Obj->delivery_type == 'action') {
            $campaignAction = CampaignAction::where('campaign_id', $campaignId)
                ->where('action_type', 'trigger')
                ->get();

            foreach ($campaignAction as $item) {
                $obj = (object)[];
                $obj->action_id = Lookup::where('id', $item->action_id)->first()->name;
                $obj->value = $item->value;
                $arr[] = $obj;
            }
            $step3Obj->input = $this->campaignModal->action_trigger_delay_value;
            $step3Obj->value = $this->campaignModal->action_trigger_delay_unit;
            $step3Obj->actions = $arr;
        } else {
            $step3Obj->schedule_type = $this->campaignModal->schedule_type;

            if ($step3Obj->schedule_type == 'WEEEKLY') {
                $step3Obj->days = [];
                $this->campaignScheduleModal = CampaignSchedule::where('campaign_id', '=', $campaignId)->get();
                foreach ($this->campaignScheduleModal as $obj) {
                    $step3Obj->days[] = $obj->day;
                }
            }
        }

        $step3Obj->deliveryInput = $this->campaignModal->delivery_control_delay_value;
        $step3Obj->deliveryValue = $this->campaignModal->delivery_control_delay_unit;
        $step3Obj->deliveryPriority = $this->campaignModal->campaign_priority;
        $step3Obj->deliveryIsChecked = $this->campaignModal->enable_delivery_control;
        $step3Obj->enableCapping = $this->campaignModal->enable_capping;

        return $step3Obj;
    }

    public function getStep4($campaignId)
    {
        $step4Obj = (object)[];

        $step4Obj->segments = DB::table('campaign_segments')
            ->join('segment', 'campaign_segments.segment_id', '=', 'segment.id')
            ->where('campaign_segments.campaign_id', $campaignId)
            ->select('segment.id', 'segment.name as text')
            ->get();

        $step4Obj->reachableUsers = count($this->getUsersOfSegmentsAgainstCampaign($campaignId, true));

        return $step4Obj;
    }

    public function getStep5($campaignId)
    {
        $campaignConversion = CampaignAction::where('campaign_id', $campaignId)
            ->where("action_type", "conversion")
            ->get();

        $obj = (object)[];
        $obj->conversion = [];

        foreach ($campaignConversion as $conversion) {
            $innerObj = (object)[];
            $innerObj->conversionType = Lookup::where('id', $conversion->action_id)->first()->name;
            $innerObj->conversionValue = $conversion->value;
            $innerObj->conversionValidity = $conversion->validity;
            $innerObj->period = $conversion->period;
            $obj->conversion[] = $innerObj;
        }

        $obj->apps = CampaignApp::where('campaign_id', $campaignId)->pluck('app_id');

        return $obj;
    }

    public function launchCampaign($campaignId)
    {
        $this->campaignModal = Campaign::find($campaignId);
        $this->campaignModal->status = 'active';//Campaign::STATUS_ACTIVE;
        $this->campaignModal->save();

        return $this->campaignModal->id;
    }

    public function anchorTagOperation($html, $type, $campaignId) //Implementation of Deterministic finite automaton state Machine
    {
        $duplicateHtml = '';
        $link = '';
        $currentState = 'start';
        for ($i = 0; $i < strlen($html); $i++) {
            switch ($currentState) {
                case 'start':
                    if ($html[$i] == '<') {
                        $currentState = 's1';
                    } else {
                        $currentState = 'start';
                    }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's1':
                    if ($html[$i] == ' ') {
                        $currentState = 's1';
                    } else
                        if ($html[$i] == 'a') {
                            $currentState = 's2';
                        } else {
                            $currentState = 'start';
                        }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's2':
                    if ($html[$i] == ' ') {
                        $currentState = 's3';
                    } else {
                        $currentState = 'start';
                    }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's3':
                    if ($html[$i] == ' ') {
                        $currentState = 's3';
                    } else
                        if ($html[$i] == 'h') {
                            $currentState = 's4';
                        } else {
                            $currentState = 'start';
                        }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's4':
                    if ($html[$i] == 'r') {
                        $currentState = 's5';
                    } else {
                        $currentState = 'start';
                    }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's5':
                    if ($html[$i] == 'e') {
                        $currentState = 's6';
                    } else {
                        $currentState = 'start';
                    }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's6':
                    if ($html[$i] == 'f') {
                        $currentState = 's7';
                    } else {
                        $currentState = 'start';
                    }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's7':
                    if ($html[$i] == ' ') {
                        $currentState = 's7';
                    } else
                        if ($html[$i] == '=') {
                            $currentState = 's8';
                        } else {
                            $currentState = 'start';
                        }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's8':
                    if ($html[$i] == ' ') {
                        $currentState = 's8';
                    } else
                        if ($html[$i] == '"') {
                            $currentState = 's9';
                        } else {
                            $currentState = 'start';
                        }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    break;
                case 's9':
                    if ($html[$i] == '"') {
                        if ($type == 'encrypt') {
                            $duplicateHtml = $duplicateHtml . $this->encrypt($link, $campaignId);
                        } else {
                            $duplicateHtml = $duplicateHtml . $this->dcrypt($link);
                        }
                        $duplicateHtml = $duplicateHtml . $html[$i];
                        $currentState = 's10';
                    } else {
                        $link = $link . $html[$i];
                    }
                    break;
                case 's10':
                    if ($html[$i] == ' ') {
                        $currentState = 's10';
                    } else
                        if ($html[$i] == '>') {
                            $currentState = 'start';
                        }
                    $duplicateHtml = $duplicateHtml . $html[$i];
                    $link = '';
            }
        }
        return $duplicateHtml;
    }

    public function encrypt($link, $campaignId, $type = 'Email', $rowId = null)
    {
        if ($link == "http://closeme.engagement.com/") {
            return $link;
        }

        if ($link == "#" || $link == "") {
            return "javascript:void(0);";
        } else {

            $link = htmlspecialchars_decode($link);

            if ($rowId) {

                return url('') . '/trackLink?enc=' . base64_encode($type . '/' . $campaignId . '/' . $rowId . '/' . str_replace(' ', '', $link));
            } else {

                return url('') . '/trackLink?enc=' . base64_encode($type . '/' . $campaignId . '/' . $link);
            }
        }
    }

    public function dcrypt($link, $campaignId = "")
    {
        $decodeUrl = base64_decode($link);
        if ($campaignId == "") {
            $originalUrl = explode("/", $decodeUrl);
            if (isset($originalUrl[0]) && isset($originalUrl[1])) {

                $originalUrl = str_replace($originalUrl[0] . '/' . $originalUrl[1] . '/', "", $decodeUrl);
            } else {
                return "javascript:void(0)";
            }
            return $originalUrl;
        } else {
            $originalUrl = explode("/", $decodeUrl);
            $type = $originalUrl[0];
            $id = $originalUrl[1];
            if (isset($originalUrl[2])) {
                $rowId = $originalUrl[2];
            } else {

                $rowId = null;
            }
            $originalUrl = str_replace($originalUrl[0] . '/' . $originalUrl[1] . '/', "", $decodeUrl);
//            dump($originalUrl);die;
            if ($type != 'Email') {
                if ($rowId) {
                    $originalUrl = str_replace($rowId . '/', "", $originalUrl);

                }
            }
            return array($id, $type, $originalUrl, $rowId);
        }

    }

    public function dcrypt2($link, $campaignId = "")
    {
//        $url = explode("/", $link);
//        $url = $url[sizeof($url) - 1];
        $decodeUrl = base64_decode($link);

        if ($campaignId == "") {
            $originalUrl = explode("/", $decodeUrl);
            if (isset($originalUrl[0]) && isset($originalUrl[1])) {

                $originalUrl = str_replace($originalUrl[0] . '/' . $originalUrl[1] . '/', "", $decodeUrl);
            } else {
                return "javascript:void(0)";
            }
            return $originalUrl;
        } else {
            $originalUrl = explode("/", $decodeUrl);
//            dump($decodeUrl,$originalUrl);die;
            $type = $originalUrl[0];
            $id = $originalUrl[1];
            if (isset($originalUrl[2])) {
                $rowId = $originalUrl[2];
            } else {

                $rowId = null;
            }
            $originalUrl = str_replace($originalUrl[0] . '/' . $originalUrl[1] . '/', "", $decodeUrl);
//            dump($originalUrl);die;
            if ($type != 'Email') {
                if ($rowId) {
                    $originalUrl = str_replace($rowId . '/', "", $originalUrl);

                }
            }
            return array($id, $type, $originalUrl, $rowId);
        }

    }

    public function submitLinkTracking($rec_type, $rec_id, $actual_url, $ip_address, $user_agent, $device, $rowId = null)
    {
        $this->linkTrackingModal->rec_type = $rec_type;
        $this->linkTrackingModal->rec_id = $rec_id;
        $this->linkTrackingModal->actual_url = $actual_url;
        $this->linkTrackingModal->created_date = new DateTime('now');
        $this->linkTrackingModal->ip_address = $ip_address;
        $this->linkTrackingModal->user_agent = $user_agent;
        $this->linkTrackingModal->device = $device;
        $this->linkTrackingModal->row_id = $rowId;
        $this->linkTrackingModal->save();
    }

    public function getUserIdByEmail($companyKey)
    {
        $companyKey = User::where('id', $companyKey)
            ->first()->company_key;
        return array('company_key' => $companyKey);
    }

    public function getCampaignConversionData($companyId)
    {
        $conversionId = DB::table('lookup')
            ->where('code', 'CONVERSION_TYPES')
            ->first()->id;

        $conversionLookUpData = DB::table('lookup')
            ->where('parent_id', $conversionId)
            ->where('company_id', $companyId)
            ->select('code', 'name')
            ->get();

        $arr = [];
        foreach ($conversionLookUpData as $data) {
            $obj = (object)[];
            $obj->name = $data->name;
            $value = DB::table('attribute_data')
                ->where('company_id', $companyId)
                ->where('attribute_data.code', $data->code)
                ->where('data_type', 'conversion')
                ->pluck('value');
            $obj->values = $value;
            //if (!empty($obj->values))
            $arr[] = clone $obj;
        }
        return $arr;
    }

    public function campaignFilters($companyId)
    {
        $schedule = [
            'key' => 'schedule_type',
            'Weekly' => 'WEEEKLY',
            'Daily' => 'DAILY',
            'Once' => 'ONCE'
        ];

        $type = [
            'key' => 'type_id',
            'Email' => '1',
            'Push' => '2',
            'InApp' => '3'
        ];

        $status = [
            'key' => 'status',
            'Active' => 'active',
            'Draft' => 'draft',
            'Suspend' => 'suspend',
            'Expired' => 'expired'
        ];

        $tags = DB::select("SELECT t.tags, count(*) AS occurence FROM (SELECT campaign.id, SUBSTRING_INDEX(SUBSTRING_INDEX(campaign.tags, ',', numbers.n), ',', -1) tags FROM (SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4) numbers INNER JOIN campaign ON CHAR_LENGTH(campaign.tags) -CHAR_LENGTH(REPLACE(campaign.tags, ',', ''))>=numbers.n-1 WHERE campaign.company_id = $companyId ORDER BY id, n) t WHERE t.tags != '' GROUP BY BINARY t.tags ORDER BY occurence DESC, t.tags ASC limit 5");

        return array('schedule' => $schedule, 'type' => $type, 'status' => $status, 'tags' => $tags);

    }

    public function getAttributesDataFromSegmentQuery($query, $companyId)
    {
        $queryString = "SELECT `value` FROM attribute_data WHERE row_id IN (SELECT row_id FROM attribute_data WHERE" . $query . ") AND `code`='user_id' and company_id = " . $companyId;
        $userIds = DB::select($queryString);
        return $userIds;
    }

    public function getAttributes($companyId)
    {
        $queryString = 'SELECT a1.code, a1.name FROM attribute as a1 ';
        $queryString .= 'WHERE (a1.company_id = ' . $companyId . ' OR a1.type = "General") AND Not EXISTS ( ';
        $queryString .= 'SELECT * ';
        $queryString .= 'FROM attribute as a2 ';
        $queryString .= 'WHERE a2.type = "General" AND a1.code = a2.code AND a1.type != a2.type ) ';
        $queryString .= 'GROUP BY a1.code ';

        return DB::Select($queryString);
    }

    public function getAttributeData($companyId)
    {
        return AttributeData::where('company_id', $companyId)
            ->where('data_type', 'custom_attribute')
            ->select('code', 'value as name')
            ->get();
    }

    public function getCompanySegmentsWithSearch($companyId, $searchStr)
    {
        return Segment::where('company_id', $companyId)
            ->where('name', 'LIKE', "%{$searchStr}%")
            ->select('id', 'name as text')
            ->get();
    }


    public function getTestUsersData($companyId, $searchStr, $campaignType, $deviceType)
    {
        //$testUsers = CompanyAttributeData::rows($companyId);
        //$users = [];
        /*if (!empty($testUsers)) {
            $userObj = (object)[];
            foreach ($testUsers as $user) {

                if ($campaignType == "notEmail" && strtolower($deviceType) != "universal") {
                    if (isset($user['email']) && isset($user['device_type']) && strpos(strtolower($user['email']), strtolower($searchStr)) !== false && strtolower($user['device_type']) == strtolower($deviceType)) {
                        $userObj->text = $user['email'] . '(' . $user['user_id'] . ')' . ' - ' . $user['app_name'];
                        $userObj->id = $user['row_id'];
                        $users[] = clone $userObj;
                    }
                } else {
                    if (isset($user['email']) && strpos(strtolower($user['email']), strtolower($searchStr)) !== false) {
                        $userObj->text = $user['email'] . '(' . $user['user_id'] . ')' . ' - ' . $user['app_name'];
                        $userObj->id = $user['row_id'];
                        $users[] = clone $userObj;
                    }
                }
            }
            return $users;
        }*/

        $queryChain = DB::table("user_attribute")
            ->where('company_id', $companyId)
            ->where("email", 'LIKE', "%{$searchStr}%");
        if ($campaignType == "notEmail" && strtolower($deviceType) != "universal") {
            $queryChain->where("device_type", $deviceType);
        }
        return $queryChain->select(DB::raw('CONCAT(email, "(", user_id, ")-", app_name) as text'), 'row_id as id')
            ->get();
    }

    public function getCampaignApps($companyId)
    {
        $apps = Apps::where('company_id', $companyId)
            ->select("id", "name", "logo", "platform")
            ->get();
        return $apps;
    }


    public function getActionList($postedData, $user)
    {


        $dataArr = array();
        if ($postedData['data_type'] == "action") {

            $lookUpData = Lookup::where("parent_id", 1)->get();


            foreach ($lookUpData as $item) {


                $actionList = AttributeData::where("company_id", $user->id)->where("code", strtolower($item->code))->select("code", "value")->where("data_type", $postedData['data_type'])->get();
                foreach ($actionList as $list)
                    array_push($dataArr, array(
                        "id" => $item->id,
                        "code" => $list->code,
                        "value" => $list->value,
                    ));

            }
        } elseif ($postedData['data_type'] == "conversion") {

            $lookUpData = Lookup::where("parent_id", 89)->get();


            foreach ($lookUpData as $item) {


                $actionList = AttributeData::where("company_id", $user->id)->where("code", strtolower($item->code))->select("code", "value")->where("data_type", $postedData['data_type'])->get();
                foreach ($actionList as $list)
                    array_push($dataArr, array(
                        "id" => $item->id,
                        "code" => $list->code,
                        "value" => $list->value,
                    ));

            }
        } else {

            $dataArr = AttributeData::where("company_id", $user->id)->select("code", "value")->where("data_type", $postedData['data_type'])->get();

        }

        return $dataArr;
    }

    public function getPlatform($campaignId)
    {
        $platform = DB::table('campaign')
            ->join('lookup', 'campaign.platform_id', '=', 'lookup.id')
            ->where('campaign.id', $campaignId)
            ->select('lookup.name')
            ->first();

        if (isset($platform))
            return $platform->name;
        return $platform;
    }

    public function getClicksAndViews($platform, $dateRange, $campaignId)
    {
        $clickAndViews = (object)[];
        $clickAndViews->views = $this->getViews($platform, $dateRange, $campaignId);
        $clickAndViews->clicks = $this->getClicks($platform, $dateRange, $campaignId);
        return $clickAndViews;
    }

    public function getViews($platform, $dateRange, $campaignId)
    {
        return DB::table('campaign_tracking')
            ->where('campaign_id', $campaignId)
            ->where('viewed', 1)
            ->where('device_type', $platform)
            ->whereDate('viewed_at', '>=', $dateRange['startDate'])
            ->whereDate('viewed_at', '<=', $dateRange['endDate'])
            ->groupBy('campaign_id', 'viewed')
            ->count();

    }

    public function getClicks($platform, $dateRange, $campaignId)
    {
        return DB::table('link_tracking')
            ->where('rec_id', $campaignId)
            ->where('device', $platform)
            ->whereDate('created_date', '>=', $dateRange['startDate'])
            ->whereDate('created_date', '<=', $dateRange['endDate'])
            ->groupBy('rec_id')
            ->count();
    }

    public function getIntervalForChart($dateRange)
    {
        $period = new \DatePeriod(
            new DateTime($dateRange['startDate']),
            new \DateInterval('P1D'),
            new DateTime($dateRange['endDate'])
        );

        $interval = [];
        foreach ($period as $key => $value) {
            $interval[] = $value->format('Y-m-d');
        }
        $interval[] = $dateRange['endDate'];
        return $interval;
    }

    public function getChartsForViewsAndroidAndIos($campaignId, $interval, $platform)
    {
        $view = [];
        $view[] = $this->getChartsForViewsAndroid($campaignId, $interval, 'ANDROID');
        $view[] = $this->getChartsForViewsIos($campaignId, $interval, 'IOS');
        return $view;
    }

    public function getChartsForViewsAndroid($campaignId, $interval, $platform)
    {
        // dd($platform);
        $obj = (object)[];
        $obj->name = 'android';
        $obj->data = [];
        foreach ($interval as $date) {
            if (isset($platform) && strtolower($platform) != 'ios') {
                $obj->data[] = DB::table('campaign_tracking')
                    ->where('device_type', $platform)
                    ->where('campaign_id', $campaignId)
                    ->where('viewed', 1)
                    ->whereDate('viewed_at', '=', $date)
                    ->groupBy('campaign_id', 'viewed')
                    ->count();
            } else
                $obj->data[] = 0;
        }
        return $obj;
    }

    public function getChartsForViewsIos($campaignId, $interval, $platform)
    {
        $obj = (object)[];
        $obj->name = 'ios';
        $obj->data = [];
        foreach ($interval as $date) {
            if (isset($platform) && strtolower($platform) != 'android') {
                $obj->data[] = DB::table('campaign_tracking')
                    ->where('device_type', $platform)
                    ->where('campaign_id', $campaignId)
                    ->where('viewed', 1)
                    ->whereDate('viewed_at', '=', $date)
                    ->groupBy('campaign_id', 'viewed')
                    ->count();
            } else
                $obj->data[] = 0;
        }
        return $obj;
    }

    public function getChartsForClicksAndroidAndIos($campaignId, $interval, $platform)
    {
        $click = [];
        $click[] = $this->getChartsForClicksAndroid($campaignId, $interval, 'android');
        $click[] = $this->getChartsForClicksIos($campaignId, $interval, 'ios');
        return $click;
    }

    public function getChartsForClicksAndroid($campaignId, $interval, $platform)
    {
        $obj = (object)[];
        $obj->name = 'android';
        $obj->data = [];
        foreach ($interval as $date) {
            if (isset($platform) && strtolower($platform) != 'ios') {
                $obj->data[] = DB::table('link_tracking')
                    ->where('rec_id', $campaignId)
                    ->where('device', $platform)
                    ->whereDate('created_date', '=', $date)
                    ->groupBy('rec_id')
                    ->count();
            } else
                $obj->data[] = 0;
        }
        return $obj;
    }

    public function getChartsForClicksIos($campaignId, $interval, $platform)
    {
        $obj = (object)[];
        $obj->name = 'ios';
        $obj->data = [];
        foreach ($interval as $date) {
            if (isset($platform) && strtolower($platform) != 'android') {
                $obj->data[] = DB::table('link_tracking')
                    ->where('device', $platform)
                    ->where('rec_id', $campaignId)
                    ->whereDate('created_date', '=', $date)
                    ->groupBy('rec_id')
                    ->count();
            } else
                $obj->data[] = 0;
        }
        return $obj;
    }

    public function getChartsForClickThroughRateAndroidAndIos($clicks, $view)
    {
        $clickThrough = [];

        foreach ($clicks as $device) {
            if ($device->name == "android") {
                $androidClicks = $device->data;
            }
            if ($device->name == "ios") {
                $iosClicks = $device->data;
            }
        }

        foreach ($view as $device) {
            if ($device->name == "android") {
                $androidView = $device->data;
            }
            if ($device->name == "ios") {
                $iosView = $device->data;
            }
        }

        $clickThrough[] = $this->getChartsForClickThroughRateAndroid($androidClicks, $androidView);
        $clickThrough[] = $this->getChartsForClickThroughRateIos($iosClicks, $iosView);
        return $clickThrough;
    }

    public function getChartsForClickThroughRateAndroid($clicks, $view)
    {
        $obj = (object)[];
        $obj->name = "android";
        $obj->data = [];

        for ($i = 0; $i < sizeof($clicks); $i++) {
            if ($view[$i] != 0)
                $obj->data[] = ($clicks[$i] / $view[$i]) * 100;
            else
                $obj->data[] = 0;
        }

        return $obj;
    }

    public function getChartsForClickThroughRateIos($clicks, $view)
    {
        $obj = (object)[];
        $obj->name = "ios";

        for ($i = 0; $i < sizeof($clicks); $i++) {
            if ($view[$i] != 0)
                $obj->data[] = ($clicks[$i] / $view[$i]) * 100;
            else
                $obj->data[] = 0;
        }

        return $obj;
    }

    public function getCampaignDetails($campaignId)
    {
        $campaignDetails = DB::table('campaign')
            ->where('id', $campaignId)
            ->select('name', 'en', 'start_time', 'end_time', 'type_id')
            ->first();

        if ($campaignDetails->type_id != 1) {
            $campaignDetails->en = json_decode($campaignDetails->en);
            $campaignDetails->en = $campaignDetails->en->template;
        } else {
            $campaignDetails->en = $this->is_base64_encoded($campaignDetails->en) ? base64_decode($campaignDetails->en) : $campaignDetails->en;
        }


        $campaignDetails->targetAudience = $this->getCampaignSegments($campaignId);
        return $campaignDetails;
    }

    public function getCampaignSegments($campaignId)
    {
        $obj = (object)[];
        $obj->segments = DB::table('campaign_segments')
            ->join('segment', 'campaign_segments.segment_id', '=', 'segment.id')
            ->where('campaign_segments.campaign_id', $campaignId)
            ->pluck('segment.name');
        $obj->reachableUsers = count($this->getUsersOfSegmentsAgainstCampaign($campaignId, true));
        return $obj;
    }

    public function checkAndGetCampaignTemplate($campaignId, $column)
    {
        $content = DB::table("campaign")
            ->where('id', $campaignId)
            ->select($column, 'type_id')
            ->get();

        if ($content[0]->$column) {

            if ($content[0]->type_id == 1) {
                $emailContentData = $this->anchorTagOperation($content[0]->$column, 'dcrypt', $campaignId);
                return $this->is_base64_encoded($emailContentData) ? base64_decode($emailContentData) : $emailContentData;
            } else {
                $obj = $this->getStep2($campaignId);
                $obj->lang = $column;
                $obj->templateInfo = json_decode($content[0]->$column);
                return $obj;
            }
        }
        return $content[0]->$column;
    }

    public function getCappingSettings($companyId)
    {
        return CampaignCapRule::where("company_id", $companyId)
            ->select("cap_limit as cappingLimit", "campaign_type as campaignType", "duration_value as durationValue", "duration_unit as durationUnit")
            ->get();
    }

    public function submitCappingSettings($cappingArr, $companyId)
    {
        CampaignCapRule::where("company_id", $companyId)
            ->delete();

        foreach ($cappingArr as $rule) {
            $cap = new CampaignCapRule();
            $cap->company_id = $companyId;
            $cap->cap_limit = $rule["cappingLimit"];
            $cap->campaign_type = $rule["campaignType"];
            $cap->duration_value = $rule["durationValue"];
            $cap->duration_unit = $rule["durationUnit"];
            //$cap->is_deleted = 0;
            $cap->save();
        }
    }

    public function getUsersOfSegmentsAgainstCampaign($campaignId, $count = false)
    {
        $segment_rowIds = CompanyAttributeData::campaignSegments(Auth::user()->id, $campaignId, true);
        if ($count) {
            return array_unique($segment_rowIds);
        }

        $arr = [];
        $headers = DB::table('attribute')
            ->where('company_id', Auth::user()->id)
            ->orWhere('type', 'general')
            ->groupBy('code')
            ->pluck('code');

        if (!in_array("row_id", $headers)) {
            $headers[] = "row_id";
            sort($headers);
        }
        $arr[] = $headers;

        $company_rows = CompanyAttributeData::rows(Auth::user()->id);

        foreach ($company_rows as $key => $value) {
            if (in_array($key, $segment_rowIds)) {
                $record = $company_rows[$key];

                foreach ($record as $key1 => $value1) {
                    if (!in_array($key1, $headers)) {
                        unset($record[$key1]);
                    }
                }

                foreach ($headers as $column) {
                    if (!isset($record[$column])) {
                        $record[$column] = "N/A";
                    }
                }

                ksort($record);
                $arr[] = $record;
            }
        }
        $arr = array_unique($arr, SORT_REGULAR);
        return $arr;
    }

    public function is_base64_encoded($data)
    {
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
            return true;
        } else {
            return false;
        }
    }
}
