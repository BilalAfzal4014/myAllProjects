<?php

namespace App\Engagment\Queue;

use App\Engagment\Campaign\QueueHandler;

class QueueWrapper
{
    protected $queueHandler;

    public function __construct(QueueHandler $queueHandler)
    {
        $this->queueHandler = $queueHandler;
    }

    public function getQueue()
    {
        return $this->queueHandler->getQueueData();
    }
}