<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementAndNewsComment extends Model
{
    protected $table = 'announcements_and_news_comments';
    protected $fillable = [
        'user_id', 'announcements_and_news_id', 'message'
    ];

    // Mutators
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans(Carbon::now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class);
    }
}
