<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Club extends Model
{
    protected $table = 'clubs';
    protected $fillable = [
        'club_name', 'club_link'
    ];

    public function settings(): HasOne
    {
        return $this->hasOne(ClubSetting::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ClubMember::class);
    }

    public function clubAnnouncementsAndNews(): HasMany
    {
        return $this->hasMany(AnnouncementAndNewsComment::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function invitation(): HasMany
    {
        return $this->hasMany(ClubInvitation::class);
    }
}
