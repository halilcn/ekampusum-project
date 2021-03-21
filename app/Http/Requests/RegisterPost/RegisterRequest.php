<?php

namespace App\Http\Requests\RegisterPost;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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

    public static function getRules()
    {
        return [
            'name_surname' => ['required', 'max:22', 'min:4', 'string'],
            'username' => ['required', 'max:12', 'min:3', 'unique:users', 'string', 'not_regex:/@/', 'regex:/^\S*$/u'],
            'register_email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
            'password_2' => ['required', 'same:password'],
            'conditions' => ['required']
        ];
    }

    public static function getMessage()
    {
        return [
            'name_surname.required' => 'Ad-Soyad alanı boş bırakılamaz.',
            'name_surname.max' => 'Ad-Soyad alanı maksimum 22 karakter olmalıdır.',
            'name_surname.min' => 'Ad-Soyad alanı minimum 4 karakter olmalıdır.',
            'name_surname.string' => 'Ad-Soyad alanına geçersiz karakterler girilemez.',
            'username.required' => 'Kullanıcı adı alanı boş bırakılamaz.',
            'username.max' => 'Kullanıcı adı maksimum 12 karakter olmalıdır.',
            'username.min' => 'Kullanıcı adı minimum 3 karakterli olmalıdır.',
            'username.unique' => 'Bu kullanıcı adı daha önce alınmış.',
            'username.string' => 'Kullanıcı adına geçersiz karakterler girilemez.',
            'username.not_regex' => 'Kullanıcı adında "@" işareti kullanılamaz.',
            'username.regex' => 'Kullanıcı adında boşluk bırakılamaz.',
            'register_email.required' => 'E-posta alanı boş bırakılamaz.',
            'register_email.email' => 'Lütfen geçerli bir e-posta adresi giriniz.',
            'register_email.unique' => 'Bu e-posta adresi daha önce kullanılmış.',
            'password.required' => 'Şifre alanı boş bırakılamaz.',
            'password.min' => 'Şifre minimum 6 karakterli olmalıdır.',
            'password_2.required' => 'Şifre alanı boş bırakılamaz.',
            'password_2.same' => 'Şifre alanları aynı olmalıdır.',
            'conditions.required' => 'Kullanıcı sözleşmesine onay vermelisiniz.',
        ];
    }

    /*Verilerimiz rules'a girmeden burada hazırlanabilir.
    protected function prepareForValidation()
    {
        $this->merge([
            'is_draft' => $this->input('is_draft', false),
        ]);
    }*/

    /*:attribute isimlerini özelleştirmemizi sağlar
    public function attributes()
    {
        return [
            'title'    => 'Başlık',
            'body'     => 'İçerik',
            'is_draft' => 'Taslak',
        ];
    } */

    /*Validatördün sonra yaptırmak istediğimiz ek işlemler var ise
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (mb_stripos($this->title, 'tiktok') !== false || mb_stripos($this->title, 'tik tok') !== false) {
                $validator->errors()->add('title', 'Başlıkta "tiktok" veya "tik tok" kelimesi kullanılmamlı.');
            }
        });
    } */

    /*validation başarısız olduğunda default olarak geri gider.Bunu customize etmek istersek.
    public function getRedirectUrl()
    {
        return route('articles.index');
        // veya
        return '/articles';
    }*/

    /*Normalde hata olduğunda ValidationException hatası fıraltılır.Hatadan önce özelleştirme yapmak istersek.
    protected function failedValidation(Validator $validator)
    {
        // Validation hatalarını loglara yaz.
        Log::debug(json_encode($validator->errors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        // Sonra normal akışa devam et.
        parent::failedValidation($validator);
    } */

    /*Validator success olduğunda yapılacak işlemler.
     protected function passedValidation()
     {
         //
     }*/

    /*Authorizatio işlemi başarısız olduğunda.
    protected function failedAuthorization()
    {
        // Authorization işleminin başarısız olduğunu ve nerede gerçekleştiğini loglara yaz.
        Log::debug(__CLASS__.': Authorization başarısız.');
        // Sonra normal akışa devam et.
        parent::failedAuthorization();
    }*/
}
