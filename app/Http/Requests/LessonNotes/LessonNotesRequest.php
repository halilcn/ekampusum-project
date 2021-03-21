<?php

namespace App\Http\Requests\LessonNotes;

use Illuminate\Foundation\Http\FormRequest;

class LessonNotesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public static function getRules()
    {
        return [
            'section_university_name' => ['required'],
            'lesson_name' => ['required'],
            'period' => ['required'],
            'file' => ['required', 'file', 'max:15000']

        ];
    }

    public static function getMessage()
    {
        return [
            'section_university_name.required' => 'Bölüm Adı ve Üniversite Adı boş bırakılamaz.',
            'lesson_name.required' => 'Ders Adı boş bırakılamaz.',
            'period.required' => 'Dönem boş bırakılamaz.',
            'file.required' => 'Dosya boş gönderilemez.',
            'file.max' => 'Dosya maksimum 20mb olabilir.Lütfen sıkıştırıp göndermeyi deneyin.',
        ];
    }
}
