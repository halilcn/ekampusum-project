<?php

namespace App\Http\Requests\ConfessionPost;

use Illuminate\Foundation\Http\FormRequest;

class ConfessionCommentRequest extends FormRequest
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
            'comment' => 'required|max:500|nullable',
            'confession_id' => 'integer'
        ];
    }

    public static function getMessage()
    {
        return [
            'comment.required' => 'Yorum alanı boş bırakılamaz.',
            'comment.max' => 'Yorum maksimum 500 karakter olabilir.',
            'comment.nullable' => 'Yorum alanı boş bırakılamaz.',
            'confession_id.integer' => 'Bir hata oluştu.'
        ];
    }
}
