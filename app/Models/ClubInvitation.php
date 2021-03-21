<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubInvitation extends Model
{
    protected $table = 'club_invitation';
    protected $fillable = [
        'user_id', 'club_id', 'event_user_id', 'invitation_who'
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo(users::class);
    }
}
