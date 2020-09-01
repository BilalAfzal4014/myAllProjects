<?php

namespace App\Components;

class ScheduleJobInQueue
{
    /**
     * Schedule an item for processing in jobs table.
     *
     * @param string $class
     * @param string $queue
     * @param int    $times
     * @param int    $interval
     */
    public static function process($class, $queue, $times, $interval)
    {
        $delay = 60*$interval;
        for ($i=1; $i<=$times; $i++) {
            dispatch(
                (new $class)->onQueue($queue)->delay($delay)
            );
            $delay = $delay*$interval;
        }
    }
}