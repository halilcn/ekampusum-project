<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'post_type', 'post_id'
    ];

    //Mutators
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans(Carbon::now());
    }

    //Scope
    public function scopePostMorpToWith($query)
    {
        return $query->with('post', function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Discussion::class => [
                    'user' => function ($query) {
                        $query->select('id', 'username', 'image', 'school_email_confirmation');
                    }
                ],
                Event::class => [
                    'user' => function ($query) {
                        $query->select('id', 'username', 'image', 'school_email_confirmation');
                    },
                    'club'
                ],
                AnnouncementAndNews::class => [
                    'user' => function ($query) {
                        $query->select('id', 'username', 'image', 'school_email_confirmation');
                    },
                    'club'
                ],
                Confession::class => [
                    'users'
                ]
            ])
                ->morphWithCount([
                    Discussion::class => 'comments',
                    Event::class => 'comments',
                    AnnouncementAndNews::class => 'comments',
                    Confession::class => 'comments'
                ]);
        });
    }

    //Scope
    public function scopeCommentWith($query, $request)
    {
        return $query->with([
            'post',
            'post.comments' => function ($query) use ($request) {
                $query->latest('id')
                    ->when($request->lastId != '0', function ($query) use ($request) {
                        $query->where('id', '<', $request->lastId);
                    })
                    ->take(5);
            },
            'post.comments.user' => function ($query) {
                $query->select('username', 'image', 'id');
            }
        ]);
    }

    public function post(): MorphTo
    {
        Relation::morphMap([
            'discussion' => Discussion::class,
            'event' => Event::class,
            'announcement' => AnnouncementAndNews::class,
            'confession' => Confession::class
        ]);

        return $this->morphTo();
    }
}
