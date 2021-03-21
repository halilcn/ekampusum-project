<?php

namespace App\Listeners;

use App\Models\Confirmation;
use App\Models\users;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class emailConfirmationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */

    //Spagetti Code :/
    public function handle()
    {
        //Spagetti Code.Performans kaybı var.İncelenip düzeltilmeli
        $confirmation_key = md5(microtime());
        if (Auth::check()) {
            try {
                //Register Email Confirmation Check
                $userRegisterEmailConfirmation = users::query()
                    ->where([
                        'id' => Auth::user()->id,
                        'register_email_confirmation' => '0'
                    ])
                    ->exists();

                if ($userRegisterEmailConfirmation) {
                    //Register Email Send

                    $userRegisterEmailConfirmation = Confirmation::query()
                        ->where([
                            'user_id' => Auth::user()->id,
                            'confirmation_which' => 'register_email'
                        ]);

                    //User Confirmation Random Code Check
                    if ($userRegisterEmailConfirmation->exists()) {
                        $userRegisterEmailConfirmation->delete();
                    }

                    //Email Send
                    Mail::send("email/registerEmailConfirmation", ['nameSurname' => Auth::user()->name_surname, 'confirmationKey' => $confirmation_key, 'whichEmail' => 'r'], function ($message) {
                        $message->to(Auth::user()->register_email, 'Sayın ' . Auth::user()->name_surname)->subject("E-mail Onayı");
                    });

                    //Confirmation Value Create
                    Confirmation::query()
                        ->create([
                            'user_id' => Auth::user()->id,
                            'confirmation_key' => $confirmation_key,
                            'confirmation_which' => 'register_email'
                        ]);

                    return with([
                        'status' => '1'
                    ]);

                } else {
                    //School Email Send

                    //User Check
                    $user = users::query()
                        ->where([
                            'id' => Auth::user()->id,
                            'school_email' => '0'
                        ])
                        ->orWhere(function ($query) {
                            $query->where([
                                'id' => Auth::id(),
                                'school_email_confirmation' => '1'
                            ]);
                        })->exists();

                    if ($user) {
                        return abort(404);
                    }

                    //User School Email Confirmation
                    $userSchoolEmailConfirmation = Confirmation::query()
                        ->where([
                            'user_id' => Auth::user()->id,
                            'confirmation_which' => 'school_email'
                        ]);

                    //Old School Emial Confirmation Delete
                    if ($userSchoolEmailConfirmation->exists()) {
                        $userSchoolEmailConfirmation->delete();
                    }

                    //School Email Send
                    Mail::send("email/registerEmailConfirmation", ['nameSurname' => Auth::user()->name_surname, 'confirmationKey' => $confirmation_key, 'whichEmail' => 's'], function ($message) {
                        $message->to(Auth::user()->school_email, 'Sayın ' . Auth::user()->name_surname)->subject("Okul E-posta Onayı");
                    });

                    //Confirmation Create
                    Confirmation::query()
                        ->create([
                            'user_id' => Auth::user()->id,
                            'confirmation_key' => $confirmation_key,
                            'confirmation_which' => 'school_email'
                        ]);

                    return with([
                        'status' => '1'
                    ]);
                }
            } catch (\Exception $e) {
                return with([
                    'status' => '0',
                    'message' => 'Onay e-maili gönderilirken bir hata oluştu.E-posta hesabınızı doğru yazdığınıza emin olun.'
                ]);
            }
        } else {
            return with([
                'status' => '0',
                'message' => 'Kullanıcı girişi yapmalısınız.'
            ]);
        }
    }
}
