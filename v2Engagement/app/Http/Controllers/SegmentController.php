<?php

namespace App\Http\Controllers;

use App\Segment;
use App\User;
use Illuminate\Http\Request;
use App\Engagment\Segment\SegmentWrapper;
use App\Http\Requests;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\NewsFeed;
use App\CampaignSegments;
use App\Components\CompanyAttributeData;


class SegmentController extends Controller
{
    protected $segmentClass;

    public function __construct(SegmentWrapper $segment)
    {
        $this->segmentClass = $segment;
    }

    public function segmentView()
    {
        $companyId = Auth::user()->id;
        $tagsCount = Segment::tagsCount('created_by', $companyId, 'is_deleted');
        return view('segment.segmentListing', ['companyId' => $companyId, 'tagsCount' => $tagsCount]);
    }

    public function segmentCreateView()
    {
        $companyId = Auth::user()->id;
        return view('segment.segmentCrud', ['companyId' => $companyId]);
    }

    public function segmentAction($action, $id)
    {
        $companyId = Auth::user()->id;
        if (($action == 'edit' || $action == 'view')) {
            $segment = Segment::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
            if (isset($segment)) {
                return view('segment.segmentCrud', ['companyId' => $companyId, 'action' => $action, 'id' => $id]);
            }
            return view('segment.error');
        }
        return view('segment.error');
    }

    public function segmentListing(Request $request, $companyId)
    {
        $authUser = Auth::user()->id;
        if ($companyId != $authUser) {
            abort(403, 'Unauthorized');
        }
        list($totalData, $totalFiltered, $segmentListing) = $this->segmentClass->segmentListing($request, $companyId);
        return response()->json([
            'status' => true,
            'data' => $segmentListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Segment listing'
        ]);
    }

    public function segmentGetFilters($companyId = 1)
    {
        try {
            $authUserId = Auth::user()->id;
            if ($companyId != $authUserId) {
                abort(403, 'Unauthorized');
            }
            $filters = $this->segmentClass->GetFilters($companyId);

            $response = [
                'status' => true,
                'data' => $filters,
                'message' => 'Fields Filters'
            ];
        } catch (\Exception $exception) {
            $response = [
                'status' => false,
                'data' => [],
                'message' => $exception->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function segmentCreate(Request $request)
    {
        //dd($request['segmentObj']['companyId']);
        $authUser = Auth::user()->id;
        $companyId = $request['segmentObj']['companyId'];
        if ($companyId != $authUser) {
            abort(403, 'Unauthorized');
        }
        if ($this->segmentClass->saveSegment($request->all())) {
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'segment created/Edited'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Segment Title already exist'
            ]);
        }
    }

    public function segmentGet($id)
    {
        $authUser = Auth::user()->id;
        $segment = DB::table('segment')
            ->where('id', $id)
            ->where('company_id','=',$authUser)
            ->select('name', 'tags', 'json_data as rules')
            ->first();
        if ($segment) {
            $newFeedCount = NewsFeed::where(['segment_id' => $id])->count();
            $campaignCount = CampaignSegments::where(['segment_id' => $id])->count();

            return response()->json([
                'status' => true,
                'data' => $segment,
                'message' => 'Get segment',
                'newFeedCount' => $newFeedCount,
                'campaignCount' => $campaignCount,
            ]);

        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function getSegmentCacheUsers($segmentId)
    {
        $content = $this->segmentClass->getSegmentCacheUsers($segmentId);
        $fileName = $segmentId . '-' . date('Y-m-d H:i:s') . '.csv';

        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . strlen($content));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        echo $content;
        exit;

    }

}
