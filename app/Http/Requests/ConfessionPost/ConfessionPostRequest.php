<?php

namespace App\Http\Requests\ConfessionPost;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ConfessionPostRequest extends FormRequest
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
        if ($this->which == 'confessionUserSettings') {
            return ConfessionUserSettingRequest::getRules();
        } else if ($this->which == 'confessionPost') {
            return ConfessionRequest::getRules();
        } else if ($this->which == 'commentPost') {
            return ConfessionCommentRequest::getRules();
        } else {
            return [];
        }
    }

    public function messages()
    {
        if ($this->which == 'confessionUserSettings') {
            return ConfessionUserSettingRequest::getMessage();
        } else if ($this->which == 'confessionPost') {
            return ConfessionRequest::getMessage();
        } else if ($this->which == 'commentPost') {
            return ConfessionCommentRequest::getMessage();
        } else {
            return [];
        }
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
