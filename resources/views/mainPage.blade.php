<!doctype html>
<html>
<head>
    @include('global/page_links')
    @if($confessionsUser)
        <meta name="checkConfessionUser" content="true">
        <meta name="confessionUserImage" content="{{$confessionsUser->image}}">
        <meta name="confessionUserUsername" content="{{$confessionsUser->username}}">
    @else
        <meta name="checkConfessionUser" content="false">
    @endif
</head>

<body>
@if(Session::has('userClubExist'))
    <script>
        Notiflix.Notify.Success('Kulüpten Ayrıldın.');
    </script>
@endif
<script>
    //Control Authority Value
    var authCheck = 0;
    var registerEmailCheck = 0;
    var schoolEmailCheck = 0;

    @auth
        authCheck = 1;
    @if(Auth::user()->register_email_confirmation == '1')
        registerEmailCheck = 1;
    @endif

        @if(Auth::user()->school_email_confirmation == '1')
        schoolEmailCheck = 1;
    @endif

    @endauth
</script>
<div class="page_container">
    @auth()
        <div class="popup_container discussion_new_popup">
            <div class="popup_filter"></div>
            <div class="discussion_new">
                <div class="cancel"></div>
                <div class="popup_title">
                    Yeni Tartışma
                </div>
                <ul>
                    <li class="discussion_new_ul_header">
                        @if(Auth::user()->image==0)
                            <img src="/img/user/default.png">
                        @else
                            <img src="/img/user/{{Auth::user()->image}}">
                        @endif
                        <div class="username">
                            {{Auth::user()->username}}
                        </div>
                    </li>
                    <li>
                        <form id="discussionForm">
                            {{csrf_field()}}
                            <input type="hidden" name="which" value="newDiscussionSend">
                            <textarea name="title" placeholder="Başlık"></textarea>
                            <textarea name="subject" placeholder="Konu"></textarea>
                            <button class="button_not_click" type="button" onclick="discussionSend()">
                                Paylaş
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="popup_container confession_new_popup">
            <div class="popup_filter"></div>
            <div class="confession_new">
                <div class="cancel"></div>
                <div class="popup_title">
                    Yeni İtiraf
                </div>
                <ul>
                    <li class="anonymous_profile">
                        <img src="">
                        <div class="username">
                        </div>
                        <div onclick="anonymousSettingsShow(true)" class="anonymous_settings_button">
                            <i class="fas fa-user-cog"></i>
                            ayarlar
                        </div>
                    </li>
                    <li class="anonymous_settings">
                        <form onsubmit="return false" id="confessionsUserSettingsForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="which" value="confessionUserSettings">
                            <div class="title">
                                Anonim Profili
                            </div>
                            <div class="content">
                                <div class="image_container">
                                    <img src="../img/confessions/user_default.png">
                                    <input name="image" type="file" id="anonymous_image">
                                    <label for="anonymous_image">
                                        Değiştir
                                    </label>
                                </div>
                                <input name="username" type="text" value="" placeholder="Takma Ad">
                                <div class="buttons">
                                    <button onclick="anonymousSettingsShow(false)" class="back_button">
                                        geri
                                    </button>
                                    <button class="save" type="submit" onclick="confessionUserSettingsPost()">
                                        Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="new_confession">
                        <form id="confessionForm">
                            @csrf
                            <input type="hidden" name="which" value="confessionPost">
                            <textarea placeholder="İtiraf" name="confession"></textarea>
                            <button class="button_not_click" type="button" onclick="newConfessionsPost()">
                                Paylaş
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @if(Auth::user()->school_email_confirmation == '1')
            <div class="popup_container global_new_popup global_popup_announcement_container">
                <div class="popup_filter"></div>
                <div class="club_global_popup global_popup_announcement">
                    <div class="club_global_popup_not_authorization">
                        <script
                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_9LR70U.json"
                                       background="transparent" speed="1" style="width: 170px; height: 170px;" loop
                                       autoplay></lottie-player>
                        <span>
                            Herhangi bir kulübe üye değilsin ya da kulüpte yetkin yok.
                        </span>
                    </div>
                    <div class="cancel"></div>
                    <div class="popup_title">Yeni Duyuru-Haber</div>
                    <ul class="club_global_popup_ul">
                        <form id="clubNewAnnouncementsAndNewsForm" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="which" value="add">
                            <li>
                                <select name="club_name">
                                </select>
                            </li>
                            <li>
                                <div class="club_global_popup_title">Başlık</div>
                                <input name="title" type="text">
                            </li>
                            <li>
                                <div class="club_global_popup_title">Kapak Fotoğrafı</div>
                                <input name="title_image" type="file" id="title_image">
                                <label for="title_image">Fotoğraf Yükle</label>
                            </li>
                            <li>
                                <div class="club_global_popup_title">İçerik</div>
                                <textarea onkeydown="textareaHeight($(this),200)" name="subject"></textarea>
                            </li>
                            <li>
                                <div class="club_global_popup_title">Fotoğraflar</div>
                                <input type="file" name="images[]" id="images" multiple>
                                <label for="images">Fotoğraf Yükle</label>
                            </li>
                        </form>
                        <li>
                            <button class="button_not_click"
                                    onclick="globalEventsAdd('duyuru-haber','#clubNewAnnouncementsAndNewsForm')">
                                Paylaş
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="popup_container global_new_popup global_popup_event_container">
                <div class="popup_filter"></div>
                <div class="club_global_popup global_popup_event">
                    <div class="club_global_popup_not_authorization">
                        <script
                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_9LR70U.json"
                                       background="transparent" speed="1" style="width: 170px; height: 170px;" loop
                                       autoplay></lottie-player>
                        <span>
                            Herhangi bir kulübe üye değilsin ya da kulüpte yetkin yok.
                        </span>
                    </div>
                    <div class="cancel"></div>
                    <div class="popup_title">Yeni Etkinlik</div>
                    <ul class="club_global_popup_ul">
                        <form id="clubEventsForm" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="which" value="add">
                            <li>
                                <select name="club_name">
                                </select>
                            </li>
                            <li>
                                <div class="club_global_popup_title">Tarih</div>
                                <input name="date" type="datetime-local">
                            </li>
                            <li>
                                <div class="club_global_popup_title">Konum</div>
                                <input name="location" type="text">
                                <div class="club_global_popup_location">
                                    <input class="inp-cbx" id="location_online" type="checkbox" name="location_online"
                                           value="1"
                                           style="display: none">
                                    <label class="cbx" for="location_online">
                                      <span>
                                        <svg width="9px" height="6px" viewBox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                     </svg>
                                     </span>
                                        <span>
                                      Sadece Online
                                        </span>
                                    </label>
                                </div>
                            </li>
                            <li>
                                <div class="club_global_popup_title">Başlık</div>
                                <input name="title" type="text">
                            </li>
                            <li>
                                <div class="club_global_popup_title">Kapak Fotoğrafı</div>
                                <input name="title_image" type="file" id="title_image_2">
                                <label for="title_image_2">Fotoğraf Yükle</label>
                            </li>
                            <li>
                                <div class="club_global_popup_title">İçerik</div>
                                <textarea onkeydown="textareaHeight($(this),200)" name="subject"></textarea>
                            </li>
                            <li>
                                <div class="club_global_popup_title">Fotoğraflar</div>
                                <input type="file" name="images[]" id="images_2" multiple>
                                <label for="images_2">Fotoğraf Yükle</label>
                            </li>
                        </form>
                        <li>
                            <button class="button_not_click" onclick="globalEventsAdd('etkinlik','#clubEventsForm')">
                                Paylaş
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    @endauth
    @include('global/page_header')
    <div class="page_bottom_content">
        @include('global/page_left_menu')
        <div class="page_content">
            @if(\Illuminate\Support\Facades\Session::has('newUser'))
                <script>
                    Notiflix.Report.Success('Teşekkür Ederiz!', 'Kayıt olduğunuz e-posta hesabını kontrol etmeyi unutmayın!', 'Tamam');
                </script>
            @endif
            <div class="main_page">
                <div class="main_page_inner_left">
                    @auth
                        <ul class="main_page_post_create">
                            <li class="create_text">
                                @if(Auth::user()->image == '0')
                                    <img src="../img/user/default.png">
                                @else
                                    <img src="../img/user/{{Auth::user()->image}}">
                                @endif
                                <div class="text">
                                    Merhaba {{Auth::user()->username}},
                                    Bir
                                    <span>
                                     şey
                                    </span>
                                    paylaş.
                                </div>
                            </li>
                            <li class="create_buttons">
                                @php
                                    $registerEmailCheck='';
                                    $schoolEmailCheck='';
                                        if (auth()->check()){

                                            if (auth()->user()->register_email_confirmation=='0'){
                                                      $registerEmailCheck=' not_click';
                                                       $schoolEmailCheck=' not_click';
                                                   }

                                                if (auth()->user()->school_email_confirmation=='0'){
                                                       $schoolEmailCheck=' not_click';
                                                   }
                                                }

                                @endphp
                                <div class="discussion{{$registerEmailCheck}}">
                                    <i class="fas fa-comments"></i>
                                    Tartışma
                                </div>
                                <div
                                    class="announcement{{$schoolEmailCheck}}">
                                    <i class="fas fa-bullhorn"></i>
                                    Duyuru-Haber
                                </div>
                                <div
                                    class="event{{$schoolEmailCheck}}">
                                    <i class="fas fa-calendar-alt"></i>
                                    Etkinlik
                                </div>
                                <div class="confession{{$registerEmailCheck}}">
                                    <i class="far fa-eye-slash"></i>
                                    İtiraf
                                </div>
                            </li>
                        </ul>
                    @endauth
                    <ul class="main_page_post_list">
                    </ul>
                    <div class="wrapper">
                        <div class="wrapper-cell">
                            <div class="image"></div>
                            <div class="text">
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                            </div>
                        </div>
                        <div class="wrapper-cell">
                            <div class="image"></div>
                            <div class="text">
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                            </div>
                        </div>
                        <div class="wrapper-cell">
                            <div class="image"></div>
                            <div class="text">
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                                <div class="text-line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('global.footer')
</div>
@include('global.page_mobile_bottom_menu')
</body>
</html>
