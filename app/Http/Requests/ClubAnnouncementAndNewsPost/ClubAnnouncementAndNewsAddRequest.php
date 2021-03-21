<?php

namespace App\Http\Requests\ClubAnnouncementAndNewsPost;

use Illuminate\Foundation\Http\FormRequest;

class ClubAnnouncementAndNewsAddRequest extends FormRequest
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
            'title' => ['required'],
            'title_image' => ['image', 'max:2048'],
            'subject' => ['required'],
            'images.*' => ['image', 'max:2048']
        ];
    }

    public static function getMessage()
    {
        return [
            'title.required' => 'Başlık alanı boş bırakılamaz.',
            'title_image.image' => 'Kapak fotoğrafı uzantısı sadece png,jpg,jpeg,gif olmalıdır.',
            'title_image.max' => 'Kapak fotoğrafı boyutu maksimum 2mb olmalıdır.',
            'subject.required' => 'İçerik boş bırakılamaz.',
            'images.image' => 'Fotoğraf uzantısı sadece png,jpg,jpeg,gif olmalıdır.',
            'images.max' => 'Fotoğraf maksimum boyutu 2mb olmalıdır.'
        ];
    }
}
