<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $fillable = [
        'email',
        'firebase_key',
        'device_key',
        'payload',
        'sent',
        'sent_at',
        'job',
        'status',
        'started_at',
        'ended_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function log()
    {
        return $this->hasOne(NotificationsLogs::class);
    }
}
