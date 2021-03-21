<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PasswordForgetNewPasswordRequest extends FormRequest
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
            'new_password' => ['required', 'min:6'],
            'new_password_repeat' => ['required', 'same:new_password'],
        ];
    }

    public function messages()
    {
        return [
            'new_password.required' => 'Yeni Şifre alanı boş bırakılamaz.',
            'new_password.min' => 'Yeni Şifre alanı minimum 6 karakter olabilir.',
            'new_password_repeat.required' => 'Yeni Şifre Tekrar alanı boş bırakılamaz.',
            'new_password_repeat.same' => 'Şifreler birbirinden farklı.',
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
