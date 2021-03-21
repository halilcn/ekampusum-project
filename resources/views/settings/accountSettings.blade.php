<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
<div class="page_container">
    @include('global/page_header')
    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <div class="settings_container">
                @include('settings/settings_left')
                <div class="settings_contain">
                    <div class="account_settings">
                        <div class="settings_title">
                            Profili Düzenle
                            <a href="{{asset('/profil/'.Auth::user()->username)}}" class="profile_link">
                                profile git
                            </a>
                        </div>
                        <ul class="account_settings_ul">
                            <form id="accountForm" enctype="multipart/form-data" action="" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="which" value="0">
                                <li>
                                    <div class="account_settings_title">Profil Resmi</div>
                                    @if(Auth::user()->image=='0')
                                        <img class="account_image" src="../img/user/default.png">
                                    @else
                                        <img class="account_image" src="../img/user/{{Auth::user()->image}}">
                                    @endif
                                    <input type="file" name="image" id="image">
                                    <label class="file_image" for="image">Fotoğraf Yükle</label>
                                    <span class="file_image_mobile_warning">*Maksimum 2mb olmalıdır.</span>

                                </li>
                                <li>
                                    <div class="account_settings_title">Kullanıcı Adı
                                    </div>
                                    <span class="account_username">{{Auth::user()->username}}</span>
                                </li>
                                <li>
                                    <div class="account_settings_title">Ad-Soyad</div>
                                    <input name="name_surname" type="text" value="{{Auth::user()->name_surname}}">
                                </li>
                                <li>
                                    <div class="account_settings_title">Hakkında</div>
                                    <textarea
                                        @if(Auth::user()->about=='0') placeholder="Henüz eklenmemiş" @endif
                                    name="about">@if(Auth::user()->about!='0'){{Auth::user()->about}}@endif</textarea>
                                </li>
                                <li>
                                    <div class="account_settings_title">Kayıtlı E-posta Hesabın
                                        <div class="tooltip">
                                            <i class="fas fa-info-circle"></i>
                                            <div class="tooltip_text">Kayıt olduğun e-posta hesabı değiştirilemez.</div>
                                        </div>
                                    </div>
                                    <div class="input_mail_div">
                                        @if(Auth::user()->register_email_confirmation=='0')
                                            <input style="color: #7f7f7f;cursor: no-drop;" readonly
                                                   name="register_email" type="email"
                                                   value="{{Auth::user()->register_email}}">
                                            <div style="color: #eebe29;" class="email_warning">Onay maili gönderilmiş.
                                                <span onclick="accountPost('email')">Tekrar Gönder</span>
                                            </div>
                                        @else
                                            <input name="register_email" style="color: #7f7f7f;cursor: no-drop;"
                                                   readonly type="email" value="{{Auth::user()->register_email}}">
                                            <div style="color: #32C682;" class="email_warning">
                                                <div class="animated">
                                                    <script
                                                        src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                    <lottie-player
                                                        src="https://assets7.lottiefiles.com/packages/lf20_UDFLD9.json"
                                                        background="transparent" speed="1"
                                                        style="width: 35px; height:35px;" loop
                                                        autoplay></lottie-player>
                                                </div>
                                                E-posta Onaylanmış
                                            </div>
                                        @endif
                                    </div>
                                </li>
                                <br>
                                <li>
                                    <div class="account_settings_title">Okul E-posta Hesabın
                                        <div class="tooltip">
                                            <i class="fas fa-info-circle"></i>
                                            <div class="tooltip_text">Okul e-posta hesabı platformu daha iyi kullanmanı
                                                sağlar.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input_mail_div">
                                        @if(Auth::user()->register_email_confirmation=='0' && Auth::user()->school_email=='0')
                                            <input name="school_email" type="email" value="" style="cursor: no-drop;"
                                                   disabled>
                                            <div style="color: #ee3737;" class="email_warning school_email_warning">İlk
                                                önce kayıtlı e-posta
                                                hesabını onaylamalısın.
                                            </div>
                                        @elseif(Auth::user()->register_email_confirmation=='0' && Auth::user()->school_email!='0')
                                            <div style="color: #ee3737;" class="email_warning school_email_warning">
                                                <input name="school_email" type="email"
                                                       value="{{Auth::user()->register_email}}"
                                                       style="opacity:0.7;cursor: no-drop;"
                                                       disabled>
                                            </div>
                                        @elseif(Auth::user()->register_email_confirmation=='1' && Auth::user()->school_email=='0')
                                            <input name="school_email" type="email" value=""
                                                   placeholder="Henüz eklenmemiş">
                                            <div style="color: #ee3737;" class="email_warning school_email_warning">Bir
                                                okul hesabı (sonu
                                                @ktun.edu.tr veya selcuk.edu.tr ile biten) kayıt etmelisin.
                                            </div>
                                        @elseif(Auth::user()->register_email_confirmation=='1' && Auth::user()->school_email_confirmation=='0')
                                            <input name="school_email" type="email"
                                                   value="{{Auth::user()->school_email}}">
                                            <div style="color: #eebe29;" class="email_warning school_email_warning">Okul
                                                e-postan
                                                onaylanmamış.
                                                <span onclick="accountPost('email')">Onayla</span>
                                            </div>
                                        @elseif(Auth::user()->register_email_confirmation=='1' && Auth::user()->school_email_confirmation=='1')
                                            <input style="color: #7f7f7f;cursor: no-drop;" name="school_email"
                                                   type="email" readonly
                                                   value="{{Auth::user()->school_email}}">
                                            <div style="color: #32C682;" class="email_warning school_email_warning">
                                                <div class="animated">
                                                    <script
                                                        src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                    <lottie-player
                                                        src="https://assets7.lottiefiles.com/packages/lf20_UDFLD9.json"
                                                        background="transparent" speed="1"
                                                        style="width: 35px; height:35px;" loop
                                                        autoplay></lottie-player>
                                                </div>
                                                E-posta Onaylanmış
                                            </div>
                                        @endif

                                    </div>
                                </li>
                            </form>
                            <li>
                                <button onclick="accountPost('accountSave')" class="account_save">Kaydet</button>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
    @include('global.footer')
</div>
</body>
</html>
