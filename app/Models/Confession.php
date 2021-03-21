<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Confession extends Model
{
    protected $table = 'confessions';
    protected $fillable = [
        'confession_user_id', 'confession_content'
    ];

    //Mutators
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans(Carbon::now());
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(ConfessionUser::class, 'confession_user_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ConfessionComment::class);
    }

}
