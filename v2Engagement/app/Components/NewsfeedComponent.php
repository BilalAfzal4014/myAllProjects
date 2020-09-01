<?php

namespace App\Components;

use App\NewsFeed;

class NewsfeedComponent
{
    public function dispatch($queueId, $notification_type = 'all')
    {
        try {
            $queueRow = \DB::table('queue')
                ->select('id', 'data')
                ->where([['id', '=', $queueId],['status', '!=', 'Complete']])
                ->first();

            if (empty( $queueRow )) {
                throw new \Exception("No data found/queue item doesn't exists.");
            }

            $data = json_decode($queueRow->data, true);
            if (empty($data['newFeedId'])) {
                throw new \Exception("No associated newsfeed exists for this queue.");
            }

            $newsFeedId = $data['newFeedId'];
            $newsfeed = NewsFeed::find($newsFeedId);
            if (empty($newsfeed->id)) {
                throw new \Exception("Newsfeed not found.");
            }

            \DB::table('queue')
                ->where('id', $queueId)
                ->update(['status' => 'Processing']);

            $segment = \DB::table('segment as s')
                ->where('s.id', $newsfeed->segment_id)
                ->first();

            if (empty($segment)) {
                throw new \Exception("No segment details found!");
            }

            $queryStr = trim($segment->key_value_sql);

            $rowIds = \DB::table('attribute_data as ad')
                ->select('ad.row_id')
                ->whereRaw($queryStr)
                ->groupBy('row_id')
                ->get();

            if (empty($rowIds)) {
                throw new \Exception("No user found against this criteria!");
            }

            $rowIds = array_pluck($rowIds, 'row_id');

            if ($notification_type == 'all') {
                $code_clause = ['fire_base_key', 'firebase_key', 'device_token'];
            } elseif ($notification_type == 'push') {
                $code_clause = ['device_token'];
            } elseif ($notification_type == 'inapp') {
                $code_clause = ['fire_base_key', 'firebase_key'];
            }

            $users = \DB::table('attribute_data as ad')
                ->whereIn('code', $code_clause)
                ->whereIn('row_id', $rowIds)
                ->get();

            return [
                'users'     => $users,
                'newsfeed'  => $newsfeed
            ];
        } catch (\Exception $exception) {
            return [
                'type'      => 'error',
                'message'   => $exception->getMessage()
            ];
        }
    }
}