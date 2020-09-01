<?php

namespace App\Engagment\Segment;

use App\Campaign;
use App\CampaignSegments;
use App\Engagment\Campaign\CampaignHandler;
use App\NewsFeed;
use Faker\Provider\Company;
use Faker\Provider\zh_TW\DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Segment;
use App\Components\CompanyAttributeData;


class SegmentHandler
{
    protected $segmentModal;

    public function __construct(Segment $segmentObj)
    {
        $this->segmentModal = $segmentObj;
    }

    public function segmentListing($request, $companyId)
    {
        $columns = array(
            1 => 'created_at',
            2 => 'name',
            3 => 'user-target',
            4 => 'created_by',
        );

        $totalData = Segment::where('company_id', $companyId)
            ->count();
        //$totalFiltered = $totalData;  // 2
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $order = $columns[$request->input('order.0.column')];  //name
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""
        $column = $request['column'];
        $value = $request['value'];

        $segmentListing = $this->getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $column, $value);

        if (!empty($search)) {

            $myQuery = DB::table('segment')
                ->join('users', 'segment.created_by', '=', 'users.id')
                ->where('company_id', $companyId)
                ->where('segment.is_deleted', 0);
            if ($column != 'all') {
                if ($column == 'tags') {
                    $myQuery->whereRaw("FIND_IN_SET('$value', BINARY $column) > 0");
                } else
                    $myQuery->where($column, $value);
            }
            $myQuery->where(function ($query) use ($search) {
                $query->where('segment.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%");
            });
            $totalFiltered = $myQuery->select('segment.id', 'segment.created_at', 'segment.name', 'users.name as created_by', 'segment.key_value_sql as user_target')
                ->count();

        } else {
            $myQuery = DB::table('segment')
                ->join('users', 'segment.created_by', '=', 'users.id')
                ->where('company_id', $companyId)
                ->where('segment.is_deleted', 0);
            if ($column != 'all') {
                if ($column == 'tags') {
                    $myQuery->whereRaw("FIND_IN_SET('$value', BINARY $column) > 0");
                } else
                    $myQuery->where($column, $value);
            }
            $totalFiltered = $myQuery->select('segment.id', 'segment.created_at', 'segment.name', 'users.name as created_by', 'segment.key_value_sql as user_target')
                ->count();
        }
        foreach ($segmentListing as $item) {
            $item->company_id = Auth::user()->id;
            $item->user_target = count(CompanyAttributeData::getSegmentCache($item));
            //$item->user_target = count($this->getAttributesDataFromSegmentQuery($item, $companyId));
            $item->newsfeedCount = $this->getNewsfeedCount($item);

            $segmentUsed = $this->getCampaignCount($item, $companyId);
            $item->emailCount = 0;
            $item->pushCount = 0;
            $item->inAppCount = 0;
            foreach ($segmentUsed as $count) {

                if ($count->type_id == 1) {

                    $item->emailCount = $count->Segmentused;
                }

                if ($count->type_id == 2) {

                    $item->pushCount = $count->Segmentused;
                }

                if ($count->type_id == 2) {

                    $item->inAppCount = $count->Segmentused;
                }
            }
        }
        return array($totalData, $totalFiltered, $segmentListing);
    }

    public function getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $column, $value)
    {
        if (empty($search)) {
            $myQuery = DB::table('segment')
                ->join('users', 'segment.created_by', '=', 'users.id')
                ->where('company_id', $companyId)
                ->where('segment.is_deleted', 0);
            if ($column != 'all') {
                if ($column == 'tags') {
                    $myQuery->whereRaw("FIND_IN_SET('$value', BINARY $column) > 0");
                } else
                    $myQuery->where($column, $value);
            }
            $segment = $myQuery->select('segment.id', 'segment.created_at', 'segment.name', 'users.name as created_by', 'segment.key_value_sql as user_target')
                ->orderBy($order, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();

        } else {
            $myQuery = DB::table('segment')
                ->join('users', 'segment.created_by', '=', 'users.id')
                ->where('company_id', $companyId)
                ->where('segment.is_deleted', 0);
            if ($column != 'all') {
                if ($column == 'tags') {
                    $myQuery->whereRaw("FIND_IN_SET('$value', BINARY $column) > 0");
                } else
                    $myQuery->where($column, $value);
            }
            $myQuery->where(function ($query) use ($search) {
                $query->where('segment.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%");
            });
            $segment = $myQuery->select('segment.id', 'segment.created_at', 'segment.name', 'users.name as created_by', 'segment.key_value_sql as user_target')
                ->orderBy($order, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }
        return $segment;
    }

    public function GetFields($companyId)
    {
        $queryString = 'SELECT DISTINCT * FROM attribute as a1 ';
        $queryString .= 'WHERE (a1.is_deleted = 0 && (a1.company_id = ' . $companyId . ' OR a1.type = "General")) AND Not EXISTS ( ';
        $queryString .= 'SELECT * ';
        $queryString .= 'FROM attribute as a2 ';
        $queryString .= 'WHERE a2.type = "General" AND a1.code = a2.code AND a1.type != a2.type ) ';
        $queryString .= 'GROUP BY a1.code ';

        return DB::Select($queryString);
    }

    public function getValues($field, $companyId)
    {
        $parentId = DB::table($field->source_table_name)
            ->where('company_id', $companyId)
            ->where('name', $field->code)
            ->first()
            ->id;

        if (!$field->text_column) {

            $list = DB::table($field->source_table_name)
                ->where('parent_id', $parentId)
                ->pluck('name');

        } else {

            $query = 'select name from ' . $field->source_table_name . ' where parent_id=' . $parentId . ' and ' . ' ( ' . $field->where_condition . ' ) ';
            $results = DB::select(DB::raw($query));
            $list = array();
            foreach ($results as $result) {
                $list[] = $result->name;
            }
        }
        return $list;
    }

    public function insertInDb($segmentArr)
    {
        $nameExist = DB::table('segment')
            ->where('company_id', $segmentArr['segmentObj']['companyId'])
            ->where('name', $segmentArr['segmentObj']['campaignTitle']);
        if ($segmentArr['segmentObj']['id'] != -1) {
            $nameExist->where('id', '<>', $segmentArr['segmentObj']['id']);
        }
        if ($nameExist->first()) {
            return false;
        }

        $updateSegment = false;
        if ($segmentArr['segmentObj']['id'] != -1) {
            $this->segmentModal = Segment::find($segmentArr['segmentObj']['id']);

            $updateSegment = true;
            if ($updateSegment === true) {
                CompanyAttributeData::segments($this->segmentModal);

                $status = CompanyAttributeData::segmentCache($this->segmentModal, true);
                if ($status === false) {
                    CompanyAttributeData::segmentCache($this->segmentModal);
                }

                CompanyAttributeData::campaignSegmentsCache($this->segmentModal);

            }
        } else {
            $this->segmentModal->created_by = Auth::user()->id;
            $this->segmentModal->code = $segmentArr['segmentObj']['campaignTitle'] . time();
        }
        $this->segmentModal->company_id = $segmentArr['segmentObj']['companyId'];
        $this->segmentModal->name = $segmentArr['segmentObj']['campaignTitle'];
        $this->segmentModal->tags = $segmentArr['segmentObj']['tagsInput'];
        $this->segmentModal->criteria = base64_decode($segmentArr['segmentObj']['sql']['sql']);
        $this->segmentModal->json_data = $segmentArr['segmentObj']['rules'];
        $this->segmentModal->field_names = implode(",", $segmentArr['segmentObj']['distinctFields']);
        $this->segmentModal->key_value_sql = base64_decode($segmentArr["segmentObj"]["keySql"]);
        $this->segmentModal->is_active = 1;
        $this->segmentModal->is_deleted = 0;
        $this->segmentModal->updated_by = Auth::user()->id;
        $this->segmentModal->save();

        CompanyAttributeData::segments($this->segmentModal);

        $status = CompanyAttributeData::segmentCache($this->segmentModal, true);
        if ($status === false) {
            CompanyAttributeData::segmentCache($this->segmentModal);
        }

        return true;
    }


    public function getNewsfeedCount($segment)
    {
        $queryString = NewsFeed::where("segment_id", $segment->id)->where("is_deleted", 0)->count();
        return $queryString;
    }


    public function getCampaignCount($segment, $companyId)
    {
        $campaignsSegemnt = DB::select("SELECT COUNT(*) as Segmentused,campaign.type_id
FROM campaign JOIN campaign_segments as cs on campaign.id = cs.campaign_id 
WHERE campaign.company_id = " . $companyId . " AND cs.segment_id = " . $segment->id . "
 GROUP BY campaign.type_id");
        return $campaignsSegemnt;

    }

    public function getSegmentCacheUsers($segmentId)
    {
        $segmentObj = Segment::find($segmentId);
        $arr = [];
        $headers = DB::table('attribute')
            ->where('company_id', $segmentObj->company_id)
            ->orWhere('type', 'general')
            ->groupBy('code')
            ->pluck('code');
        
        if(!in_array("row_id", $headers)){
            $headers[] = "row_id";
            sort($headers);
        }
        $arr[] = $headers;

        if ($segmentObj) {
            $rowIds = CompanyAttributeData::getSegmentCache($segmentObj);
            $company_rows = CompanyAttributeData::rows(Auth::user()->id);

            foreach ($company_rows as $key => $value) {
                if (in_array($key, $rowIds)) {
                    $record = $company_rows[$key];

                    foreach($record as $key1 => $value1){
                        if(!in_array($key1, $headers)){
                            unset($record[$key1]);
                        }
                    }

                    foreach($headers as $column){
                        if(!isset($record[$column])){
                            $record[$column] = "\N";
                        }
                    }

                    ksort($record);
                    $arr[] = $record;
                }
            }
            $arr = array_unique($arr, SORT_REGULAR);
        }
        
        return $arr;
    }
}