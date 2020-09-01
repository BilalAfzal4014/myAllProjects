<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignQueue;
use App\Components\RunExternalCommand;
use App\Http\Requests\Request;
use App\Queue;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    public function __construct()
    {
        $this->middleware('superadmin');
    }

    public function index()
    {
        $queues = CampaignQueue::orderBy('id', 'desc')->get();
        //dd($queues);
        return view('jobs.index', compact('queues'));
    }

    public function show($id)
    {
        $contents = [];

        try {
            $queue = CampaignQueue::findOrFail($id);
            $campaign = Campaign::findOrFail($queue->campaign_id);

            if ($queue->status === 'Complete') {
                $queue->status = 'Available';
                $queue->save();

                $contents['title'] = '<i class="fa fa-play"></i> Execute';
                $contents['status'] = 'Available';
                $contents['log'] = "Status is set to 'Available'";
            } else {
                \Artisan::call('backend:campaign:queue', ['id' => $queue->id]);
                //RunExternalCommand::run("/usr/bin/php ".base_path()."/artisan campaign:queue {$queue->id}");
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

    public function destroy($id)
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

    public function queueJobFilter(Request $request)
    {
        $companyId = Auth::user()->id;
        $columns = array(
            0 => 'id',
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
            ->get(['queue.id as id', 'queue.method as method', 'queue.data as data', 'queue.status as status', 'queue.error_message as error_message', 'queue.created_at as created_at']);
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
}
