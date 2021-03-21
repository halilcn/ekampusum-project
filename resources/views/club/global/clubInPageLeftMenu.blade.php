<div class="club_ın_page_bottom_contain_left_menu">
    <ul class="left_menu_container">
        <li class="club_settings">
            @if($clubAuth['clubAuthority']=='1')
                <a href="/kulupler/{{Request::segment(2)}}/ayarlar" class="club_ın_page_settings">
                    <i class="fas fa-cog"></i>
                </a>
            @endif
        </li>
        <li class="name">
            {{$club->club_name}}
        </li>
        <li class="social">
            @php($social_media=json_decode($club->settings->social_media))
            @if($club->settings->web_url!='0')
                <div class="link">
                    <a target="_blank" href="{{$club->settings->web_url}}">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            @endif
            @if($social_media->instagram!='0')
                <div class="link">
                    <a target="_blank" href="{{$social_media->instagram}}"><i class="fab fa-instagram"></i></a>
                </div>
            @endif
            @if($social_media->facebook!='0')
                <div class="link">
                    <a target="_blank" href="{{$social_media->facebook}}"><i class="fab fa-facebook-f"></i></a>
                </div>
            @endif
            @if($social_media->twitter!='0')
                <div class="link">
                    <a target="_blank" href="{{$social_media->twitter}}"><i class="fab fa-twitter"></i></a>
                </div>
            @endif
            @if($social_media->linkedin!='0')
                <div class="link">
                    <a target="_blank" href="{{$social_media->linkedin}}"><i class="fab fa-linkedin-in"></i></a>
                </div>
            @endif
            @if($club->settings->phone!='0')
                <div class="link">
                    <a href="tel:{{$club->settings->phone}}">
                        <i class="fas fa-phone-alt"></i>
                    </a>
                </div>
            @endif
            @if($club->settings->email!='0')
                <div class="link">
                    <a href="mailto:{{$club->settings->email}}">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            @endif
        </li>
        <li class="information">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people"
                     viewBox="0 0 16 16">
                    <path
                        d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                </svg>
            </div>
            <div class="content_container">
                <div class="title">
                    Toplam Kullanıcı
                </div>
                <div class="content">
                    {{$club->members_count}} kişi
                </div>
            </div>
        </li>
        <li class="information">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                     class="bi bi-calendar4-event" viewBox="0 0 16 16">
                    <path
                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
                    <path d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                </svg>
            </div>
            <div class="content_container">
                <div class="title">
                    Toplam Etkinlik
                </div>
                <div class="content">
                    {{$club->events_count}} adet
                </div>
            </div>
        </li>
        <li class="user_action">
            @if(Auth::check())
                @if($clubAuth['clubMember']=='0')
                    @if(Auth::user()->school_email_confirmation=='0')
                        <div class="club_join club_join_school_email_warning">
                            Kulübe Katıl
                        </div>
                        <div class="club_join_school_email_warning_text">
                            <i class="fas fa-exclamation-circle"></i>
                            Okul e-postası gerekli.<a href="{{url('/ayarlar/hesap')}}">Şimdi Yap</a>
                        </div>
                    @else
                        @if($clubInvitation==true)
                            @if($clubInvitation->invitation_who == 'club')
                                <div class="club_invitation_answer">
                                    <div class="title">
                                        Sana önceden kulüp daveti gönderilmiş.Kulüp Davetini;
                                    </div>
                                    <div class="buttons">
                                        <div class="join_button"
                                             onclick="clubInvitationAnswerPost(0,'1',{{$clubInvitation->id}})">
                                            Kabul Et
                                        </div>
                                        <div class="delete_button"
                                             onclick="clubInvitationAnswerPost(0,'0',{{$clubInvitation->id}})">
                                            Reddet
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="club_invitation_status">
                                    Kulübe İstek Atılmış
                                </div>
                            @endif
                        @else
                            <div onclick="clubJoinPost()" class="club_join">
                                Kulübe Katıl
                            </div>
                        @endif
                    @endif
                @endif
                @if($clubAuth['clubAuthorityAdmin']=='0' && $clubAuth['clubMember']=='1')
                    <div onclick="clubExit()" class="club_exit">
                        Kulüpten Çık
                    </div>
                @endif
            @else
                <div class="user_action_guest">
                    Katılmak İçin Giriş Yapmak Gerekir.
                </div>
            @endif
        </li>
    </ul>
</div>
