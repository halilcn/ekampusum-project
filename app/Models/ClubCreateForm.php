<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubCreateForm extends Model
{
    protected $table = 'club_create_form';
    protected $fillable = [
        'username', 'email', 'club_logo', 'club_name', 'club_social'
    ];
}
