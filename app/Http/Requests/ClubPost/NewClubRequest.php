<?php

namespace App\Http\Requests\ClubPost;

use Illuminate\Foundation\Http\FormRequest;

class NewClubRequest extends FormRequest
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

        ];
    }

    public static function getRules()
    {
        return [
            'club_logo' => 'required|image|max:2048',
            'club_name' => 'required|string',
            'club_social' => 'required'
        ];
    }

    public static function getMessage()
    {
        return [
            'club_logo.required' => 'Logo alanı boş bırakılamaz.',
            'club_logo.image' => 'Logo uzantısı sadece png,jpg,jpeg olabilir.',
            'club_name.max' => 'Logo maksimum 2mb olmalıdır.',
            'club_name.required' => 'Klup ismi boş bırakılamaz',
            'club_name.string' => 'Kulüp ismine geçersiz karakterler girilemez.',
            'club_social.required' => 'Sosyal medya linki boş bırakılamaz.'
        ];
    }


}
