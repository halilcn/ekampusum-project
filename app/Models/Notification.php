<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id', 'discussion_new_comment_mail', 'discussion_new_comment', 'new_events'
    ];

    public function user()
    {
        return $this->hasOne(users::class, 'id', 'user_id');
    }

}
