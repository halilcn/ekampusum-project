<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    protected $table = 'confirmation';
    protected $fillable = [
        'user_id', 'confirmation_key', 'confirmation_which'
    ];
    public $timestamps = false;
}
