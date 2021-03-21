<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SchoolLessonFile extends Model
{
    use HasFactory;

    protected $table = 'school_lesson_files';
    protected $fillable = [
        'lesson_id', 'user_id', 'period', 'file_path', 'file_size'
    ];

    public function user()
    {
        return $this->belongsTo(users::class);
    }
}
