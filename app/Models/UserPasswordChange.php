<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPasswordChange extends Model
{
    protected $table = 'users_password_change';
    protected $fillable = [
        'user_id', 'random_link'
    ];
}
