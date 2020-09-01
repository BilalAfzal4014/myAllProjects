<?php

namespace App\Http\Controllers;

use App\Components\TargetedUsers;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use SendGridForEmail;
use App\Libraries\tv_jwt;
use CommonHelper;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Components\ManageJwtToken;

class QueueController extends Controller
{

    public function listQueueJobs()
    {

        $result = DB::select("select * from queue order by id desc");
        echo '<h1>Queue Jobs</h1> Server Time: ' . date("Y-m-d H:i:s") . '<br>';
        echo 'Total jobs: ' . count($result);
        echo '<table width="90%" border="1" cellspacing="5" cellpading="5">
                <tr>
                    
                    <th>Job ID</th>
                    <th>Method</th>
                    <th>Data</th>
                    <th>Status</th>                    
                    <th>Created At</th>

                </tr>';
        $i = 1;

        foreach ($result as $key => $row) {
            $link = "backend/queue/executeJob?job_id=$row->id";
            echo '<tr>
                       
                        <td>' . $row->id . '</td>
                        <td><a href="' . $link . '"  target="_blank" >' . $row->method . '</a></td>
                        <td>' . $row->data . '</td>
                        <td>' . $row->status . '</td>                        
                        <td>' . $row->created_at . '</td>                       
                    </tr>';

            $i++;
        }

        echo '</table>';


    }

    public function sendCampaignDataInQueue()
    {

//         $query =
// <<<EOT
// SELECT
// CONCAT('{"campaignId":"',`campaign`.`id`,'", ', '"scheduleType":"',`campaign`.`schedule_type`,'", ',
//     '"sendingDate":"',DATE_FORMAT(NOW(), '%Y-%m-%d'),'", ',
//     '"campaignDay":"',IF( `campaign_schedule`.`campaign_id` is NULL,'', `campaign_schedule`.`day`),'"}'
//     ) AS formatedData,
//      campaign.*
// FROM
//     `campaign`
//     LEFT JOIN `campaign_schedule`
//     ON `campaign`.`id` = `campaign_schedule`.`campaign_id`
// WHERE campaign.`is_active` = 1
// AND campaign.`is_deleted` = 0
// AND campaign.`delivery_type` = 'schedule'
// AND campaign.`start_time` <= NOW()
// AND (date_format(`campaign`.`start_time`, '%H:%i:%s') <= date_format(UTC_TIMESTAMP(), '%H:%i:%s'))
// AND campaign.`end_time` >= NOW()
// AND (
//      campaign.`schedule_type` = 'DAILY'
//      OR (
//          campaign.`schedule_type` = 'WEEEKLY'
//          AND campaign_schedule.`day` = UPPER(DATE_FORMAT(NOW(),"%W"))
//      )
//  )
//  AND CONCAT('{"campaignId":"',`campaign`.`id`,'", ', '"scheduleType":"',`campaign`.`schedule_type`,'", ',
//     '"sendingDate":"',DATE_FORMAT(NOW(), '%Y-%m-%d'),'", ',
//     '"campaignDay":"',IF( `campaign_schedule`.`campaign_id` IS NULL,'', `campaign_schedule`.`day`),'"}'
//     )
//  NOT IN ( SELECT `data` FROM queue )
// EOT;
        $result = DB::select("select * from vwQueueFromCampaign");
        if (!empty($result)) {
            $nowDate = Carbon::now();
            foreach ($result as $key => $row) {
                $data[$key]['method'] = 'campaignDataBroadCast';
                $data[$key]['data'] = $row->formatedData;
                $data[$key]['status'] = 'Available';
                $data[$key]['created_at'] = $nowDate;
            }
            DB::table('queue')->insert($data);

            $response = [
                'type' => 'success',
                'message' => 'Data inserted in queue successfully',
            ];
        } else {
            $response = [
                'type' => 'error',
                'message' => 'No record found.',
            ];
        }

        return response()->json($response);
        exit;
    }

    public function newsfeedBroadCast($queueId)
    {
        return (new TargetedUsers($queueId, 'newsfeed', 'all'))->process();
    }

    public function campaignDataBroadCast($queueId)
    {
        return (new TargetedUsers($queueId))->process();
    }

    public function broadcast($queueId)
    {
        $queue = Queue::where([
            ['id', $queueId],
            ['status', 'Available']
        ])->first();

        return !empty($queue->id) ?
            (new TargetedUsers($queue))->process() :
            ['type' => 'error', 'message' => 'No available queue found!'];
    }

    public function sendNewsFeedDataInQueue()
    {

//         $query =
// <<<EOT
//        SELECT
// CONCAT('{"newFeedId":"',`news_feed`.`id`,'", ',
//     '"newFeedName":"',`news_feed`.`name`,'", ',
//     '"sendingDate":"',DATE_FORMAT(NOW(), '%Y-%m-%d'),'"}'
// ) AS formatedData,
//      news_feed.*
// FROM
//     `news_feed`
//  WHERE news_feed.`is_active` = 1
//        AND news_feed.`is_deleted` = 0
//        AND news_feed.`start_time` <= NOW()
//        AND (DATE_FORMAT(`news_feed`.`start_time`, '%H:%i:%s') <= DATE_FORMAT(UTC_TIMESTAMP(), '%H:%i:%s'))
//        AND news_feed.`end_time` >= NOW()
//        AND CONCAT('{"newFeedId":"',`news_feed`.`id`,'", ',
//            '"newFeedName":"',`news_feed`.`name`,'", ',
//            '"sendingDate":"',DATE_FORMAT(NOW(), '%Y-%m-%d'),'"}'
//        )
//  NOT IN ( SELECT `data` FROM queue )
// EOT;
        $result = DB::select(DB::raw("select * from getNewsFeedFullFillCriteria"));
        if (!empty($result)) {
            $nowDate = Carbon::now();
            foreach ($result as $key => $row) {
                $data[$key]['method'] = 'newsFeedDataBroadCast';
                $data[$key]['data'] = $row->formatedData;
                $data[$key]['status'] = 'Available';
                $data[$key]['created_at'] = $nowDate;
            }
            DB::table('queue')->insert($data);
            //echo json_encode('Data inserted in queue successfully.');
            return response()->json('Data inserted in queue successfully.');
        } else {
            //echo json_encode('No record Founds.');
            return response()->json('No record Founds.');
        }
        exit;
    }





    public function verifyToken(Request $request, tv_jwt $jwt)
    {

        return response()->json(['success' => 'request is ok.'], 200);
    }


//    public function generateJWTToken( Request $request){
//
//        $userId = $request->userId;
//        $userObj = \App\User::find($userId);
//        $token= \JWTAuth::fromUser($userObj);
//        return json_encode(['token'=>$token]);
//    }


    public function generateJwtToken(Request $request)
    {
        $companyKey = $request->companyKey;
        $responseData = ManageJwtToken::generate($companyKey);
        return response()->json($responseData);
    }



    public function generateJWTTokenFromCompanyKey(Request $request, tv_jwt $jwt)
    {

        try {

            $companyKey = $request->companyKey;
            $user_token = $request->user_token;
            $jwtApiKey = $companyKey;//config('common.JWT.apiKey.user');
            $expiryDate = config('common.JWT.tokenExpiryTime');

            $data = [
                'company_key' => $companyKey,
                'user_token' => $user_token,
                "exp" => strtotime(date("Y-m-d H:i:s", strtotime("{$expiryDate} Minute")))
            ];
            $token = $jwt->engagiveCreateToken($jwtApiKey, $data);
            return new JsonResponse(['error' => false, 'token' => $token]);
        } catch (\Exception $exception) {

            return new JsonResponse(['error' => true, 'msg' => $exception->getMessage()]);
        }
    }


    public function throtleTesting(Request $request)
    {

        return response()->json(['success' => 'request is ok.'], 200);
    }

}
