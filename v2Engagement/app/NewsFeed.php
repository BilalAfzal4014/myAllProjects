<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{

    use CompileTags;
    protected $table = "news_feed";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'message',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function impressions()
    {
        return $this->hasMany(NewsFeedImpression::class, 'news_feed_id');
    }

}
