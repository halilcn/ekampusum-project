<?php

namespace App\Http\Requests\ConfessionPost;

use Illuminate\Foundation\Http\FormRequest;

class ConfessionUserSettingRequest extends FormRequest
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
            'username' => 'required|max:12|min:4',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2048|nullable'
        ];
    }

    public static function getMessage()
    {
        return [
            'username.required' => 'İsim boş bırakılamaz',
            'username.max' => 'isim maksimum 12 karakter olabilir.',
            'username.min' => 'isim minimum 4 karakter olabilir.',
            'image.mimes' => 'Fotoğraf uzantısı jpeg,jpg,png,gif olabilir.',
            'image.max' => 'Fotoğraf dosya boyutu maksimum 2mb olabilir.',
        ];
    }
}
