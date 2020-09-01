<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ArchiveCampaignTrackingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to archive campaign tracking DB and Cache Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $campaignTrackingIDs = [];

        // getting required data from DB
        $trackingIDs = \Illuminate\Support\Facades\DB::select("SELECT cmptrc.id, cmptrc.campaign_id, cmp.company_id
            from campaign_tracking as cmptrc
            INNER JOIN campaign as cmp
             ON cmptrc.campaign_id = cmp.id
            WHERE cmptrc.created_at <= (DATE_FORMAT(UTC_TIMESTAMP(),'%Y-%m-%d %H:%i:%s') - INTERVAL 6 MONTH)");


        if (!empty($trackingIDs)) {

            \Illuminate\Support\Facades\DB::beginTransaction();

            try {
                // removing cache data
                foreach ($trackingIDs as $tackingID) {
                    $cacheData = \Cache::forget('company_' . $tackingID->company_id . '_campaign_' . $tackingID->campaign_id . '_tracking');
                    array_push($campaignTrackingIDs, $tackingID->id);
                }
                echo 'These campaigns tracking data are being deleted:';
                print_r($campaignTrackingIDs);

                // removing DB data
                \Illuminate\Support\Facades\DB::delete("DELETE FROM campaign_tracking where id IN (" . implode(',', $campaignTrackingIDs) . ")");
                \Illuminate\Support\Facades\DB::delete("DELETE FROM campaign_tracking_logs where campaign_tracking_id IN (" . implode(',', $campaignTrackingIDs) . ")");

                \Illuminate\Support\Facades\DB::commit();

                echo ' All Done';
            } catch (\Exception $e) {
                print_r('Error occurred: ' . $e->getMessage());
                \Illuminate\Support\Facades\DB::rollback();
            }
        } else {
            echo 'No Data found';
        }
    }
}
