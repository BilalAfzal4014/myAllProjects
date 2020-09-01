<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Segment;
use App\Cache\AppGroupSegmentCache;

class RemoveSegmentCacheEvent extends  Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $segment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Segment $segment)
    {
        //
        $this->segment = $segment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
