<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnouncementAndNews extends Model
{
    protected $table = 'announcements_and_news';
    protected $fillable = [
        'user_id', 'club_id', 'title', 'title_image', 'subject', 'image', 'link', 'view_count'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(AnnouncementAndNewsComment::class, 'announcements_and_news_id', 'id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
