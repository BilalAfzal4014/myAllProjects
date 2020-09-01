<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsFeedTranslation extends Model
{
    protected $table = 'news_feed_translation';

    protected $fillable = [
        'news_feed_id',
        'title',
        'image_url',
        'message',
        'link_text',
        'language',
        'is_deleted',
        'created_by',
        'updated_by',
    ];
}
