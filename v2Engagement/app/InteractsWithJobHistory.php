<?php

namespace App;

use Carbon\Carbon;
use App\Campaign;
use App\CampaignTracking;

trait InteractsWithJobHistory
{
    private function addToHistory()
    {
        switch ($this->type) {
            case 'campaign':              
                $this->track = $this->model->tracks()->create($this->data);    
                break;
            case 'quick_notify':
                $this->track = Notification::create($this->data);
                break;
        }

        $this->track->job = str_replace("App\\Jobs\\", "", self::class);
        $this->track->save();
    }

    /**
     * Update job start time.
     */
    private function updateStart()
    {
        $this->track->status = 'executing';
        $this->track->started_at = Carbon::now();
        $this->track->save();
    }

    /**
     * Update job finish time.
     */
    private function updateComplete()
    {
        $this->track->status = 'completed';
        $this->track->ended_at = Carbon::now();
        $this->track->save();
    }

    /**
     * Update finish time if job fails.
     */
    private function updateFailed()
    {
        $this->track->status = 'failed';
        $this->track->ended_at = Carbon::now();
        $this->track->save();
    }
}