<?php

namespace App\Http\Requests\ConfessionPost;

use Illuminate\Foundation\Http\FormRequest;

class ConfessionRequest extends FormRequest
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
            'confession' => 'required|max:2500'
        ];
    }

    public static function getMessage()
    {
        return [
            'confession_content.required' => 'İtiraf alanı boş bırakılamaz.',
            'confession_content.max' => 'Maksimum 2500 karakter olabilir'
        ];
    }
}
