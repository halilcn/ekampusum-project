<?php

namespace App\Http\Controllers;

use App\Events\emailConfirmation;

use App\Http\Requests\AccountPost\AccountPostRequest;
use App\Http\Requests\AnnouncemenetAndNewsCommentRequest;
use App\Http\Requests\ClubAnnouncementAndNewsPost\ClubAnnouncementAndNewsPostRequest;
use App\Http\Requests\ClubEventPost\ClubEventPostRequest;
use App\Http\Requests\ClubEventPost\ClubEventRequest;
use App\Http\Requests\ClubPost\ClubPostRequest;
use App\Http\Requests\ClubSettingRequest;
use App\Http\Requests\ConfessionPost\ConfessionPostRequest;
use App\Http\Requests\DiscussionInPagePost\DiscussionInPagePostRequest;
use App\Http\Requests\DiscussionPost\DiscussionPostRequest;
use App\Http\Requests\EventCommentRequest;
use App\Http\Requests\LessonNotes\LessonNotesPostRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Requests\PasswordForgetNewPasswordRequest;
use App\Http\Requests\RegisterPost\RegisterPostRequest;
use App\Http\Requests\RegisterPost\RegisterRequest;
use App\Jobs\sendMail;
use App\Models\AnnouncementAndNews;
use App\Models\AnnouncementAndNewsComment;
use App\Models\Club;
use App\Models\ClubCreateForm;
use App\Models\ClubInvitation;
use App\Models\ClubMember;
use App\Models\ClubSetting;
use App\Models\Confession;
use App\Models\ConfessionComment;
use App\Models\ConfessionUser;
use App\Models\Confirmation;
use App\Models\Discussion;
use App\Models\DiscussionComment;
use App\Models\Event;
use App\Models\EventComment;
use App\Models\Help;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\Post;
use App\Models\Report;
use App\Models\SchoolLesson;
use App\Models\SchoolLessonNoteForm;
use App\Models\SchoolSection;
use App\Models\User;
use App\Models\UserPasswordChange;
use App\Models\users;
use Debugbar;
use http\Env\Response;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use MongoDB\Driver\ReadConcern;
use PharIo\Manifest\RequiresElementTest;
use phpDocumentor\Reflection\DocBlock\Tags\Link;
use PhpParser\Builder;
use Psy\CodeCleaner\AssignThisVariablePass;
use function GuzzleHttp\Psr7\str;
use function PHPUnit\Framework\isEmpty;


class homeController extends Controller
{
    //file_get_contents()

    public function __construct()
    {
        Carbon::setLocale('tr');
    }


    public function artisan_command(Request $request)
    {
        Artisan::call($request->k);
        return "ok";
    }

    public function globalLinks(Request $request)
    {
        if ($request->which == 'emailAgainSend') {
            $response = event(new emailConfirmation());
            return response()->json([
                'status' => $response[0]['status']
            ]);
        } else if ($request->which == 'notificationsGet') {
            try {
                $query = NotificationUser::query()
                    ->orderByDesc('created_at')
                    ->when($request->id != '0', function ($query) use ($request) {
                        $query->where('id', '<', $request->id);
                    })
                    ->where('user_id', Auth::user()->id)
                    ->with('user')
                    ->latest()
                    ->take(6);

                $notificationsGet = $query->get();
                $query->update([
                    'notification_view' => '1'
                ]);
                $notifications = [];
                $count = 0;
                foreach ($notificationsGet as $notification) {
                    //collect ?? :(
                    if ($notification->notification_id == '1') {
                        $discussion = Discussion::query()
                            ->where('id', $notification->notification_information)
                            ->firstOrFail();
                        //username, userImg, title, subject, date, link,
                        $notifications[$count]['notificationId'] = '1';
                        $notifications[$count]['username'] = $notification->user->username;
                        $notifications[$count]['userImg'] = $notification->user->image;
                        $notifications[$count]['title'] = $discussion->title;
                        $notifications[$count]['date'] = Carbon::make($notification->created_at)->isoFormat('Do MMMM YYYY');
                        $notifications[$count]['link'] = $discussion->link;
                        $notifications[$count]['dataId'] = $notification->id;
                        $notifications[$count]['view'] = $notification->notification_view;
                    } else if ($notification->notification_id == '2') {
                        //username, userImg, clubName, id
                        $notification_informations = explode(',', $notification->notification_information);
                        $notifications[$count]['notificationId'] = '2';
                        $notifications[$count]['username'] = $notification->user->username;
                        $notifications[$count]['userImg'] = $notification->user->image;
                        $notifications[$count]['clubName'] = $notification_informations[0];
                        $notifications[$count]['clubInvitationId'] = $notification_informations[1];
                        $notifications[$count]['dataId'] = $notification->id;
                        $notifications[$count]['view'] = $notification->notification_view;
                    } else if ($notification->notification_id == '3') {
                        //username, userImg, clubName, roleName, date
                        $notification_informations = explode(',', $notification->notification_information);
                        $notifications[$count]['notificationId'] = '3';
                        $notifications[$count]['username'] = $notification->user->username;
                        $notifications[$count]['userImg'] = $notification->user->image;
                        $notifications[$count]['clubName'] = $notification_informations[0];
                        $notifications[$count]['roleName'] = $notification_informations[1];
                        $notifications[$count]['date'] = Carbon::make($notification->created_at)->isoFormat('Do MMMM YYYY');
                        $notifications[$count]['dataId'] = $notification->id;
                        $notifications[$count]['view'] = $notification->notification_view;
                    }
                    $count++;
                }
                return response()->json($notifications);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }

        } else if ($request->which == 'notificationsAllDelete') {
            try {
                NotificationUser::query()
                    ->where('user_id', Auth::id())
                    ->delete();
                return response()->json([
                    'status' => '1',
                    'message' => 'Tüm bildirimler silindi.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'notificationsClubInvitationAnswer') {
            try {
                //Get Club Invitation
                $clubInvitation = ClubInvitation::query()
                    ->where([
                        'id' => $request->clubInvitationId,
                        'user_id' => Auth::id(),
                        'invitation_who' => 'club'
                    ])
                    ->firstOrFail();

                //Delete Notification User
                if ($request->dataId !== '0') {
                    NotificationUser::query()
                        ->where([
                            'id' => $request->dataId,
                            'user_id' => Auth::id()
                        ])
                        ->firstOrFail()
                        ->delete();
                } else {
                    NotificationUser::query()
                        ->where([
                            'user_id' => Auth::id(),
                            'notification_id' => '2',
                            'event_user_id' => $clubInvitation->event_user_id
                        ])
                        ->firstOrFail()
                        ->delete();
                }

                //Delete Club Invitation
                $clubInvitation->delete();

                if ($request->answer == '1') {
                    //Create Club Member
                    ClubMember::query()
                        ->create([
                            'club_id' => $clubInvitation->club_id,
                            'user_id' => $clubInvitation->user_id,
                        ]);
                    return response()->json([
                        'status' => '1',
                        'message' => 'Grup isteği kabul edildi.'
                    ]);
                } else {
                    return response()->json([
                        'status' => '1',
                        'message' => 'Grup isteği reddedildi.'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        }
    }

    public function download_get(Request $request)
    {
        return Storage::download($request->file);
    }

    //Spagetti Code :/
    public function eposta_onay_get($confirmationKey)
    {
        //Confirmation Get
        $confirmation = Confirmation::query()
            ->where('confirmation_key', $confirmationKey)
            ->first();

        if ($confirmation) {
            $user = users::query()
                ->where([
                    'id' => $confirmation->user_id,
                    'register_email' => DB::raw('school_email')
                ])
                ->exists();

            if ($user) {
                //school ile kayıt olmuş
                try {
                    users::query()
                        ->where('id', $confirmation->user_id)
                        ->update([
                            'register_email_confirmation' => '1',
                            'school_email_confirmation' => '1'
                        ]);

                    Confirmation::query()
                        ->where('id', $confirmation->id)
                        ->firstOrFail()
                        ->delete();

                    return view('email/emailStatus')->with('status', '1');
                } catch (\Exception $e) {
                    return view('email/emailStatus')->with('status', '0');
                }
            } else {
                if ($confirmation->confirmation_which == 'register_email') {
                    //ilk kayıt register_email ile
                    try {
                        users::query()
                            ->where('id', $confirmation->user_id)
                            ->update(['register_email_confirmation' => '1']);

                        Confirmation::query()
                            ->where('id', $confirmation->id)
                            ->firstOrFail()
                            ->delete();
                        return view('email/emailStatus')->with('status', '1');
                    } catch (\Exception $e) {
                        return view('email/emailStatus')->with('status', '0');
                    }
                } else {
                    //Daha sonra okul hesabı onayı yapma
                    try {
                        users::query()
                            ->where('id', $confirmation->user_id)
                            ->update(['school_email_confirmation' => '1']);
                        Confirmation::query()
                            ->where('id', $confirmation->id)
                            ->firstOrFail()
                            ->delete();
                        return view('email/emailStatus')->with('status', '1');
                    } catch (\Exception $e) {
                        return view('email/emailStatus')->with('status', '0');
                    }
                }
            }
        } else {
            abort(404);
        }
    }

    public function ana_sayfa_get()
    {
        $confessionsUser = null;
        if (Auth::check()) {
            $confessionsUser = ConfessionUser::query()
                ->where('user_id', Auth::user()->id)
                ->select('username', 'image')
                ->first();
        }

        return view('mainPage', compact('confessionsUser'));
    }

    public function ana_sayfa_post(Request $request)
    {
        if ($request->which == 'morePost') {
            try {
                //Posts Get
                $posts = Post::query()
                    ->latest('id')
                    ->when($request->lastId != '0', function ($query) use ($request) {
                        $query->where('id', '<', $request->lastId);
                    })
                    ->take(10)
                    ->postMorpToWith()
                    ->get();

                // Check User Email
                $userEmailConfirmation = 0;
                if (Auth::check()) {
                    $userEmailConfirmation = Auth::user()->register_email_confirmation == '1' ? true : false;
                }

                return response()->json([
                    'status' => '1',
                    'userEmailConfirmation' => $userEmailConfirmation,
                    'posts' => $posts
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }

        } else if ($request->which == 'moreComment') {
            try {
                //Auth Check
                if (!Auth::check()) {
                    abort(404);
                }

                //Get Comments
                $comments = Post::query()
                    ->where('id', $request->dataId)
                    ->commentWith($request)
                    ->get()
                    ->pluck('post.comments')
                    ->collapse()
                    ->map(function ($query) {
                        return $query->only(['id', 'message', 'created_at', 'user']);
                    });

                return response()->json([
                    'status' => '1',
                    'comments' => $comments
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'clubAuthorityCheck') {
            try {
                //Auth Check
                if (!Auth::check()) {
                    abort(404);
                }

                //Club Member Authority Check
                $clubs = ClubMember::query()
                    ->where('user_id', Auth::id())
                    ->whereIn('authority', [1, 2])
                    ->with('clubs:club_link,club_name,id')
                    ->get()
                    ->pluck('clubs');

                return response()->json(compact('clubs'));
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'confessionAuthorityCheck') {
            try {
                //Auth Check
                if (!Auth::check()) {
                    abort(404);
                }

                //Confession Profile Check
                $user = ConfessionUser::query()
                    ->where('user_id', Auth::id())
                    ->select('username', 'image')
                    ->first();

                return response()->json(compact('user'));
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'reportPost') {
            try {
                //Check Post
                Post::findOrFail($request->postId);

                //Create Report
                Report::create([
                    'post_id' => $request->postId,
                    'user_id' => Auth::user()->id
                ]);

                return response()->json([
                    'status' => '1',
                    'message' => 'Başarıyla bildirildi!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        }
    }

    public function yardim_destek_get()
    {
        return view('others.support');
    }

    public function yardim_destek_post(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'question' => ['required']
            ],
                [
                    'email.required' => 'E-mail alanı gereklidir.',
                    'email.email' => 'E-mail alanı uygun değil.',
                    'question.required' => 'Soru alanı boş bırakılamaz.',
                ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => '0',
                    'message' => $validator->errors()->first()
                ]);
            }

            Help::create([
                'email' => $request->email,
                'question' => $request->question
            ]);

            return response()->json([
                'status' => '1',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Bir hata oluştu.'
            ]);
        }
    }

    public function hakkimizda_get()
    {
        return view("others.about");
    }

    public function hizmet_kosullari_get()
    {
        return view('others.terms');
    }

    public function aday_ogrenci_get()
    {
        return view('candidateStudent');
    }

    public function get_profil(Request $request, $username)
    {
        try {
            $pageType = $request->selection;
            $user = users::query()
                ->where('username', $username)
                ->firstOrFail();

            if (!isset($request->selection)) {
                $pageType = 'clubs';
            }

            if ($pageType == 'clubs') {
                $user->load([
                    'clubsMember',
                    'clubsMember.clubs',
                    'clubsMember.clubs.settings'
                ]);
            } else if ($pageType == 'lastDiscussion') {
                $user->load([
                    'lastDiscussions' => function ($discussions) {
                        $discussions->withCount('comments')->limit(5);
                    }
                ]);
            }
            return view('profile.userProfile', compact('user', 'pageType'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function kayit_ol_get()
    {
        return view('register');
    }

    public function kayit_ol_post(RegisterPostRequest $request)
    {
        if ($request->which == 'register') {
            //User School Email Control
            $schoolEmail = 0;
            $registerEmail = $request->register_email;
            $email = Str::after($request->register_email, '@');
            if ($email === 'ktun.edu.tr' || $email === 'selcuk.edu.tr') {
                $schoolEmail = $request->register_email;
                $registerEmail = $request->register_email;
            }
            try {
                //User Create
                $user = users::create([
                    'username' => $request->username,
                    'name_surname' => $request->name_surname,
                    'password' => Hash::make($request->password),
                    'register_email' => $registerEmail,
                    'school_email' => $schoolEmail
                ]);

                //User Login
                if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                    $request->session()->regenerate();
                    $request->session()->flash('newUser', '1');

                    //Notifications Table Create
                    Notification::create(['user_id' => Auth::user()->id]);

                    $result = event(new emailConfirmation());
                    return response()->json([
                        'status' => '1',
                        'redirectTo' => '/'
                    ]);

                } else {
                    return response()->json([
                        'status' => '0',
                        'message' => 'Bir hata oluştu . Lütfen daha sonra tekrar deneyiniz . '
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . Lütfen daha sonra tekrar deneyiniz . '
                ]);
            }
        } else if ($request->which == 'usernameCheck') {
            //Username Check
            if (users::where("username", $request->value)->exists()) {
                return response()->json([
                    'which' => 'usernameCheck',
                    'status' => '0'
                ]);
            } else {
                return response()->json([
                    'which' => 'usernameCheck',
                    'status' => '1'
                ]);
            }
        } else if ($request->which == 'registerEmailCheck') {
            if (users::where('register_email', $request->value)->exists()) {
                return response()->json([
                    'which' => 'registerEmailCheck',
                    'status' => '0'
                ]);
            } else {
                return response()->json([
                    'which' => 'registerEmailCheck',
                    'status' => '1'
                ]);
            }
        }
    }

    public function giris_yap_get()
    {
        return view('sign_in');
    }

    public function giris_yap_post(LoginRequest $request)
    {
        $usernameOrEmail = Str::contains($request->username_or_email, '@');
        $request->remember_me === NULL ? $rememberMe = false : $rememberMe = true;
        if (!$usernameOrEmail) {
            $username = $request->username_or_email;
            $eror = 'Kullanıcı Adı veya Şifre yanlış.';
        } else {
            //Register E-mail Check
            $user = users::query()
                ->where("register_email", $request->username_or_email)
                ->first();
            //$user NULL Query
            $username = $user == null ?: $user->username;
            $eror = 'E-mail veya Şifre yanlış . ';
        }
        try {
            if (Auth::attempt(['username' => $username, 'password' => $request->password], $rememberMe)) {
                $request->session()->regenerate();
                return response()->json([
                    'status' => '1',
                    'redirectTo' => '/'
                ]);
            } else {
                return response()->json([
                    'status' => '0',
                    'message' => $eror
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Bir hata oluştu . Lütfen daha sonra tekrar deneyiniz . '
            ]);
        }
    }

    public function cikis_get(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function main_sifre_degistir_get()
    {
        return view('passwordChange.passwordChangePage');
    }

    public function main_sifre_degistir_post(Request $request)
    {
        if (isset($request->data)) {
            try {
                $isEmail = Str::contains($request->data, '@');
                //$request->data Check users Model
                $user = users::query()
                    ->when($isEmail, function ($query) use ($request) {
                        $query->where('register_email', $request->data);
                    }, function ($query) use ($request) {
                        $query->where('username', $request->data);
                    })
                    ->first();
                if (isset($user)) {
                    $random = Str::random(64);
                    if ($user->register_email_confirmation == '1') {
                        //Delete Last Random Code
                        UserPasswordChange::query()
                            ->where('user_id', $user->id)
                            ->delete();

                        //Create New Random Code
                        UserPasswordChange::create([
                            'user_id' => $user->id,
                            'random_link' => $random
                        ]);
                        //Make E-mail Data
                        $emailData = collect([
                            'username' => $user->username,
                            'random_link' => $random
                        ]);
                        //Add E-mail to Job
                        sendMail::dispatchNow('email/passwordChangeCode', $emailData->all(), $user->register_email, 'Şifre Değiştirme Talebi');
                        //E-mail Hide
                        // :/
                        $email = explode('@', $user->register_email);
                        $lenght = floor(strlen($email[0]) / 2);
                        $newHideEmail = substr($email[0], 0, $lenght) . str_repeat('*', $lenght) . "@" . $email[1];
                        return response()->json([
                            'status' => '1',
                            'message' => $newHideEmail
                        ]);
                    } else {
                        return response()->json([
                            'status' => '0',
                            'message' => 'E-mail doğrulanmamış . İlk önce e-mail doğrulaması gerekir . '
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => '0',
                        'message' => 'Böyle bir kullanıcı yok . '
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else {
            return response()->json([
                'status' => '0',
                'message' => 'Boş bırakılamaz . '
            ]);
        }
    }

    public function main_sifre_degistir_slug_get($slug)
    {
        $userPasswordChange = UserPasswordChange::query()
            ->where('random_link', $slug)
            ->firstOrFail();

        $time = Carbon::parse($userPasswordChange->created_at)->diffInSeconds(Carbon::parse(now()));
        if (isset($userPasswordChange) && ($time < 3600)) {
            return view('passwordChange.passwordChangeInPage');
        } else {
            abort('404');
        }
    }

    public function main_sifre_degistir_slug_post(PasswordForgetNewPasswordRequest $request, $slug)
    {
        try {
            $userPasswordChange = UserPasswordChange::query()
                ->where('random_link', $slug)
                ->firstOrFail();
            $time = Carbon::parse($userPasswordChange->created_at)->diffInSeconds(Carbon::parse(now()));
            if ($time > 3600) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Lütfen tekrar e-mail gönderiniz. '
                ]);
            }
            //New Password Update
            users::query()
                ->findOrFail($userPasswordChange->user_id)
                ->update([
                    'password' => Hash::make($request->new_password)
                ]);
            //Random Code Delete
            $userPasswordChange->delete();
            Session::flash('passwordChangeSuccess', 'Şifre başarıyla değiştirildi.');
            return response()->json([
                'status' => '1',
                'redirect' => env('APP_URL') . '/giris-yap'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Bir hata oluştu . '
            ]);
        }
    }

    public function hesap_get()
    {
        return view('settings.accountSettings');
    }

    //Spagetti Code :(
    public function hesap_post(AccountPostRequest $request)
    {
        if ($request->which == 'email') {
            $result = event(new emailConfirmation());
            if ($result[0]['status'] == '1') {
                return response()->json([
                    'status' => $result[0]['status'],
                    'message' => 'E-mail tekrar gönderildi. '
                ]);
            } else {
                return response()->json([
                    'status' => $result[0]['status'],
                    'message' => $result[0]['message']
                ]);
            }
        } else if ($request->which == 'accountSave') {


            //Not Confirmation Email Button
            $school_email_confirm_button = false;

            //school_email Check NULL
            if (empty($request->school_email)) {
                $school_email = '0';
            } else {
                if (Auth::user()->school_email_confirmation == '1') {
                    $school_email = Auth::user()->school_email;
                } else {
                    $school_email = $request->school_email;
                }
            }

            //about update Check NULL
            if (empty($request->about)) {
                $about = '0';
            } else {
                $about = $request->about;
            }

            try {
                //School Email Query
                if ($school_email != '0') {
                    $email = Str::after($request->school_email, '@');
                    if ($email != 'selcuk.edu.tr' && $email != 'ktun.edu.tr') {
                        return response()->json([
                            'status' => '0',
                            'message' => 'Geçerli bir okul hesabı girmelisiniz.'
                        ]);
                    }
                }

                //school_email Changed ?
                if ($request->school_email != Auth::user()->school_email) {
                    if (users::where(['school_email' => $request->school_email, 'school_email_confirmation' => '1'])->exists()) {
                        return response()->json([
                            'status' => '0',
                            'message' => 'Bu okul e-postası daha önce kullanılmış'
                        ]);
                    }
                    if ($request->school_email != null) {
                        $school_email_confirm_button = true;
                    }
                }

                if (Auth::user()->register_email_confirmation == '0') {
                    abort(404);
                }

                //Profil fotoğrafı işlemleri
                $image_name = Auth::user()->image;
                if ($request->file('image')) {
                    $image = $request->file('image');
                    $file_image = Image::make($image);
                    $file_image->resize(512, 512);
                    $image_name = rand() . '.' . $image->getClientOriginalExtension();
                    $file_image->save(public_path('./img/user/' . $image_name));
                    if (Auth::user()->image != '0') {
                        File::delete('./img/user/' . Auth::user()->image);
                    }
                }

                users::where('id', Auth::user()->id)->update([
                    'name_surname' => $request->name_surname,
                    'image' => $image_name,
                    'about' => $about,
                    'school_email' => $school_email,
                ]);

                return response()->json([
                    'status' => '1',
                    'message' => 'Hesap ayarları güncellendi . ',
                    'school_email_confirm_button' => $school_email_confirm_button
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                ]);
            }
        }
    }

    public function sifre_degistir_get()
    {
        return view('settings.passwordChange');
    }

    public function sifre_degistir_post(PasswordChangeRequest $request)
    {
        if (Hash::check($request->real_password, Auth::user()->password)) {
            //Password Change
            Auth::user()->update([
                'password' => Hash::make($request->new_password)
            ]);
            return response()->json([
                'status' => '1',
                'message' => 'Şifre başarıyla değiştirildi . '
            ]);
        } else {
            return response()->json([
                'status' => '0',
                'message' => 'Mevcut şifren yanlış . '
            ]);
        }
    }

    public function bildirimler_get()
    {
        //All Notifications Get
        $notifications = Notification::query()
            ->where('user_id', Auth::id())
            ->first();
        return view('settings.notifications', compact('notifications'));
    }

    public function bildirimler_post(Request $request)
    {
        try {
            //Notifications Update
            Notification::query()
                ->where('user_id', Auth::user()->id)
                ->update([
                    'discussion_new_comment_mail' => '0',
                    'discussion_new_comment' => $request->input('discussion_new_comment', '0'),
                    'new_events' => $request->input('new_events', '0')
                ]);

            return response()->json([
                'status' => '1',
                'message' => 'Bildirimler başarıyla kaydedildi . '
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Bir hata oluştu . '
            ]);
        }
    }

    public function kuluplerim_get()
    {
        $clubMembers = ClubMember::query()
            ->where('user_id', Auth::id())
            ->with([
                'clubs',
                'clubs.settings:club_id,image'
            ])
            ->get();

        return view('myClubs.myClubsPage', compact('clubMembers'));
    }

    public function tartismalar_get()
    {
        //Discussions Get
        $discussions = Discussion::query()
            ->orderBy('id', 'DESC')
            ->with('user', 'comments')
            ->paginate(15);

        return view('tartismalar.tartismalar_dis', compact('discussions'));
    }

    public function tartismalar_post(DiscussionPostRequest $request)
    {
        if ($request->which == 'newDiscussionSend') {
            try {
                if (Auth::user()->register_email_confirmation == '0') {
                    abort(404);
                }
                //Link Create
                $link = Str::slug($request->title) . '-' . rand();

                //Discussion Create
                $discussion = Discussion::create([
                    'user_id' => Auth::id(),
                    'title' => $request->title,
                    'subject' => $request->subject,
                    'link' => $link
                ]);

                //Post Create
                Post::create([
                    'post_type' => 'discussion',
                    'post_id' => $discussion->id
                ]);

                return response()->json([
                    'status' => '1',
                    'message' => 'Tartışman paylaşıldı . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        }
    }

    public function tartismalar_in_page_get(Request $request, $discussionLink)
    {
        try {
            //Discussion Get
            $discussion = Discussion::query()
                ->where('link', $discussionLink)
                ->with('user')
                ->withCount('comments')
                ->firstOrFail();

            //Discussion Comments Get
            $discussionComments = DiscussionComment::query()
                ->where('discussion_id', $discussion->id)
                ->with('user')
                ->when($request->listType == 'vote', function ($query) {
                    $query->orderBy('vote', 'DESC');
                })
                ->when($request->listType == 'created_at' || !isset($request->listType), function ($query) {
                    $query->orderBy('created_at', 'DESC');
                })
                ->paginate(12);

            return view('tartismalar.tartismalar_ic', compact('discussion', 'discussionComments'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function tartismalar_in_page_post(DiscussionInPagePostRequest $request, $discussionLink)
    {
        //Discussion Get
        $discussion = Discussion::query()
            ->where('link', $discussionLink)
            ->firstOrFail();

        if ($request->which == 'edit') {
            try {
                if ($discussion->title == $request->title) {
                    $link = $discussion->link;
                } else {
                    //New Link Create
                    $link = Str::slug($request->title) . '-' . rand();
                }
                //Discussion Update
                Discussion::query()
                    ->where([
                        'link' => $discussionLink,
                        'user_id' => Auth::id()
                    ])
                    ->update([
                        'title' => $request->title,
                        'subject' => $request->subject,
                        'link' => $link
                    ]);

                return response()->json([
                    'status' => '1',
                    'link' => $link
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }

        } else if ($request->which == 'discussionNewComments') {
            try {
                //Email Confirmation Check
                if (Auth::user()->register_email_confirmation == '0') {
                    abort(404);
                }

                //Comment Create
                DiscussionComment::create([
                    'discussion_id' => $discussion->id,
                    'user_id' => Auth::id(),
                    'message' => $request->comment
                ]);

                //Comment Notification Get
                $notification = Notification::query()
                    ->where('user_id', $discussion->user->id)
                    ->first();

                if (Auth::id() != $discussion->user->id) {
                    //Notification Create
                    if ($notification->discussion_new_comment == '1') {
                        //Notifications Add
                        NotificationUser::create([
                            'user_id' => $discussion->user->id,
                            'event_user_id' => Auth::id(),
                            'notification_id' => '1',
                            'notification_information' => $discussion->id
                        ]);
                    }
                    //Mail Notification Create
                    if ($notification->discussion_new_comment_mail == '1') {
                        //Mail Queue
                        $emailData = collect([
                            'title' => $discussion->title,
                            'user' => Auth::user()->username,
                            'link' => $discussionLink
                        ]);
                        $email = $discussion->user->register_email;
                        sendMail::dispatchNow('email/discussionNewCommentNotification', $emailData->all(), $email, 'Tartışmana Yeni Yorum Geldi!');
                    }
                }


                return response()->json([
                    'status' => '1',
                    'message' => 'Yorum yapıldı . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'increaseDiscussionComment') {
            try {
                $discussion->comments()->where([
                    ['id', '=', $request->commentId],
                    ['user_id', '!=', Auth::id()]
                ])->increment('vote', $request->count);
                return response()->json([
                    'status' => '1'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bİr hata oluştu.'
                ]);
            }
        }
    }

    public function duyuru_haber_get()
    {
        $announcementAndNewsGet = AnnouncementAndNews::query()
            ->orderBy('id', 'DESC')
            ->with('user', 'club')
            ->withCount('comments')
            ->paginate(10);

        return view('announcementsAndNews.announcementsAndNewsPage', compact('announcementAndNewsGet'));
    }

    public function duyuru_haber_in_page_get($link)
    {
        try {
            $announcementAndNewsQuery = AnnouncementAndNews::where('link', $link);
            //Page View Field Increment
            $announcementAndNewsQuery->increment('view_count');
            $announcementAndNews = $announcementAndNewsQuery->with('user', 'club')->firstOrFail();
            $announcementAndNewsComments = AnnouncementAndNewsComment::query()
                ->where('announcements_and_news_id', $announcementAndNews->id)
                ->orderBy('created_at', 'DESC')
                ->with('user')
                ->paginate(15);
            return view('announcementsAndNews.announcementsAndNewsInPage', compact('announcementAndNews', 'announcementAndNewsComments'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function duyuru_haber_in_page_post(AnnouncemenetAndNewsCommentRequest $request, $link)
    {
        try {
            //Announcement Query
            $announcementAndNews = AnnouncementAndNews::query()
                ->where('link', $link)
                ->firstOrFail();

            //register_email_confirmation Confirmation Check
            if (Auth::user()->register_email_confirmation == '0') {
                abort(404);
            }

            //COmment Create
            AnnouncementAndNewsComment::create([
                'user_id' => Auth::user()->id,
                'announcements_and_news_id' => $announcementAndNews->id,
                'message' => $request->message
            ]);

            return response()->json([
                'status' => '1',
                'message' => 'Yorum Yapıldı . '
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Bir hata oluştu . '
            ]);
        }
    }

    public function etkinlik_get()
    {

        //Event Get
        $events = Event::query()
            ->orderBy('date', 'DESC')
            ->with('club', 'user')
            ->withCount('comments')
            ->paginate(10);

        $clubs = Club::query()
            ->latest()
            ->take(3)
            ->with('settings:image,club_id')
            ->withCount('members')
            ->get();

        return view('events.eventsPage', compact('events', 'clubs'));
    }

    public function etkinlik_in_page_get($link)
    {
        try {
            $eventsQuery = Event::where('link', $link);
            //Event Page View Increment
            $eventsQuery->increment('view_count');
            //Event Query
            $event = $eventsQuery->with('user', 'club')->firstOrFail();
            //Comments Get
            $eventComments = EventComment::query()
                ->where('events_id', $event->id)
                ->with('user')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            return view('events.eventsInPage', compact('event', 'eventComments'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function etkinlik_in_page_post(EventCommentRequest $request, $link)
    {
        try {
            //Event Check
            $event = Event::query()
                ->where('link', $link)
                ->firstOrFail();
            if (Auth::user()->register_email_confirmation == '0') {
                abort(404);
            }
            //Comment Create
            EventComment::create([
                'user_id' => Auth::id(),
                'events_id' => $event->id,
                'message' => $request->message
            ]);

            return response()->json([
                'status' => '1',
                'message' => 'Yorum Yapıldı . '
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => '0',
                'message' => 'Bir hata oluştu . '
            ]);
        }
    }

    public function kulupler_get()
    {
        //Clubs Get
        $clubs = Club::query()
            ->with('settings')
            ->withCount('members')
            ->paginate(25);
        return view('club.clubList', compact('clubs'));
    }

    public function kulupler_post(ClubPostRequest $request)
    {
        if ($request->which == 'newClub') {
            try {

                if (Auth::user()->school_email_confirmation == '0') {
                    abort(401);
                }

                //Image Resize And Save
                $image = $request->file('club_logo');
                $imageName = Str::random() . '.' . $image->clientExtension();
                $fileImage = Image::make($image);
                $fileImage->resize(512, 512)->save(public_path('./img/club_form/' . $imageName));

                ClubCreateForm::create([
                    'username' => Auth::user()->username,
                    'email' => Auth::user()->register_email,
                    'club_logo' => $imageName,
                    'club_name' => strtoupper($request->club_name),
                    'club_social' => $request->club_social
                ]);
                $request->session()->flash('newClubSuccess', 'Yeni kulüp isteği gönderildi!');
                return response()->json([
                    'status' => '1',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu'
                ]);
            }
        } else if ($request->which == 'searchClub') {
            try {
                //Clubs Search
                $clubs = Club::query()
                    ->where('club_name', 'LIKE', '%' . $request->club_name . '%')
                    ->with('settings', function ($query) {
                        $query->select('image', 'club_id');
                    })
                    ->select('id', 'club_name', 'club_link')
                    ->withCount('members')
                    ->get();

                return response()->json([
                    'status' => '1',
                    'clubs' => $clubs
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu'
                ]);
            }
        }
    }

    //Club User Control Function
    //Ultra Spagetti Code :/
    public function clubUserControl($link)
    {
        try {
            //Club Check
            $club = Club::query()
                ->where('club_link', $link)
                ->with('settings')
                ->withCount('members', 'events')
                ->firstOrFail();

            //Authority Create
            $clubAuth = [
                'clubAuthority' => '0',
                'clubMember' => '0',
                'clubAuthorityAdmin' => '0',
                'clubMiddleAuthority' => '0'
            ];
            if (Auth::check()) {
                $clubAdmin = ClubMember::query()
                    ->where([
                        'club_id' => $club->id,
                        'user_id' => Auth::id()
                    ])
                    ->first();
                //Admin Check
                if (!empty($clubAdmin)) {
                    $clubAuth['clubMember'] = '1';
                    if ($clubAdmin->authority == '2') {
                        $clubAuth['clubAuthority'] = '1';
                        if ($clubAdmin->role == '3') {
                            $clubAuth['clubAuthorityAdmin'] = '1';
                        }
                    } else if ($clubAdmin->authority == '1') {
                        $clubAuth['clubMiddleAuthority'] = '1';
                    }
                    $clubInvitation = false;
                } else {
                    $clubInvitation = ClubInvitation::query()
                        ->where([
                            'club_id' => $club->id,
                            'user_id' => Auth::id()
                        ])
                        ->select('invitation_who', 'id')
                        ->first();
                }
            } else {
                $clubAuth['clubAuthorityAdmin'] = '1';
                $clubInvitation = false;
            }
            return compact('club', 'clubAuth', 'clubInvitation');
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function kulupler_in_page_get($link)
    {
        $clubUserControl = $this->clubUserControl($link);
        //Neden dizileri ayırdın. :/ Componentte ayrılabilir.
        return view('club.clubInPage', [
            'clubAuth' => $clubUserControl['clubAuth'],
            'club' => $clubUserControl['club'],
            'clubInvitation' => $clubUserControl['clubInvitation']
        ]);
    }

    public function kulupler_in_page_post($link, Request $request)
    {
        if ($request->which == 'userClubExist') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Check
                $clubMember = ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id
                    ])
                    ->firstOrFail();

                //If User "Başkan"
                if ($clubMember->role === '3') {
                    abort(404);
                }

                //Club Member Delete
                $clubMember->delete();
                Session::flash('userClubExist', '1');
                return response()->json([
                    'status' => '1',
                    'message' => 'Başarılı . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'userClubJoin') {
            try {
                if (Auth::user()->school_email_confirmation == '0') {
                    abort(404);
                }

                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Control
                $clubMember = ClubMember::query()
                    ->where([
                        'club_id' => $club->id,
                        'user_id' => Auth::id()
                    ])->exists();

                //Club Invitation Control
                $clubInvitation = ClubInvitation::query()
                    ->where([
                        'user_id' => Auth::id(),
                        'club_id' => $club->id
                    ])->exists();

                //Club Member And Club Invitation Check
                if ($clubMember || $clubInvitation) {
                    abort(404);
                }

                //Club Invitation Create
                ClubInvitation::query()
                    ->create([
                        'user_id' => Auth::id(),
                        'club_id' => $club->id,
                        'invitation_who' => 'user'
                    ]);

                return response()->json([
                    'status' => '1',
                    'Grup isteği gönderildi . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'Bir hata oluştu . '
                ]);
            }
        }
    }

    public function kulupler_duyuru_haber_get($link)
    {
        $clubUserControl = $this->clubUserControl($link);
        $announcementAndNewses = AnnouncementAndNews::query()
            ->where('club_id', $clubUserControl['club']->id)
            ->with('user')
            ->withCount('comments')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        //:/
        return view('club.clubAnnouncementsAndNews', [
            'clubAuth' => $clubUserControl['clubAuth'],
            'club' => $clubUserControl['club'],
            'announcementAndNewses' => $announcementAndNewses,
            'clubInvitation' => $clubUserControl['clubInvitation']
        ]);
    }

    //Ultra Spagetti Code :/
    public function kulupler_duyuru_haber_post(ClubAnnouncementAndNewsPostRequest $request, $link)
    {
        if ($request->which == 'add') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Authority Check
                ClubMember::query()
                    ->where(function ($query) use ($club) {
                        $query->where([
                            'club_id' => $club->id,
                            'user_id' => Auth::id()
                        ]);
                    })
                    ->where(function ($query) {
                        $query->whereIn('authority', [1, 2]);
                    })->firstOrFail();


                //title_image input
                $titleImageName = 0;
                if ($request->file('title_image')) {
                    $titleImageFile = $request->file('title_image');
                    $titleImageName = Str::random() . '.' . $titleImageFile->getClientOriginalExtension();
                    $titleImage = Image::make($titleImageFile);
                    $titleImage->resize(500, 333)->save(public_path('./img/announcements_and_news/' . $titleImageName));
                }

                //images input
                $imagesName = 0;
                if ($request->file('images')) {
                    $imageName = null;
                    $count = 0;
                    foreach ($request->file('images') as $image) {
                        $imageFile = $request->file('images')[$count];
                        $imageName = rand() . '.' . $imageFile->getClientOriginalExtension();
                        $image = Image::make($imageFile);
                        $image->resize(500, 333)->save(public_path('./img/announcements_and_news/' . $imageName));
                        if ($count == 0) {
                            $imagesName = $imageName;
                        } else {
                            $imagesName = $imagesName . ',' . $imageName;
                        }
                        $count++;
                    }
                }

                $announcementAndNewsLink = Str::slug($request->title) . '-' . rand();

                //Announcement And News Create
                $announcementAndNews = AnnouncementAndNews::create([
                    'user_id' => Auth::user()->id,
                    'club_id' => $club->id,
                    'title' => $request->title,
                    'title_image' => $titleImageName,
                    'subject' => $request->subject,
                    'image' => $imagesName,
                    'link' => $announcementAndNewsLink
                ]);

                //Post Create
                Post::create([
                    'post_type' => 'announcement',
                    'post_id' => $announcementAndNews->id
                ]);

                $request->session()->flash('clubAnnouncementSuccess', 'Duyuru/Haber paylaşıldı.');

                return response()->json([
                    'status' => '1',
                    'message' => 'Başarıyla paylaşıldı . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'delete') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Authority Check
                ClubMember::query()
                    ->where(function ($query) use ($club) {
                        $query->where([
                            'club_id' => $club->id,
                            'user_id' => Auth::id()
                        ]);
                    })
                    ->where(function ($query) {
                        $query->whereIn('authority', [1, 2]);
                    })
                    ->firstOrFail();

                //Announcement And News Check
                $announcementAndNews = AnnouncementAndNews::findOrFail($request->id);
                $images = '';
                if ($announcementAndNews->title_image != '0') {
                    $images = $announcementAndNews->title_image . ',';
                }
                if ($announcementAndNews->image != '0') {
                    $images = $images . '' . $announcementAndNews->image;
                }
                foreach (explode(',', $images) as $image) {
                    if (File::exists('./img/announcements_and_news/' . $image)) {
                        File::delete('./img/announcements_and_news/' . $image);
                    }
                }

                Post::query()
                    ->where([
                        'post_type' => 'announcement',
                        'post_id' => $announcementAndNews->id
                    ])->delete();

                $announcementAndNews->comments()->delete();
                $announcementAndNews->delete();
                return response()->json([
                    'status' => '1',
                    'message' => 'Silme işlemi başarılı.'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'editGet') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id
                    ])
                    ->whereIn('authority', [1, 2])
                    ->firstOrFail();

                //Announcement And News Get
                $values = AnnouncementAndNews::query()
                    ->findOrFail($request->id)
                    ->only([
                        'id',
                        'title_image',
                        'title',
                        'subject',
                        'image'
                    ]);

                return response()->json([
                    'status' => '1',
                    'values' => $values
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'editPost') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id
                    ])
                    ->whereIn('authority', [1, 2])
                    ->firstOrFail();

                //Announcement And News Check
                $announcementAndNews = AnnouncementAndNews::findOrFail($request->id);

                //Title Change
                if ($announcementAndNews->title != $request->title) {
                    //Link Create
                    $newLink = Str::slug($request->title) . '-' . rand();
                    $announcementAndNews->title = $request->title;
                    $announcementAndNews->link = $newLink;
                }

                //title_image Change
                //Code Duplication !!
                if ($request->file('title_image')) {
                    $titleImageFile = $request->file('title_image');
                    $titleImageName = rand() . '.' . $titleImageFile->getClientOriginalExtension();
                    $titleImage = Image::make($titleImageFile);
                    $titleImage->resize(500, 333)->save('./img/announcements_and_news/' . $titleImageName);
                    $announcementAndNews->title_image = $titleImageName;

                    if ($request->title_image_delete != '') {
                        if (File::exists('.' . $request->title_image_delete)) {
                            File::delete('.' . $request->title_image_delete);
                        }
                    }
                } else {
                    if ($request->title_image_delete != '') {
                        if (File::exists('.' . $request->title_image_delete)) {
                            File::delete('.' . $request->title_image_delete);
                        }
                        $announcementAndNews->title_image = '0';
                    }
                }

                //Subject change
                if ($announcementAndNews->subject != $request->subject) {
                    $announcementAndNews->subject = $request->subject;
                }

                //Has Images
                if ($announcementAndNews->image != '0') {
                    $hasImages = collect(explode(',', $announcementAndNews->image));
                } else {
                    $hasImages = collect();
                }

                //Delete Old ımages
                if ($request->delete_images != null) {
                    $oldImages = collect();
                    foreach (explode(',', $request->delete_images) as $image) {
                        if (File::exists('.' . $image)) {
                            File::delete('.' . $image);
                            $oldImages->push(basename($image));
                        }
                    }
                    //$oldImages delete
                    $hasImages = $hasImages->diff($oldImages);
                }

                //New Image Add
                if ($request->hasFile('images')) {
                    $newImages = collect();
                    foreach ($request->file('images') as $image) {
                        $images = Image::make($image);
                        $images->resize(500, 333);
                        $imagesName = rand() . '.' . $image->getClientOriginalExtension();
                        $images->save(public_path('./img/announcements_and_news/' . $imagesName));
                        $newImages->push($imagesName);
                    }
                    //NewImages add
                    $hasImages = $hasImages->merge($newImages);
                }

                if ($hasImages->isNotEmpty()) {
                    $announcementAndNews->image = $hasImages->implode(',');
                } else {
                    $announcementAndNews->image = '0';
                }
                $announcementAndNews->save();
                return response()->json([
                    'status' => '1',
                    'message' => 'Başarıyla kaydedildi.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        }
    }

    public function kulupler_etkinlik_get($link)
    {
        $clubUserControl = $this->clubUserControl($link);
        //Event Get
        $events = Event::query()
            ->where('club_id', $clubUserControl['club']->id)
            ->with('user')
            ->withCount('comments')
            ->orderBy('date', 'DESC')
            ->paginate(10);

        return view('club.clubEvents', [
            'clubAuth' => $clubUserControl['clubAuth'],
            'club' => $clubUserControl['club'],
            'events' => $events,
            'clubInvitation' => $clubUserControl['clubInvitation']
        ]);
    }

    //Spagetti Code :/
    public function kulupler_etkinlik_post($link, ClubEventPostRequest $request)
    {
        if ($request->which == 'add') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //User Authority Check
                ClubMember::query()
                    ->where(function ($query) use ($club) {
                        $query->where([
                            'club_id' => $club->id,
                            'user_id' => Auth::user()->id
                        ]);
                    })
                    ->whereIn('authority', [1, 2])
                    ->firstOrFail();

                //title_image input
                $title_image_name = 0;
                if ($request->file('title_image')) {
                    $title_image_file = $request->file('title_image');
                    $title_image_name = Str::random() . '.' . $title_image_file->getClientOriginalExtension();
                    $title_image = Image::make($title_image_file);
                    $title_image->resize(500, 333)->save(public_path('./img/events/' . $title_image_name));
                }

                //images input
                $images_name = 0;
                if ($request->file('images')) {
                    $images_name = null;
                    $count = 0;
                    foreach ($request->file('images') as $image) {
                        $image_file = $request->file('images')[$count];
                        $image_name = Str::random() . '.' . $image_file->getClientOriginalExtension();
                        $image = Image::make($image_file);
                        $image->resize(500, 333)->save(public_path('./img/events/' . $image_name));
                        if ($count == 0) {
                            $images_name = $image_name;
                        } else {
                            $images_name = $images_name . ',' . $image_name;
                        }
                        $count++;
                    }
                }

                //Link Create
                $eventLink = Str::slug($request->title) . '-' . rand();


                //Event Create
                $event = Event::create([
                    'user_id' => Auth::user()->id,
                    'club_id' => $club->id,
                    'title' => $request->title,
                    'title_image' => $title_image_name,
                    'subject' => $request->subject,
                    'image' => $images_name,
                    'date' => $request->date,
                    'location' => $request->location,
                    'link' => $eventLink
                ]);

                //Post Create
                Post::create([
                    'post_type' => 'event',
                    'post_id' => $event->id
                ]);

                //E-mail Send
                Notification::query()
                    ->where('new_events', '1')
                    ->with('user')
                    ->chunk(50, function ($notifications) use ($club, $event) {
                        foreach ($notifications as $notification) {
                            $emailData = collect([
                                'username' => $notification->user->username,
                                'club_name' => $club->club_name,
                                'events_link' => $event->link
                            ]);

                            //Queue Add
                            sendMail::dispatch('email/newEventsNotification', $emailData->all(), $notification->user->register_email, 'Yeni Etkinlik var!');
                        }
                    });

                $request->session()->flash('postEventSuccess', 'Etkinlik paylaşıldı.');

                return response()->json([
                    'status' => '1',
                    'message' => 'Başarıyla paylaşıldı . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'delete') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();


                //User Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id
                    ])
                    ->whereIn('authority', [1, 2])
                    ->firstOrFail();

                //Event Check
                $event = Event::findOrFail($request->id);

                //Event Date Check
                if (Carbon::now()->gte($event->date)) {
                    abort(404);
                }

                $images = '';
                if ($event->title_image != '0') {
                    $images = $event->title_image . ',';
                }
                if ($event->image != '0') {
                    $images = $images . '' . $event->image;
                }

                //All Image Delete
                foreach (explode(',', $images) as $image) {
                    if (File::exists('./img/events/' . $image)) {
                        File::delete('./img/event/' . $image);
                    }
                }

                //Post Delete
                Post::query()
                    ->where([
                        'post_type' => 'event',
                        'post_id' => $event->id
                    ])
                    ->delete();

                //Event Delete And Event Comment Delete
                $event->comments()->delete();
                $event->delete();


                return response()->json([
                    'status' => '1',
                    'message' => 'Silme işlemi başarılı . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }
        } else if ($request->which == 'editGet') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //User Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id
                    ])
                    ->whereIn('authority', [1, 2])
                    ->firstOrFail();

                //Event Get
                $values = Event::query()
                    ->findOrFail($request->id)
                    ->only([
                        'id', 'title_image', 'title', 'subject', 'image', 'date', 'location'
                    ]);

                return response()->json([
                    'status' => '1',
                    'values' => $values
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'editPost') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //User Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id
                    ])
                    ->whereIn('authority', [1, 2])
                    ->firstOrFail();

                //Event Check
                $event = Event::findOrFail($request->id);

                //Event Date Check
                if (Carbon::now()->gte($event->date)) {
                    abort(404);
                }

                //Change Date
                $event->date = $request->date;

                //Change Location
                $event->location = $request->location;


                //Title Change
                if ($event->title != $request->title) {
                    $newLink = Str::slug($request->title) . '-' . rand();
                    $event->title = $request->title;
                    $event->link = $newLink;
                }

                //title_image change
                //Code Duplication !!
                if ($request->file('title_image')) {
                    $titleImageFile = $request->file('title_image');
                    $titleImageName = rand() . '.' . $titleImageFile->getClientOriginalExtension();
                    $titleImage = Image::make($titleImageFile);
                    $titleImage->resize(500, 333)->save(public_path('./img/events/' . $titleImageName));
                    $event->title_image = $titleImageName;

                    //Title Image Delete
                    if ($request->title_image_delete != '') {
                        if (File::exists('.' . $request->title_image_delete)) {
                            File::delete('.' . $request->title_image_delete);
                        }
                    }
                } else {
                    //Title Image Delete
                    if ($request->title_image_delete != '') {
                        if (File::exists('.' . $request->title_image_delete)) {
                            File::delete('.' . $request->title_image_delete);
                        }
                        $event->title_image = '0';
                    }
                }

                //Subject change
                if ($event->subject != $request->subject) {
                    $event->subject = $request->subject;
                }

                //Has Images
                if ($event->image != '0') {
                    $hasImages = collect(explode(',', $event->image));
                } else {
                    $hasImages = collect();
                }

                //Delete Old Images
                if ($request->delete_images != null) {
                    $oldImages = collect();
                    //Images Delete
                    foreach (explode(',', $request->delete_images) as $image) {
                        if (File::exists('.' . $image)) {
                            File::delete('.' . $image);
                            $oldImages->push(basename($image));
                        }
                    }

                    //$oldImages Delete
                    $hasImages = $hasImages->diff($oldImages);
                }

                //New Image Add
                if ($request->hasFile('images')) {
                    $newImages = collect();
                    foreach ($request->file('images') as $image) {
                        $images = Image::make($image);
                        $imagesName = rand() . '.' . $image->getClientOriginalExtension();
                        $images->resize(500, 333)->save(public_path('./img/events/' . $imagesName));
                        $newImages->push($imagesName);
                    }

                    //NewImages Add
                    $hasImages = $hasImages->merge($newImages);
                }

                //hasImages Check
                if ($hasImages->isNotEmpty()) {
                    $event->image = $hasImages->implode(',');
                } else {
                    $event->image = '0';
                }
                $event->save();

                $request->session()->flash('editEventSuccess', 'Etkinlik düzenlendi.');

                return response()->json([
                    'status' => '1',
                    'message' => 'Başarıyla kaydedildi.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        }
    }

    // :(
    public function kulupler_uyeler_get($link)
    {
        try {
            //Club Check
            $club = Club::query()
                ->where('club_link', $link)
                ->with('settings')
                ->withCount('members', 'events')
                ->firstOrFail();

            //Club Member Get
            $clubMembers = ClubMember::query()
                ->where('club_id', $club->id)
                ->with('user')
                ->orderBy('role', 'DESC')
                ->get();

            $teamMembers = $clubMembers->filter(function ($q) {
                return $q->role != '0' || $q->authority != '0';
            });

            $regularMembers = $clubMembers->filter(function ($q) {
                return $q->role == '0' && $q->authority == '0';
            });


            $clubInvitations = [];
            $clubAuth = [
                'clubAuthority' => '0',
                'clubMember' => '0',
                'clubAuthorityAdmin' => '0'
            ];
            $clubInvitation = false;
            if (Auth::check()) {
                $clubAdmin = ClubMember::query()
                    ->where([
                        'club_id' => $club->id,
                        'user_id' => Auth::user()->id
                    ])->first();
                if ($clubAdmin != null) {
                    $clubAuth['clubMember'] = '1';
                    if ($clubAdmin->authority == '2') {
                        $clubInvitations = ClubInvitation::query()
                            ->where([
                                'event_user_id' => '0',
                                'club_id' => $club->id,
                                'invitation_who' => 'user'
                            ])
                            ->with('user')
                            ->get();
                        $clubAuth['clubAuthority'] = '1';
                        if ($clubAdmin->role == '3') {
                            $clubAuth['clubAuthorityAdmin'] = '1';
                        }
                    }
                } else {
                    $clubInvitation = ClubInvitation::query()
                        ->where([
                            'club_id' => $club->id,
                            'user_id' => Auth::id()
                        ])
                        ->select('invitation_who', 'id')
                        ->first();
                }
            } else {
                $clubAuth['clubAuthorityAdmin'] = '1';
            }
            return view('club.clubMembers', compact('regularMembers', 'teamMembers', 'clubInvitations', 'clubAuth', 'club', 'clubInvitation'));
        } catch (\Exception $e) {
            abort(404);
        }
    }

    //Spagetti,Duplicated Code :/
    public function kulupler_uyeler_post($link, Request $request)
    {
        //Her biri için validate düşünülmeli.
        //Optimizasyon ile sorgulama işlemleri azaltılmalı.
        if ($request->which === 'clubMemberSettingGet') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'authority' => '2'
                    ])
                    ->firstOrFail();

                //Club Member Check
                $settings = ClubMember::query()
                    ->where('id', $request->memberId)
                    ->firstOrFail();

                return response()->json([
                    'status' => '1',
                    'username' => $settings->user->username,
                    'role' => $settings->role,
                    'role_name' => $settings->role_name,
                    'authority' => $settings->authority
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which === 'save') {
            try {
                //"0" Role And "2" Authority Check
                if ($request->role === 0 && $request->authority === 2) {
                    abort(404);
                }

                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //Club Member Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'authority' => '2'
                    ])
                    ->firstOrFail();

                //User Check And Get
                $user = users::query()
                    ->where([
                        ['username', $request->username],
                        ['id', '!=', Auth::id()]
                    ])
                    ->firstOrFail();

                //Role Name
                if ($request->role == '0') {
                    $role_name = 'Normal Üye';
                } else if ($request->role == '1') {
                    $role_name = $request->role_name;
                } else if ($request->role == '2') {
                    $role_name = 'Başkan Yardımcısı';
                }

                //Club Member Check
                $clubMemberUpdate = ClubMember::query()
                    ->where([
                        'club_id' => $club->id,
                        'user_id' => $user->id
                    ])
                    ->whereNotIn('role', [3])
                    ->firstOrFail();


                //Notification Create
                if ($request->authority != $clubMemberUpdate->authority || $clubMemberUpdate->role_name != $role_name) {
                    if ($request->authority == '0') {
                        $authority = 'Yetki Yok';
                    } else if ($request->authority == '1') {
                        $authority = 'Orta Düzey Yetki';
                    } else {
                        $authority = 'Üst Düzey Yetki';
                    }

                    NotificationUser::query()
                        ->create([
                            'user_id' => $user->id,
                            'event_user_id' => Auth::id(),
                            'notification_id' => '3',
                            'notification_information' => $club->club_name . ',' . $role_name . '(' . $authority . ')'
                        ]);
                }

                //Member Settings Update
                $clubMemberUpdate->authority = $request->authority;
                $clubMemberUpdate->role = $request->role;
                $clubMemberUpdate->role_name = $role_name;
                $clubMemberUpdate->save();

                return response()->json([
                    'status' => '1',
                    'message' => 'Kullanıcının ayarları başarıyla kaydedildi . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }

        } else if ($request->which == 'delete') {
            try {

                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //User Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'authority' => '2'
                    ])->firstOrFail();

                //Request User Check
                $user = users::query()
                    ->where('username', $request->username)
                    ->firstOrFail();

                //Club Member Delete
                ClubMember::query()
                    ->where([
                        'club_id' => $club->id,
                        'user_id' => $user->id
                    ])
                    ->whereNotIn('role', [3])
                    ->firstOrFail()
                    ->delete();

                $request->session()->flash('deleteMemberSuccess', 'Kullanıcı kulüpten çıkarıldı.');

                return response()->json([
                    'status' => '1',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'searchUser') {
            try {
                //Club Check And Relationship Get
                $club = Club::query()
                    ->where('club_link', $link)
                    ->with(['members', 'invitation'])
                    ->firstOrFail();

                //User Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'authority' => '2'
                    ])
                    ->firstOrFail();


                $clubMembers = $club->members->pluck('user_id');
                $searchUsers = collect(users::where('username', 'LIKE', "%{$request->username}%")->get());

                $users = collect();
                $clubIntivatitonId = $club->invitation->pluck('user_id');

                //zaten üye
                $searchUsers->whereIn('id', $clubMembers)->each(function ($item, $key) use (&$users) {
                    $users->push([
                        'image' => $item->image,
                        'username' => $item->username,
                        'school_email_confirmation' => $item->school_email_confirmation,
                        'user_member' => '1'
                    ]);
                });

                //istek beklemede
                $searchUsers->whereNotIn('id', $clubMembers)->whereIn('id', $clubIntivatitonId)->each(function ($item, $key) use (&$users) {
                    $users->push([
                        'image' => $item->image,
                        'username' => $item->username,
                        'school_email_confirmation' => $item->school_email_confirmation,
                        'user_member' => '2'
                    ]);
                });

                //hiç istek atılmamış
                $searchUsers->whereNotIn('id', $clubMembers)->whereNotIn('id', $clubIntivatitonId)->each(function ($item, $key) use (&$users) {
                    $users->push([
                        'image' => $item->image,
                        'username' => $item->username,
                        'school_email_confirmation' => $item->school_email_confirmation,
                        'user_member' => '0'
                    ]);
                });


                return response()->json([
                    'status' => '1',
                    'users' => $users
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'clubInvitationAnswer') {
            try {
                //CLub Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //User Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'authority' => '2'
                    ])->firstOrFail();

                //Request User Check
                $user = users::query()
                    ->where('username', $request->username)
                    ->firstOrFail();

                //Club Invatiton Delete
                ClubInvitation::query()
                    ->where([
                        'user_id' => $user->id,
                        'club_id' => $club->id,
                        'event_user_id' => '0'
                    ])
                    ->firstOrFail()
                    ->delete();

                //Club Member Create
                if ($request->answer == '1') {
                    ClubMember::create([
                        'club_id' => $club->id,
                        'user_id' => $user->id,
                        'authority' => '0',
                        'role' => '0',
                        'role_name' => 'Normal Üye'
                    ]);

                    return response()->json([
                        'status' => '1',
                        'message' => 'Üye isteği kabul edildi . '
                    ]);
                } else if ($request->answer == '0') {
                    return response()->json([
                        'status' => '1',
                        'message' => 'Üye isteği silindi . '
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir sorun oluştu . '
                ]);
            }
        } else if ($request->which == 'searchMemberUser') {
            try {
                //Club Check And Relationship Get
                $club = Club::query()
                    ->where('club_link', $link)
                    ->with('members')
                    ->firstOrFail();

                //User Authority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'authority' => '2'
                    ])->firstOrFail();

                //Username Check
                $users = users::query()
                    ->where('username', 'LIKE', ' % ' . $request->username . ' % ')
                    ->pluck('id');

                //Role DESC
                $clubMembers = $club->members->whereIn('user_id', $users)->sortByDesc('role')->all();
                $clubMembersList = [];
                $count = 0;
                //Collect ??
                foreach ($clubMembers as $clubMember) {
                    $clubMembersList[$count] = [
                        'id' => $clubMember->id,
                        'role' => $clubMember->role,
                        'role_name' => $clubMember->role_name,
                        'username' => $clubMember->user->username,
                        'image' => $clubMember->user->image
                    ];
                    $count++;
                }

                return response()->json([
                    'status' => '1',
                    'clubMembersList' => $clubMembersList
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which === 'clubInvitationPost') {
            try {
                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                //User AUthority Check
                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'authority' => '2'
                    ])
                    ->firstOrFail();

                //Request Username Check
                $user = users::query()
                    ->where('username', $request->username)
                    ->firstOrFail();

                if (!$club->invitation->contains('user_id', $user->id) && $user->school_email_confirmation == '1') {
                    //Club Invatiton Create
                    $clubInvitation = ClubInvitation::create([
                        'user_id' => $user->id,
                        'club_id' => $club->id,
                        'event_user_id' => Auth::user()->id,
                        'invitation_who' => 'club'
                    ]);

                    //Notification Crate
                    NotificationUser::create([
                        'user_id' => $user->id,
                        'event_user_id' => Auth::user()->id,
                        'notification_id' => '2',
                        'notification_information' => $club->club_name . ',' . $clubInvitation->id,
                    ]);
                } else {
                    abort(404);
                }
                return response()->json([
                    'status' => '1',
                    'message' => 'İstek Gönderildi . '
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'transferPresidentCheckUsername') {
            try {
                // Get User
                $user = users::query()
                    ->select('id', 'username', 'name_surname', 'image', 'school_email_confirmation')
                    ->where('username', $request->username)
                    ->firstOrFail();

                return response()->json([
                    'status' => '1',
                    'user' => $user
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Böyle bir kullanıcı yok.'
                ]);
            }

        } else if ($request->which == 'transferPresidentConfirmationUser') {
            try {

                //Club Check
                $club = Club::query()
                    ->where('club_link', $link)
                    ->firstOrFail();

                // Check User School Email Confirmation
                $user = users::query()
                    ->where([
                        'id' => $request->userId,
                        'school_email_confirmation' => '1'
                    ])
                    ->firstOrFail();

                // If you do save yourself
                if (Auth::id() == $user->id) {
                    abort(404);
                }

                ClubMember::query()
                    ->where([
                        'user_id' => $user->id,
                        'club_id' => $club->id,
                    ])
                    ->firstOrFail()
                    ->update([
                        'role' => '3',
                        'authority' => '2',
                        'role_name' => 'Başkan'
                    ]);

                ClubMember::query()
                    ->where([
                        'user_id' => Auth::user()->id,
                        'club_id' => $club->id,
                        'role' => '3'
                    ])
                    ->firstOrFail()
                    ->update([
                        'role' => '0',
                        'authority' => '0',
                        'role_name' => 'Normal Üye'
                    ]);

                NotificationUser::query()
                    ->create([
                        'user_id' => $user->id,
                        'event_user_id' => Auth::id(),
                        'notification_id' => '3',
                        'notification_information' => $club->club_name . ',Başkan(Üst Düzey Yetki)'
                    ]);

                Session::flash('transferPresidentSuccess', 'Başkanlık değiştirme başarılı.');
                return response()->json([
                    'status' => '1'
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.Kullanıcının üye olduğuna emin olun.'
                ]);
            }
        }
    }

    public function kulupler_ayarlar_get($link)
    {
        try {
            $clubUserControl = $this->clubUserControl($link);
            return view('club.clubSettings', [
                'clubAuth' => $clubUserControl['clubAuth'],
                'club' => $clubUserControl['club'],
            ]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function kulupler_ayarlar_post($link, ClubSettingRequest $request)
    {
        try {
            //Club Check
            $club = Club::query()
                ->where('club_link', $link)
                ->firstOrFail();

            //Club Setting Check And Get
            $clubSettings = ClubSetting::query()
                ->where('club_id', $club->id)
                ->firstOrfail();

            //Club Member Authority Check
            ClubMember::query()
                ->where([
                    'user_id' => Auth::id(),
                    'club_id' => $club->id,
                    'authority' => '2'
                ])->firstOrFail();

            //image
            $imageName = $clubSettings->image;
            if ($request->file('image')) {
                //old image delete
                if (File::exists('./img/club/' . $imageName)) {
                    File::delete('./img/club/' . $imageName);
                }
                $image = $request->file('image');
                $fileImage = Image::make($image);
                $imageName = rand() . '.' . $image->clientExtension();
                $fileImage->resize(512, 512)->save(public_path('./img/club/' . $imageName));
            }

            //background_image
            $backgroundImageName = $clubSettings->background_image;
            if ($request->file('background_image')) {
                //old background_image delete
                if (File::exists('./img/club/' . $backgroundImageName)) {
                    File::delete('./img/club/' . $backgroundImageName);
                }
                $image = $request->file('background_image');
                $backgroundImageName = rand() . '.' . $image->clientExtension();
                $fileImage = Image::make($image);
                $fileImage->save(public_path('./img/club/' . $backgroundImageName));
            }

            //social media
            $social_media = collect([
                'facebook' => $request->facebook ?: '0',
                'instagram' => $request->instagram ?: '0',
                'twitter' => $request->twitter ?: '0',
                'linkedin' => $request->linkedin ?: '0',
            ]);

            //Web Url Protocol Control
            if ($request->web_url != null) {
                if (!Str::startsWith($request->web_url, 'https://') && !Str::startsWith($request->web_url, 'http://')) {
                    $request->web_url = 'http://' . $request->web_url;
                }
            }

            //Club Settings Update
            ClubSetting::query()
                ->where('club_id', $club->id)
                ->update([
                    'image' => $imageName,
                    'background_image' => $backgroundImageName,
                    'introduction_text' => $request->introduction_text ?: ' ',
                    'phone' => $request->phone ?: '0',
                    'email' => $request->email ?: '0',
                    'social_media' => $social_media->all(),
                    'web_url' => $request->web_url ?: '0',
                ]);

            $request->session()->flash('clubSettingsSuccess', 'Ayarlar kaydedildi');

            return response()->json([
                'status' => '1',
                'message' => 'Ayarlar kaydedildi.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => '0',
                'message' => 'Bir sorun oluştu.']);
        }
    }

    public function itiraflar_get()
    {
        //Confessions Get
        $confessions = Confession::query()
            ->with([
                'users'
            ])
            ->withCount('comments')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        //Sorgu sayısını arttırıyor.Buna mecburum :(
        foreach ($confessions->items() as $confession) {
            $confession->load([
                'comments' => function ($comments) {
                    $comments->orderByDesc('created_at')->take(3);
                },
                'comments.user'
            ]);
        }

        if (Auth::check()) {
            $anonymousUser = ConfessionUser::query()
                ->where('user_id', Auth::id())
                ->first();
        } else {
            $anonymousUser = null;
        }

        return view('confessions.confessionsPage', compact('anonymousUser', 'confessions'));
    }

    public function itiraflar_post(ConfessionPostRequest $request)
    {
        if ($request->which == 'confessionUserSettings') {
            try {
                $confessionsUser = ConfessionUser::where('user_id', Auth::user()->id);
                if ($confessionsUser->exists()) {
                    //Daha Önce Kayıt Var İse
                    $imageSelect = $confessionsUser->select('image')->first();
                    $imageName = $imageSelect->image;
                    if ($request->hasFile('image')) {
                        //Old Image Delete
                        if ($imageName != '0') {
                            File::delete('./img/confessions/' . $imageName);
                        }

                        //new image
                        $image = $request->file('image');
                        $fileImage = Image::make($image);
                        $imageName = rand() . '.' . $image->getClientOriginalExtension();
                        $fileImage->resize(512, 512)->save(public_path('./img/confessions/' . $imageName));
                    }

                    //Confession User Update
                    $confessionsUser->update([
                        'username' => $request->username,
                        'image' => $imageName
                    ]);
                } else {
                    //Daha Önce Kayıtı Yok İse
                    if (Auth::user()->register_email_confirmation == '0') {
                        abort(404);
                    }

                    //Image Update
                    $imageName = '0';
                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $fileImage = Image::make($image);
                        $imageName = rand() . '.' . $image->getClientOriginalExtension();
                        $fileImage->resize(512, 512)->save(public_path('./img/confessions/' . $imageName));
                    }

                    //Confession User Update
                    ConfessionUser::create([
                        'user_id' => Auth::id(),
                        'username' => $request->username,
                        'image' => $imageName,
                    ]);
                }

                return response()->json([
                    'status' => '1',
                    'message' => 'Ayarlar başarıyla kaydedildi.',
                    'image' => $imageName
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'confessionPost') {
            try {
                //Confession User Check
                $confessionUser = ConfessionUser::query()
                    ->where('user_id', Auth::user()->id)
                    ->firstOrFail();

                //Confession Create
                $confession = Confession::create([
                    'confession_user_id' => $confessionUser->id,
                    'confession_content' => $request->confession
                ]);

                //Post Create
                Post::create([
                    'post_type' => 'confession',
                    'post_id' => $confession->id
                ]);

                $request->session()->flash('postConfessionSuccess', 'İtirafın paylaşıldı.');
                return response()->json([
                    'status' => '1',
                    'message' => 'İtirafın paylaşıldı.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir sorun oluştu . '
                ]);
            }
        } else if ($request->which == 'commentPost') {
            try {
                //Confession User Check
                $confessionUser = ConfessionUser::query()
                    ->where('user_id', Auth::user()->id)
                    ->firstOrFail();

                //Comment Create
                ConfessionComment::create([
                    'confession_id' => $request->confession_id,
                    'confession_user_id' => $confessionUser->id,
                    'message' => $request->comment
                ]);

                return response()->json([
                    'status' => '1',
                    'message' => 'Yorum yapıldı.',
                    'image' => $confessionUser->image == '0' ? 'user_default.png' : $confessionUser->image,
                    'anonymous_username' => $confessionUser->username,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu . '
                ]);
            }
        } else if ($request->which == 'getMoreComment') {

            try {
                //Check Anonymous User
                if (Auth::guest()) {
                    abort(401);
                }

                //Get Comments
                $comments = ConfessionComment::query()
                    ->where([
                        ['confession_id', '=', $request->confessionId],
                        ['id', '<', $request->lastCommentId]
                    ])
                    ->with('user:id,username,image')
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get();

                return response()->json([
                    'status' => '1',
                    'comments' => $comments
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Bir hata oluştu.'
                ]);
            }

        }
    }

    public function lesson_notes_get()
    {
        $schoolSections = SchoolSection::query()
            ->with('school')
            ->paginate('12');

        return view('lessonsNotes.lessonsNotesPage', compact('schoolSections'));
    }

    public function lesson_notes_post(LessonNotesPostRequest $request)
    {
        if ($request->which == 'storeLessonNote') {

            try {
                if (Auth::guest()) {
                    abort(404);
                }
                $pathname = Storage::putFile('lessonNotes', $request->file('file'));
                SchoolLessonNoteForm::create([
                    'user_id' => Auth::id(),
                    'section_university_name' => $request->section_university_name,
                    'lesson_name' => $request->lesson_name,
                    'period' => $request->period,
                    'file_path' => $pathname
                ]);

                return response()->json([
                    'status' => '1'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => '0',
                    'Bir hata oluştu.'
                ]);
            }

        }
    }

    public function lesson_notes_in_page_get(Request $request, $sectionName)
    {
        $schoolSection = SchoolSection::query()
            ->where([
                'link' => $sectionName,
                'school_id' => $request->schoolId
            ])
            ->with([
                'lessons',
                'lessons.files',
                'lessons.files.user'
            ])
            ->firstOrFail();

        $schoolSection->lessons = collect($schoolSection->lessons);

        $periodFiles = $schoolSection->lessons->each(function ($lessonItem, $lessonKey) {
            $lessonItem->files = $lessonItem->files->each(function ($fileItem, $fileKey) use ($lessonItem) {
                return $fileItem->lesson_name = $lessonItem->lesson_name;
            });
        })
            ->map(function ($lesson) {
                return $lesson->files;
            })
            ->collapse()
            ->groupBy('period')
            ->sortKeys()
            ->all();

        return view('lessonsNotes.lessonNotesInPage', compact('schoolSection', 'periodFiles'));
    }
}
