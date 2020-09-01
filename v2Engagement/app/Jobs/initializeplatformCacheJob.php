<?php

namespace App\Jobs;

use App\AttributeData;
use App\Engagment\AttributeData\AttributeDataWrapper;
use App\Helpers\CommonHelper;
use App\UserAttribute;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class initializeplatformCacheJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $params=array();   
    private $_job_interval="";
    
   
    public $_job_data=[];
	public $company_id="";
	public $row_id="";
	public $log;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
    	$this->_job_data = $params;
    	$this->company_id = $params["company_id"] ;
		$this->row_id = $params["row_id"] ;
		$this->log = [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $company_id = $this->company_id;
        $row_id = $this->row_id;
        $userAttributes = UserAttribute::where([
            ['company_id', $this->company_id],
            ['row_id', $this->row_id]
        ]);

        if ($userAttributes->count() > 0) {
            $userAttributes = $userAttributes->get()->toArray();

            $rows[$company_id] = [];
            foreach ($userAttributes as $attribute) {
                foreach ($attribute  as $k => $v) {
                    $rows[$attribute['company_id']][$attribute['row_id']][$k] = $v;
                }
            }
        }

        $attributes = AttributeData::where([
            ['company_id', $company_id],
            ['row_id', $row_id],
            ['data_type', 'user']
        ]);

        if ($attributes->count() > 0) {
            $attributes = $attributes->get();

            $details[$company_id] = [];
            foreach ($attributes as $attribute) {
                $details[$attribute->company_id][$attribute->row_id][$attribute->code] = $attribute->value;
            }
        }

        if (!empty($rows)) {
            foreach ($rows as $id => $row) {
                foreach ($row as $row_id => $value) {
                    $secondary = !empty($details[$id][$row_id]) ? $details[$id][$row_id] : [];
                    $primary = $value;

                    $data = array_merge($secondary, $primary);

                    $cache_key = "company_" . $id . "_row_data_" . $row_id;
                    self::removeEntry($cache_key);

                    \Cache::forever($cache_key, \GuzzleHttp\json_encode($data));
                }
            }

        }
    }


    /**
     * Remove entry from cache.
     *
     * @param string $cache_key
     */
    public static function removeEntry($cache_key)
    {
        if (!in_array(config('cache.default'), ['array', 'database', 'file'])) {
            \Artisan::call('cache:clear', [
                '--tags' => $cache_key
            ]);
        }

        \Cache::forget($cache_key);
    }
}
