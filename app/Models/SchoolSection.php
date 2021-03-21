<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSection extends Model
{
    use HasFactory;

    protected $table = 'school_sections';
    protected $fillable = [
        'name', 'school_id'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function lessons()
    {
        return $this->belongsToMany(SchoolLesson::class, 'school_section_school_lesson');
    }

}
