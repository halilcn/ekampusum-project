<!doctype html>
<html>
<head>
    @include('global/page_links')
    <meta name="image" content="">
    <meta name="background_image" content="">
</head>
<body>
<script>
    $(document).ready(function () {
        if (screen.width < 720) {
            $(".mobile_div").css('display', 'block');
        }
    });
</script>

@if(Session::has('clubSettingsSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('clubSettingsSuccess')}}');
    </script>
@endauth
<div class="page_container">
    @php($social_media=json_decode($club->settings->social_media))
    @include('global/page_header')
    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <div class="club_ın_page_container">
                @include('club.global.clubInPageHeader')
                <div class="club_ın_page_bottom_contain">
                    <div class="club_ın_page_bottom_contain_content">
                        <div class="club_ın_page_bottom_contain_content_inner">
                            <form id="clubSettingsForm" onsubmit="return false;">
                                @csrf
                                <ul class="club_settings_ul">
                                    <li onclick="window.location='/kulupler/'+ window.location.pathname.split('/')[2]"
                                        class="club_settings_back">
                                        <i style="position:relative;top: 2px;right: 3px;"
                                           class="fas fa-chevron-left"></i>
                                        geri
                                    </li>
                                    <li>
                                        <div class="club_settings_ul_title">
                                            Genel Ayarlar
                                        </div>
                                        <ul class="club_settings_ul_content">
                                            <li>
                                                <div class="club_settings_ul_content_title">
                                                    Profil Fotoğrafı
                                                </div>
                                                <div class="club_settings_ul_content_inner">
                                                    <input data-content="addImage"
                                                           onchange="clubSettingsImage(this,'image')" type="file"
                                                           id="club_image" name="image">
                                                    <label for="club_image">
                                                        Fotoğraf Değiştir
                                                    </label>
                                                    <span onclick="clubSettingsImageSelectedDelete(this,'image')"
                                                          class="club_settings_ul_content_inner_image_delete">
                                                        kaldır
                                                    </span>
                                                    <span class="club_settings_ul_content_inner_image_warning">
                                                       Canlı hali gösteriliyor
                                                    </span>
                                                </div>
                                            </li>
                                        </ul>
                                        <ul class="club_settings_ul_content">
                                            <li>
                                                <div class="club_settings_ul_content_title">
                                                    Arkaplan Fotoğrafı
                                                </div>
                                                <div class="club_settings_ul_content_inner">
                                                    <input onchange="clubSettingsImage(this,'backgroundImage')"
                                                           type="file" id="club_background_image"
                                                           name="background_image">
                                                    <label for="club_background_image">
                                                        Fotoğraf Değiştir
                                                    </label>
                                                    <span
                                                        onclick="clubSettingsImageSelectedDelete(this,'background_image')"
                                                        class="club_settings_ul_content_inner_image_delete">
                                                        kaldır
                                                    </span>
                                                    <span class="club_settings_ul_content_inner_image_warning">
                                                       Canlı hali gösteriliyor
                                                    </span>
                                                </div>
                                            </li>
                                        </ul>
                                        <ul class="club_settings_ul_content">
                                            <li>
                                                <div class="club_settings_ul_content_title">
                                                    Hakkında
                                                </div>
                                                <div class="club_settings_ul_content_inner">
                                                    <textarea
                                                        name="introduction_text">{{$club->settings->introduction_text}}</textarea>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <div class="club_settings_ul_title">
                                            İletişim Ayarları
                                        </div>
                                        <ul class="club_settings_ul_content">
                                            <li>
                                                <div class="club_settings_ul_content_title">
                                                    Telefon
                                                </div>
                                                <div class="club_settings_ul_content_inner">
                                                    <input type="tel" name="phone"
                                                           value="{{$club->settings->phone!='0'?$club->settings->phone:''}}">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="club_settings_ul_content_title">
                                                    E-mail
                                                </div>
                                                <div class="club_settings_ul_content_inner">
                                                    <input type="email" name="email"
                                                           value="{{$club->settings->email!='0'?$club->settings->email:''}}">
                                                </div>
                                            </li>
                                            <li>
                                                <div class="club_settings_ul_content_title">
                                                    Sosyal Medya Hesapları
                                                    <span
                                                        style="font-family: 'Montserrat', sans-serif;display: block;font-size: 11px;">
                                                        (Lütfen tüm linki yazınız.)
                                                    </span>
                                                </div>
                                                <ul class="club_settings_ul_social_media_ul">
                                                    <ul class="club_settings_ul_social_media_add_ul">
                                                        <li>
                                                            Ekle:
                                                        </li>
                                                        @if($social_media->facebook=='0')
                                                            <li onclick="clubSettingsSocialMediaInput(this,'facebook','add')">
                                                                <i class="fab fa-facebook-f"></i>
                                                            </li>
                                                        @endif
                                                        @if($social_media->instagram=='0')
                                                            <li onclick="clubSettingsSocialMediaInput(this,'instagram','add')">
                                                                <i class="fab fa-instagram"></i>
                                                            </li>
                                                        @endif
                                                        @if($social_media->twitter=='0')
                                                            <li onclick="clubSettingsSocialMediaInput(this,'twitter','add')">
                                                                <i class="fab fa-twitter"></i>
                                                            </li>
                                                        @endif
                                                        @if($social_media->linkedin=='0')
                                                            <li onclick="clubSettingsSocialMediaInput(this,'linkedin','add')">
                                                                <i class="fab fa-linkedin-in"></i>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    @if($social_media->facebook!='0')
                                                        <li
                                                            class="club_settings_ul_content_inner club_settings_ul_content_inner_social_media">
                                                            <i class="fab fa-facebook-f"></i>
                                                            <input type="text" name="facebook"
                                                                   value="{{$social_media->facebook}}">
                                                            <span
                                                                onclick="clubSettingsSocialMediaInput(this,'facebook','delete')">
                                                             <i class="fas fa-trash"></i>
                                                        </span>
                                                        </li>
                                                    @endif
                                                    @if($social_media->instagram!='0')
                                                        <li
                                                            class="club_settings_ul_content_inner club_settings_ul_content_inner_social_media">
                                                            <i class="fab fa-instagram"></i>
                                                            <input type="text" name="instagram"
                                                                   value="{{$social_media->instagram}}">
                                                            <span
                                                                onclick="clubSettingsSocialMediaInput(this,'instagram','delete')">
                                                             <i class="fas fa-trash"></i>
                                                        </span>
                                                        </li>
                                                    @endif
                                                    @if($social_media->twitter!='0')
                                                        <li
                                                            class="club_settings_ul_content_inner club_settings_ul_content_inner_social_media">
                                                            <i class="fab fa-twitter"></i>
                                                            <input type="text" name="twitter"
                                                                   value="{{$social_media->twitter}}">
                                                            <span
                                                                onclick="clubSettingsSocialMediaInput(this,'twitter','delete')">
                                                             <i class="fas fa-trash"></i>
                                                        </span>
                                                        </li>
                                                    @endif
                                                    @if($social_media->linkedin!='0')
                                                        <li
                                                            class="club_settings_ul_content_inner club_settings_ul_content_inner_social_media">
                                                            <i class="fab fa-linkedin-in"></i>
                                                            <input type="text" name="linkedin"
                                                                   value="{{$social_media->linkedin}}">
                                                            <span
                                                                onclick="clubSettingsSocialMediaInput(this,'linkedin','delete')">
                                                             <i class="fas fa-trash"></i>
                                                        </span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                            <li>
                                                <div class="club_settings_ul_content_title">
                                                    İnternet Sitesi
                                                </div>
                                                <div class="club_settings_ul_content_inner">
                                                    <input type="text" name="web_url"
                                                           value="{{$club->settings->web_url!='0'?$club->settings->web_url:''}}">
                                                </div>
                                            </li>
                                            <li>
                                                <button onclick="clubSettingsPost()">
                                                    Kaydet
                                                </button>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    @include('global.footer')
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
