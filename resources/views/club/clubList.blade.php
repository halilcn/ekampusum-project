<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>

<body>
@if(Session::has('userClubExist'))
    <script>
        Notiflix.Notify.Success('Kulüpten Ayrıldın.');
    </script>
@endif

@if(Session::has('newClubSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('newClubSuccess')}}');
    </script>
@endif


<div class="page_container">
    @auth()
        @if(Auth::user()->school_email_confirmation==1)
            <div class="popup_container new_club_popup">
                <div class="popup_filter"></div>
                <div class="new_club_form">
                    <div class="cancel"></div>
                    <div class="popup_title">Kulüp Ekleme İstek Formu</div>
                    <ul class="new_club_ul">
                        <form id="newClubForm" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="which" value="newClub">
                            <li>
                                <div class="new_club_ul_title">Kullanıcı Adı</div>
                                <input name="club_user_username" readonly value="{{Auth::user()->username}}"
                                       type="text">
                            </li>
                            <li>
                                <div class="new_club_ul_title">Bilgilendirileceğin E-mail Hesabı</div>
                                <input name="club_user_email" readonly value="{{Auth::user()->register_email}}"
                                       type="text">
                            </li>
                            <li>
                                <div class="new_club_ul_title">Kulüp Logosu
                                    <div class="tooltip">
                                        <i class="fas fa-info-circle"></i>
                                        <div class="tooltip_text">
                                            Logonun düzgün gözükmesi için kare(örn 500x500)
                                            olmasına dikkat edin.
                                        </div>
                                    </div>
                                </div>
                                <input id="club_image" name="club_logo" type="file">
                                <label for="club_image">Logo Yükle</label>
                            </li>
                            <li>
                                <div class="new_club_ul_title">
                                    Kulüp İsmi
                                    <div class="tooltip">
                                        <i class="fas fa-info-circle"></i>
                                        <div class="tooltip_text">
                                            Sonradan değiştirilemez.
                                        </div>
                                    </div>
                                </div>
                                <input style="text-transform: uppercase;" name="club_name" type="text">
                            </li>
                            <li>
                                <div class="new_club_ul_title">Sosyal Medya Hesap Linki
                                    <div class="tooltip">
                                        <i class="fas fa-info-circle"></i>
                                        <div class="tooltip_text">
                                            Verdiğiniz linkteki hesabın aktif olarak kullanıldığından emin olun.
                                        </div>
                                    </div>
                                </div>
                                <input name="club_social" type="text">
                            </li>
                        </form>
                        <li>
                            <button onclick="newClubFormPost()">Gönder</button>
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
            <div class="page_content_header">
                <div class="page_content_title">Kulüpler</div>
                @auth()
                    <div
                        class="club_new_button @if(Auth::user()->school_email_confirmation=='0') club_new_button_disabled @endif ">
                        Yeni Kulüp <i class="far fa-file-alt"></i>
                        @if(Auth::user()->school_email_confirmation=='0')
                            <div class="club_new_button_warning">
                                <i class="fas fa-exclamation-circle"></i>
                                Okul e-postası gerekli
                            </div>
                        @endif
                    </div>
                @endauth
                <input placeholder="Kulüp Ara..." class="club_search_input" type="text">
            </div>
            <div class="mobile_club_search">
                <input placeholder="Kulüp Ara..." type="text">
            </div>
            <ul class="club_list_ul">
                <li class="not_search_club">
                    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                    <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_4azG0q.json"
                                   background="transparent" speed="1" style="width: 20px; height: 20px;" loop
                                   autoplay></lottie-player>
                    Böyle bir kulüp yok.
                </li>
                @foreach($clubs as $club)
                    <a href="{{route('club.page',[$club->club_link])}}">
                        <li>
                            <img src="{{asset('/img/club/'.$club->settings->image)}}">
                            <div class="information">
                                <div class="name">{{$club->club_name}}</div>
                                <div class="detail">
                                    <div>
                                        <i class="fas fa-users"></i>
                                        {{$club->members_count}}
                                    </div>
                                </div>
                            </div>
                        </li>
                    </a>
                @endforeach
            </ul>
            <div class="club_list_paginate">
                {{$clubs->links()}}
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
