<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolLessonNoteForm extends Model
{
    use HasFactory;

    protected $table = 'school_lesson_notes_form';
    protected $fillable = [
        'user_id', 'section_university_name', 'lesson_name', 'period', 'file_path'
    ];
}
