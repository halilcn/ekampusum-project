<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConfessionComment extends Model
{
    protected $table = 'confessions_comments';
    protected $fillable = [
        'confession_id', 'confession_user_id', 'message'
    ];

    //Mutators
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans(Carbon::now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(ConfessionUser::class, 'confession_user_id', 'id');
    }
}
