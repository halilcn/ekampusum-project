<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'username_or_email' => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'username_or_email.required' => 'Kullanıcı Adı veya E-posta girmeden giriş yapılamaz.',
            'password.required' => 'Şifre alanı boş bırakılamaz.'
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
