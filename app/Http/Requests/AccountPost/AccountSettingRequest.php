<?php

namespace App\Http\Requests\AccountPost;

use Illuminate\Foundation\Http\FormRequest;

class AccountSettingRequest extends FormRequest
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
        return [
            //
        ];
    }

    public static function getRules()
    {
        return [
            'image' => ['mimes:jpeg,jpg,png,gif', 'max:2048', 'nullable'],
            'name_surname' => ['required', 'max:22', 'min:4', 'string'],
            'about' => ['max:1500'],
        ];
    }

    public static function getMessage()
    {
        return [
            'image.mimes' => 'Fotoğraf uzantısı sadece jpeg,jpg,png,gif olmalıdır.',
            'image.max' => 'Fotoğraf boyutu maksimum 2mb olmalıdır.',
            'name_surname.required' => 'Ad-Soyad alanı boş bırakılamaz.',
            'name_surname.max' => 'Ad-Soyad alanı maksimum 22 karakter olmalıdır.',
            'name_surname.min' => 'Ad-Soyad alanı minimum 4 karakter olmalıdır.',
            'name_surname.string' => 'Ad-Soyad alanına geçersiz karakterler girilemez.',
            'username.required' => 'Kullanıcı adı alanı boş bırakılamaz.',
            'about.max' => 'Hakkında kısmı maksimum 1500 karakter olmalıdır.'
        ];
    }
}
