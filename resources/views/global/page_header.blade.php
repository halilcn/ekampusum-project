<meta name="clubGet" content="0">
<div class="page_header_container">
    <div class="page_header">
        <div class="mobile_left_sidebar_container">
            <div class="mobile_left_sidebar_filter"></div>
            <div class="mobile_left_sidebar">
                <div class="mobile_left_sidebar_top">
                    <i class="fas fa-times"></i>
                </div>
                <ul class="mobile_left_sidebar_ul">
                    @auth
                        <li class="profile">
                            <a href="{{asset('profil/'.Auth::user()->username)}}">
                                @if(Auth::user()->image == '0')
                                    <img src="../img/user/default.png">
                                @else
                                    <img src="{{'img/user/'.Auth::user()->image}}">
                                @endif
                                <div class="user_info">
                                    <div class="username">
                                        {{Auth::user()->username}}
                                        @if(Auth::user()->school_email_confirmation == '1')
                                            <div style="top: 2px;margin-right: 10px;" class="tooltip">
                                                <i class="verification"></i>
                                                <div class="tooltip_text verification_tooltip_text">
                                                    Okul e-postası onaylanmış.
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="name">
                                        {{Auth::user()->name_surname}}
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endauth
                <!--
                    <li>
                            <a href="/aday-ogrenci">
                                <i class="fas fa-user-graduate"></i>
                                Aday Öğrenci
                            </a>
                        </li>-->
                    <li>
                        <a href="/hakkimizda">
                            <i class="fas fa-info-circle"></i>
                            Hakkımızda
                        </a>
                    </li>
                    <li>
                        <a href="/hizmet-kosullari">
                            <i class="fas fa-book"></i>
                            Hizmet Koşulları
                        </a>
                    </li>
                    <li>
                        <a href="/yardim-destek">
                            <i class="fas fa-question-circle"></i>
                            Yardım & Destek
                        </a>
                    </li>
                    @auth
                        <li class="logout">
                            <a href="/cikis">
                                <i class="fas fa-door-open"></i>
                                Çıkış
                            </a>
                        </li>
                    @endauth
                    <li class="copyright">
                        <img src="/img/logo.png">
                        ekampusum.com'un hiçbir üniversite ile resmi bağı yoktur.
                        <span>
                            Copyright © 2021 ekampusum
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="page_header_logo">
            <a href="/">
                <img src="/img/logo.png">
            </a>
        </div>
        @auth()
            @php($count=\App\Models\NotificationUser::where(['user_id'=>Auth::user()->id,'notification_view'=>'0'])->count())
        @endauth
        <i onclick="mobileMenu()" class="fas fa-bars"></i>
        <div class="page_header_menu">
            @if(\Illuminate\Support\Facades\Auth::guest())
                <ul>
                    <!--
                      <a href="/aday-ogrenci">
                         <li>Aday Öğrenci</li>
                     </a>-->
                    <a href="/giris-yap">
                        <li>
                            Giriş Yap
                        </li>
                    </a>
                    <a href="/kayit-ol">
                        <li class="register_button">
                            Kayıt Ol
                        </li>
                    </a>
                </ul>
            @else
                <ul class="margin">
                    <div class="page_header_notifications_div">
                        <i onclick="showNotifications()" class="fas fa-bell"></i>
                        <div class="page_header_notifications_count">
                            {{$count == '0' ? '' :$count }}
                        </div>
                        <div class="page_header_notifications_bottom notifications_desktop_bottom">
                            <div class="page_header_notifications_fixed">
                                <div class="page_header_notifications_fixed_title">
                                    Bildirimler
                                </div>
                                <div class="page_header_notifications_fixed_events">
                                    <i onclick="notificationsGet('update')" style="color: #474747;"
                                       class="fas fa-sync-alt"></i>
                                    <i onclick="notificationsAllDelete()" style="color: #de3838;"
                                       class="fas fa-trash-alt"></i>
                                </div>
                            </div>
                            <ul class="page_header_notifications_ul">
                            </ul>
                        </div>
                    </div>
                    <li onclick="showUserDropdown()" class="page_header_user">
                        <div class="information">
                            @if(Auth::user()->image=='0')
                                <img src="/img/user/default.png">
                            @else
                                <img src="/img/user/{{Auth::user()->image}}">
                            @endif
                            <div class="username">
                                {{\Illuminate\Support\Facades\Auth::user()->username}}
                                <i style="color: #717171;" class="fas fa-caret-down"></i>
                            </div>
                        </div>
                        <ul>
                            <a href="/ayarlar/hesap">
                                <li>
                                    <i class="fas fa-user-cog"></i>
                                    Profili Düzenle
                                </li>
                            </a>
                            <a href="/kuluplerim">
                                <li>
                                    <i class="fas fa-building"></i>
                                    Kulüplerim
                                </li>
                            </a>
                            <a href="/ayarlar/sifre">
                                <li>
                                    <i class="fas fa-cog"></i>
                                    Ayarlar
                                </li>
                            </a>
                            <li class="line"></li>
                            <a href="/cikis">
                                <li>
                                    <i class="fas fa-door-open"></i>
                                    Çıkış
                                </li>
                            </a>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</div>

@if(Auth::check() && Auth::user()->register_email_confirmation=='0')
    <div class="warning_class">
        <div class="warning">
            <div class="text">
                <div>
                    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                    <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_dVJMow.json"
                                   background="transparent"
                                   speed="1" style="width: 30px; height: 30px;" loop autoplay></lottie-player>
                </div>
                <span>
                Kayıt olduğunuz e-posta hesabına e-mail gönderdik.Lütfen onaylama işlemini tamamlayınız.
               </span>
            </div>
            <div onclick="pageLinksSend('emailAgainSend','{{csrf_token()}}')" class="email_confirmation_again_send">
                Tekrar Gönder
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>
@endif

