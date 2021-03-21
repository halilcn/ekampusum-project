<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscussionComment extends Model
{
    protected $table = 'discussion_comments';
    protected $fillable = [
        'discussion_id', 'user_id', 'message', 'vote'
    ];

    //Mutators
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans(Carbon::now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class);
    }

}
