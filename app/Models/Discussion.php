<?php

namespace App\Models;

use App\Models\DiscussionComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;


class Discussion extends Model
{
    protected $table = 'discussion';
    protected $fillable = [
        'user_id', 'title', 'subject', 'link', 'created_at', 'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(DiscussionComment::class);
    }

    public function post(): MorphMany
    {
        return $this->morphMany(Post::class, 'post');
    }


}
