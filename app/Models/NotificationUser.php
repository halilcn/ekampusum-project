<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationUser extends Model
{
    protected $table = 'notifications_user';
    protected $fillable = [
        'user_id', 'event_user_id', 'notification_id', 'notification_view', 'notification_information'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class, 'event_user_id', 'id');
    }

    //belongsTo
    public function discussion()
    {
        return $this->belongsTo(discussion::class, 'notification_information', 'id');
    }
}
