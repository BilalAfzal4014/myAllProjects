<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommandController extends Controller
{
    public function commandsCache(Request $request)
    {
        $groupId = $request->get('group_id');

        $commands = [];

        array_push($commands, \Config::get('cache.prefix') . ':app_group_id_' . $groupId . '_segment_ID_rows');
        array_push($commands, \Config::get('cache.prefix') . ':app_group_id_' . $groupId . '_segments');
        array_push($commands, \Config::get('cache.prefix') . ':app_group_id_' . $groupId . '_row_id_ID');
        array_push($commands, \Config::get('cache.prefix') . ':campaign_ID_segments');
        array_push($commands, \Config::get('cache.prefix') . ':campaign_tracking_campaign_id_ID_row_id_ID_language_CODE_variant_ID');

        return response()->json($commands);
    }

    public function commandData(Request $request)
    {
        $command = $request->get('command');

        $key = str_replace(\Config::get('cache.prefix').':', '', $command);
        if (\Cache::has($key)) {
            $data = \Cache::get($key);

            if(empty($data)) {
                return response()->json('No data found.');
            }

            return response()->json($data);
        }

        return response()->json('No data found.');
    }
}
