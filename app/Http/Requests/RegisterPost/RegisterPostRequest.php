<?php

namespace App\Http\Requests\RegisterPost;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterPostRequest extends FormRequest
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

    //Bir controlle içinde birden fazla işlem olduğu için, kuralları ayırmam gerek.Bu şekilde hangi işlem yapılacaksa
    //onun kurallarını  çekiyorum.

    public function rules()
    {
        if ($this->which == 'register') {
            return RegisterRequest::getRules();
        } else {
            return [];
        }
    }

    public function messages()
    {
        if ($this->which == 'register') {
            return RegisterRequest::getMessage();
        } else {
            return [];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors()->first(),
                'status' => '0'
            ])
        );

        // parent::failedValidation($validator);
        //Default olarak kullanılanda 422 status koduyla dönüyor.Standart ve doğru olanı default olarak kullanmaktır.
        //parent::failedValidation ile oynamadım.Laravelin dosyalarına ellememek için.
    }
}
