<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SchoolLesson extends Model
{
    use HasFactory;

    protected $table = 'school_lessons';
    protected $fillable = [
        'lesson_name'
    ];

    public function files(): HasMany
    {
        return $this->hasMany(SchoolLessonFile::class, 'lesson_id', 'id');
    }

}
