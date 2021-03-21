<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubSetting extends Model
{
    protected $table = 'clubs_settings';
    protected $fillable = [
        'club_id', 'image', 'background_image', 'introduction_text', 'phone', 'email', 'social_media', 'web_url'
    ];
}
