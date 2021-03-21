<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = [
        'user_id', 'club_id', 'title', 'title_image', 'subject', 'image', 'date', 'location', 'link', 'view_count'
    ];

    protected $dates = ['date'];

    //mutators
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->subHours(3);
    }

    //mutators
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->addHours(3);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class);
    }

    // events_id ?? :(
    public function comments(): HasMany
    {
        return $this->hasMany(EventComment::class, 'events_id', 'id');
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

}
