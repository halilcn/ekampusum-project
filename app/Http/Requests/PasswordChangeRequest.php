<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChangeRequest extends FormRequest
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
            'real_password' => ['required'],
            'new_password' => ['required', 'same:new_password_repeat', 'min:6'],
            'new_password_repeat' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'real_password.required' => 'Mevcut şifre alanı boş bırakılamaz.',
            'new_password.required' => 'Yeni şifre alanı boş bırakılamaz.',
            'new_password.same' => 'Yeni şifreler aynı değil.',
            'new_password.min' => 'Yeni şifre minimum 6 karakter olmalıdır.',
            'new_password_repeat.required' => 'Yeni şifre alanı boş bırakılamaz.'
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
