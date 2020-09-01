<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsFeedImpression extends Model
{
    protected $table = 'news_feed_impressions';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'news_feed_id',
        'location_id',
        'platform',
        'viewed',
        'created_date'
    ];
}
