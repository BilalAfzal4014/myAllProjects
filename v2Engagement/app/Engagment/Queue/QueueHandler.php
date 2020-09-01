<?php

namespace App\Engagment\Queue;

use App\Queue;

class QueueHandler
{
    protected $campaignModal;

    public function __construct( Queue $queue )
    {
        $this->queueModel = $queue;
    }

    public function getQueueData()
    {

    }


}