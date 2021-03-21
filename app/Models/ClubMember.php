<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubMember extends Model
{
    protected $table = 'clubs_members';
    protected $fillable = [
        'club_id', 'user_id', 'authority', 'role', 'role_name'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class);
    }

    public function clubs(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id', 'id');
    }
}
