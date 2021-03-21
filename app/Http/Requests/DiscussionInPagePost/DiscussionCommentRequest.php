<?php

namespace App\Http\Requests\DiscussionInPagePost;

use Illuminate\Foundation\Http\FormRequest;

class DiscussionCommentRequest extends FormRequest
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
            'comment' => ['required', 'max:5000']
        ];
    }

    public static function getMessage()
    {
        return [
            'comment.required' => 'Yorum alanı boş bırakılamaz.',
            'comment.max' => 'Yorum maksimum 2500 karakter olabilir.'
        ];
    }
}
