<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ClubSettingRequest extends FormRequest
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
            'image' => ['nullable', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'background_image' => ['nullable', 'mimes:jpg,jpeg,png,gif', 'max:4096'],
            'introduction_text' => ['nullable', 'max:5000'],
            'phone' => ['nullable', 'digits_between:9,11'],
            'email' => ['nullable', 'email'],
            'facebook' => ['nullable', 'max:150'],
            'instagram' => ['nullable', 'max:150'],
            'twitter' => ['nullable', 'max:150'],
            'linkedin' => ['nullable', 'max:150'],
            'web_url' => ['nullable', 'max:150']
        ];
    }

    public function messages()
    {
        return [
            'image.mimes' => 'Profil fotoğrafı uzantısı sadece jpeg,jpg,png,gif',
            'image.max' => 'Profil fotoğrafı maksimum 2mb olabilir.',
            'background_image.mimes' => 'Arkaplan fotoğrafı uzantısı sadece jpeg,jpg,png,gif',
            'background_image.max' => 'Arkaplan fotoğrafı maksimum 4mb olabilir.',
            'introduction_text.max' => 'Tanıtım yazısı maksimum 5000 karakter olabilir.',
            'phone.digits_between' => 'Geçerli bir telefon nuamarası giriniz.',
            'email.email' => 'Girilen email adresi geçersiz.',
            'facebook.max' => 'Facebook linki maksimum 150 karakter olabilir.',
            'instagram.max' => 'İnstagram linki maksimum 150 karakter olabilir.',
            'linkedin.max' => 'Linkedin linki maksimum 150 karakter olabilir.',
            'web_url.max' => 'İnternet Sitesi maksimum 150 karakter olabilir.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors()->first(),
                'status' => '0'
            ])
        );
    }
}
