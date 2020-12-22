<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 2019-02-28
 * Time: 12:14
 */

namespace App\Http\Controllers\Api;


use App\Components\ParseResponse;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BackendController  extends Controller
{
    public function update_tracking(Request $request, $key =null)
    {
        if($key){
            $params['key'] = $key;
            DB::statement( 'UPDATE
                            campaign_tracking ct__
                            SET
                            ct__.viewed = ct__.viewed+1,
                            ct__.viewed_at = CURRENT_TIMESTAMP
                            WHERE
                            ct__.track_key = :key',$params);
            header('Content-Type: image/gif');
            echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');die;

        }else{
            echo "Track key not found";die();
        }
        dd($key,$request);
    }
}