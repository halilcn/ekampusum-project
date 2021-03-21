<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class users extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'username', 'name_surname', 'password', 'image', 'register_email', 'register_email_confirmation', 'school_email', 'school_email_confirmation'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function notifications(): HasOne
    {
        return $this->hasOne(Notification::class, 'user_id', 'id');
    }

    public function clubsMember(): HasMany
    {
        return $this->hasMany(ClubMember::class, 'user_id', 'id');
    }

    public function lastDiscussions(): HasMany
    {
        return $this->hasMany(Discussion::class, 'user_id', 'id')->orderBy('created_at', 'DESC');
    }


}
