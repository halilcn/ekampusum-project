<?php

namespace App\Http\Requests\DiscussionInPagePost;

use Illuminate\Foundation\Http\FormRequest;

class DiscussionEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public static function getRules()
    {
        return [
            'title' => ['required', 'min:4', 'max:200'],
            'subject' => ['required', 'min:4', 'max:5000'],
        ];
    }

    public static function getMessage()
    {
        return [
            'title.required' => 'Başlık alanı boş bırakılamaz.',
            'title.min' => 'Başlık çok kısa.',
            'title.max' => 'Başlık çok uzun.',
            'subject.required' => 'Konu alanı boş bırakılamaz.',
            'subject.min' => 'Konu çok kısa.',
            'subject.max' => 'Konu çok uzun.'
        ];
    }
}
