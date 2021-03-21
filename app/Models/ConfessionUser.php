<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfessionUser extends Model
{
    protected $table = 'confession_user';
    protected $fillable = [
        'user_id', 'username', 'image'
    ];
}
