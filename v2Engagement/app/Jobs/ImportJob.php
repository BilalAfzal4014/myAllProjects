<?php

namespace App\Jobs;

use App\Engagment\AttributeData\AttributeDataWrapper;
use App\Helpers\CommonHelper;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class ImportJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $params=array();   
    private $_job_interval="";
    
   
    public $_job_data=[];
    public $job_file_name="";
	public $company_id="";
	public $import_data_id="";
	public $log;

    /**
     * @var $attributeDataWrapper AttributeDataWrapper
     */
	public $attributeDataWrapper;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params, AttributeDataWrapper $attributeDataWrapper)
    {
    	$this->_job_data = $params;
    	$this->company_id = $params["company_id"] ;
		$this->job_file_name = $params["job_file_name"] ;		
		$this->import_data_id = $params["import_data_id"] ;
		$this->attributeDataWrapper = $attributeDataWrapper ;
		$this->log = [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo 'Received Import Data Job# : '. $this->job->getJobId();
        echo 'company_'.$this->company_id.'/'.$this->job_file_name;

        /**
         * @var  $disk FilesystemAdapter
         */
        $disk = \Storage::disk("s3");

        $directory = 'company_'.$this->company_id.'/'.'attribute_file_'.$this->import_data_id;
        try{

            $now = Carbon::now();



            $items = \GuzzleHttp\json_decode(
                $disk->get($directory.'/'.$this->job_file_name),
                true
            );
            
            foreach ($items as $item) {

                /*in case of date time object*/
                foreach ($item as $key=>$value){
                    if(is_array($value) && isset($value['date'])){

                        $item[$key] = $value['date'];
                    }
                }

                list($appNameListing,$appIds) = CommonHelper::getAppList($this->company_id);
                $item['app_name'] = trim($item['app_name']);
                $validator = Validator::make($item, [
                    'app_id' => 'required',
                    'user_id' => 'required',
                    'email' => 'required',
                    'device_type' => 'required',
                    'app_name' => 'required|in:'.implode(',',$appNameListing),
                ]);
                if (empty($validator->errors()->all())) {

                    $item['is_import'] = 1;
                    if(!isset($item['is_active']))
                    $item['is_active'] = 1;

                    $this->attributeDataWrapper->saveAttributeData($this->company_id, $item, true);
                }

            }

            echo 'company_'.$this->company_id.'/'.$this->job_file_name;
            $currentTime = Carbon::now();
            $timeTaken = $currentTime->diffInSeconds($now);
            echo "total time ---- ".$timeTaken;
        }  catch (\Exception $e) {
            $this->log[] = array(
                'FunctionName' => 'Exception in ImportJob.php',
                'LogInfo' => $e->getMessage().' Line #: '. $e->getLine(),
            );
                
            \Log::info($this->job_file_name.'------------------------'.$e->getMessage().'----------'.$e->getTraceAsString());
            return false;
		}

        $disk->delete($directory.'/'.$this->job_file_name);
        $fileCount = count($disk->allFiles($directory));
        echo '-----------------------------------------------'.$fileCount;
        if($fileCount == 0){

            $disk->deleteDirectory($directory);
            Artisan::call('attribute:cache',[
                '--company'=>$this->company_id
            ]);
        }
    }
}
