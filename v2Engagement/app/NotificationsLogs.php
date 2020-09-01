<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationsLogs extends Model
{
    //
    protected $table = 'notifications_logs';

    protected $fillable = [
        'notification_id',
        'status',
        'message'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
