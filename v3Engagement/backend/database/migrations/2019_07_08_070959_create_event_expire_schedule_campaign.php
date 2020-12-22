<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class Create_event_expire_schedule_campaign
 * @author  Ikram Hassan
 */
class CreateEventExpireScheduleCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config_database_definer = env('DB_USERNAME');
        $eventName = \App\Components\SQL_SP_VW_Identifier::EVENT_EXPIRE_SCHEDULE_CAMPAIGN;

        Db::connection()->getPdo()->exec("CREATE DEFINER=`$config_database_definer`@`%` EVENT `$eventName`
        ON SCHEDULE
            EVERY 30 SECOND STARTS '2019-07-08 12:37:18'
        ON COMPLETION NOT PRESERVE
        ENABLE
        COMMENT ''
        DO BEGIN

UPDATE campaign set status='expired'   WHERE status='active'  AND   UTC_TIMESTAMP() > end_time AND (end_time IS NOT NULL AND end_time <> '0000-00-00 00:00:00');

UPDATE news_feed set status='expired'  WHERE status='active'  AND   UTC_TIMESTAMP() > end_time AND (end_time IS NOT NULL AND end_time <> '0000-00-00 00:00:00'); 

UPDATE  campaign c INNER JOIN campaign_queues cq ON cq.campaign_id=c.id
SET c.status='expired' 
WHERE lower(c.schedule_type) = 'once' 
AND c.delivery_type='schedule' 
AND c.status='active'
AND LOWER(cq.`status`)='complete';
END" );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * @author  Ikram Hassan
         */
        ///////// drop sp no need to implement
    }
}