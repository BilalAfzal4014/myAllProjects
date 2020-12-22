<?php
namespace App\Cache;

use App\AttributeData;
use App\Cache\CacheKeys;
use App\AppGroup;
use App\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppGroupSegmentCache{

    /**
     * build and grab the cache key and save App Group segment cache
     *
     * @param \Illuminate\Database\Eloquent\Model $segment
     * @return bool
     */
    public function saveAppGroupSegmentCache($segment){

        try {
            $app_group_id = $segment->app_group_id;
            $segment_id = $segment->id;

            // load cache key
            $_key = new CacheKeys($app_group_id);
            $cache_key = $_key->generateAppGroupSegmentKey();

            // get cached segments
            $segments = \Cache::get($cache_key);

            if (isset($segments)) {
                $segments = \GuzzleHttp\json_decode($segments, true);
            } else {
                $segments = [];
            }

            if (!in_array($segment_id, $segments)) {
                $segments[] = $segment->id;
                //self::removeEntry($cache_key);
                \Cache::forever($cache_key, \GuzzleHttp\json_encode($segments));
                return true;
            }
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }

    /**
     * build and grab the cache key and save App Group segment rows cache
     *
     * @param \Illuminate\Database\Eloquent\Model $segment
     * @param bool sp
     *
     * @return bool
     */
    public function saveAppGroupSegmentRowsCache($segment, $sp = false){

        try {

            // prepare and parse data
            $app_group_id = $segment->app_group_id;
            $segment_id = $segment->id;
            $status = (isset($segment->is_active)) ? $segment->is_active : 1;

            // Update segment rows for active segments only
            if($status == '1') {

                // load cache key
                $_key = new CacheKeys($app_group_id);
                $cache_key = $_key->generateAppGroupSegmentRowsKey($segment_id);

                // call sp that returns all segment rows
                $rows = \DB::select("CALL sp_get_segment_rowid({$segment_id})");
                $rows = collect($rows)->filter(function ($row) {
                    return isset($row->row_id) ? $row->row_id : null;
                });

                if ($rows->count() > 0) {
                    $segment_rows = $rows->pluck('row_id')->unique()->toArray();

                    //self::removeEntry($cache_key);
                    \Cache::forever($cache_key, \GuzzleHttp\json_encode($segment_rows));

                    return true;
                }
            }
            return true;
        } catch (\Exception $exception) {

            // returns error exception message
            dd($exception->getMessage());
        }
        return false;
    }

    /**
     * get all rows from App Group segment rows cache
     *
     * @param int $app_group_id
     * @param int $segment_id
     *
     * @return array
     */
    public function getAppGroupSegmentRowsCache($app_group_id,$segment_id)
    {
        // load cache key
        $_key = new CacheKeys($app_group_id);
        $cache_key = $_key->generateAppGroupSegmentRowsKey($segment_id);

        // get cached segments
        $segment_rows = \Cache::get($cache_key);
        //self::removeEntry($cache_key);
        if(!isset($segment_rows)){

            $rows = \DB::select("CALL sp_get_segment_rowid({$segment_id})");
            $rows = collect($rows)->filter(function ($row) {
                return isset($row->row_id) ? $row->row_id : null;
            });

            if ($rows->count() > 0) {
                $segment_rows = $rows->pluck('row_id')->unique()->toArray();

                //self::removeEntry($cache_key);
                \Cache::forever($cache_key, \GuzzleHttp\json_encode($segment_rows));

                return $segment_rows;
            }
        }
        else{
            $segment_rows = \GuzzleHttp\json_decode($segment_rows);
        }

        return $segment_rows;
    }

    /**
     * remove from segment cache
     *
     * @param \Illuminate\Database\Eloquent\Model $segment
     * @return bool
     */
    public function deleteFromAppGroupSegmentCache($segment){

        try {

            $app_group_id = $segment->app_group_id;
            $segment_id = $segment->id;

            // load cache key
            $_key = new CacheKeys($app_group_id);
            $cache_key = $_key->generateAppGroupSegmentKey();

            // get cached segments
            $segments = \Cache::get($cache_key);

            if (isset($segments)) {
                $segments = \GuzzleHttp\json_decode($segments, true);
            } else {
                $segments = [];
            }

            $_segments=[];
            if(in_array($segment_id, $segments)){

                foreach($segments as $key=>$val){
                    if( $val != $segment_id){
                        $_segments[] = $val;
                    }
                }

                self::removeEntry($cache_key);
                \Cache::forever($cache_key, \GuzzleHttp\json_encode($_segments));

                return true;
            }
        } catch (\Exception $exception) {

        }

        return false;
    }

    /**
     * remove from segment rows cache
     *
     * @param \Illuminate\Database\Eloquent\Model $segment
     * @return bool
     */
    public function deleteFromAppGroupSegmentRowsCache($segment)
    {
        try{

            $app_group_id = $segment->app_group_id;
            $segment_id = $segment->id;

            // load cache key
            $_key = new CacheKeys($app_group_id);
            $cache_key = $_key->generateAppGroupSegmentRowsKey($segment_id);

            // get cached segments
            $segments = \Cache::get($cache_key);

            self::removeEntry($cache_key);

        } catch (\Exception $exception) {

        }

        return false;
    }

    /**
     * Removes entry from cache.
     *
     * @param string cache_key
     */
    public static function removeEntry($cache_key)
    {
        if (!in_array(config('cache.default'), ['array', 'database', 'file', 'redis'])) {
            \Artisan::call('cache:clear', [
                '--tags' => $cache_key
            ]);
        }

        //\Redis::del($cache_key);
        \Cache::forget($cache_key);
    }
}