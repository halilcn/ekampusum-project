<?php

namespace App\Http\Requests\DiscussionPost;

use App\Http\Requests\DiscussionInPagePost\DiscussionEditRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DiscussionPostRequest extends FormRequest
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
        if ($this->which == 'newDiscussionSend') {
            return DiscussionEditRequest::getRules();
        } else {
            return [];
        }
    }

    public function messages()
    {
        if ($this->which == 'newDiscussionSend') {
            //DiscussionInPage içindeki form requestten çektim.Kod tekrarı yapmamak için.
            return DiscussionEditRequest::getMessage();
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
