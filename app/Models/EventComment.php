<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventComment extends Model
{
    protected $table = 'events_comments';
    protected $fillable = [
        'user_id', 'events_id', 'message'
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
