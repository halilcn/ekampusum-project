<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
@if(Session::has('clubAnnouncementSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('clubAnnouncementSuccess')}}');
    </script>
@endif
<div class="page_container">
    @if($clubAuth['clubAuthority']=='1')
        <div class="popup_container global_new_popup">
            <div class="popup_filter"></div>
            <div class="club_global_popup">
                <div class="popup_title">Yeni Duyuru-Haber</div>
                <div class="cancel"></div>
                <ul class="club_global_popup_ul">
                    <form id="clubNewAnnouncementsAndNewsForm" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="which" value="add">
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
        <div class="popup_container global_edit_popup">
            <div class="popup_filter"></div>
            <div class="club_global_popup">
                <div class="cancel"></div>
                <div class="popup_title">Düzenle</div>
                <ul class="club_global_popup_ul" style="height:400px;overflow-y: auto;">
                    <form id="clubNewAnnouncementsAndNewsEditForm" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="which" value="editPost">
                        <input type="hidden" name="id">
                        <li>
                            <div class="club_global_popup_title">Başlık</div>
                            <input name="title" type="text" value="">
                        </li>
                        <li class="clubEditTıtleImageLi">
                            <div class="club_global_popup_title">Kapak Fotoğrafı</div>
                            <input type="file" name="title_image" id="title_image_edit">
                            <input type="hidden" name="title_image_delete" value="">
                            <div class="club_global_popup_title_image">
                            </div>
                        </li>
                        <li>
                            <div class="club_global_popup_title">İçerik</div>
                            <textarea onkeydown="textareaHeight($(this),200)" name="subject"></textarea>
                            <div class="textarea_more_show" onclick="globalEventsTextarea(this)">daha fazla göster</div>
                        </li>
                        <li class="clubEditImagesLi">
                            <div class="club_global_popup_title">Fotoğraflar</div>
                            <input type="file" name="images[]" id="images_edit" multiple>
                            <input type="hidden" name="delete_images" value="">
                            <label for="images_edit">Fotoğraf Yükle</label>
                            <ul class="global_events_edit_images_ul">
                            </ul>
                        </li>
                    </form>
                    <li>
                        <button onclick="globalEventsEdit('duyuru-haber','0','popupEditPost')">
                            Kaydet
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    @endif
    @include('global/page_header')
    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <div class="club_ın_page_container">
                @include('club.global.clubInPageHeader')
                <div class="club_ın_page_bottom_contain">
                    @include('club.global.clubInPageLeftMenu')
                    <div class="club_ın_page_bottom_contain_content">
                        @if($clubAuth['clubAuthority']=='1' || $clubAuth['clubMiddleAuthority']=='1')
                            <div class="club_ın_page_bottom_contain_content_header">
                                <div class="club_new_announcements_news_button">
                                    Yeni Duyuru-Haber
                                </div>
                            </div>
                        @endif
                        <div class="club_ın_page_bottom_contain_content_inner">
                            <ul class="global_events_ul">
                                @forelse($announcementAndNewses as $announcementAndNews)
                                    <li data-id="{{$announcementAndNews->id}}">
                                        <div class="global_events_image">
                                            <a href="/duyuru-haber/{{$announcementAndNews->link}}">
                                                @if($announcementAndNews->title_image=='0')
                                                    <img
                                                        src="/img/announcements_and_news/announce_and_news_default.png">
                                                @else
                                                    <img
                                                        src="/img/announcements_and_news/{{$announcementAndNews->title_image}}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="global_events_contain">
                                            @if($clubAuth['clubAuthority']=='1' || $clubAuth['clubMiddleAuthority']=='1')
                                                <div class="global_events_settings">
                                                    <div class="global_events_settings_button">
                                                        <i class="fas fa-cog"></i> ayarlar
                                                    </div>
                                                    <ul>
                                                        <li onclick="globalEventsDelete('duyuru-haber','{{$announcementAndNews->id}}')">
                                                            <i style="padding-right: 3px;" class="fas fa-trash-alt"></i>sil
                                                        </li>
                                                        <li onclick="globalEventsEdit('duyuru-haber','{{$announcementAndNews->id}}','popupGet')">
                                                            <i style="padding-right: 3px;" class="fas fa-pen"></i>düzenle
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="mobile_global_information">
                                                <div>
                                                    <i class="far fa-clock"></i>
                                                    {{\Illuminate\Support\Carbon::parse($announcementAndNews->created_at)->isoFormat('Do MMMM YYYY')}}
                                                </div>
                                                <div>
                                                    <a href="/profil/{{$announcementAndNews->user->username}}">
                                                        <i class="fas fa-user"></i>
                                                        {{$announcementAndNews->user->username}}
                                                    </a>
                                                </div>
                                            </div>
                                            <a href="/duyuru-haber/{{$announcementAndNews->link}}">
                                                <div class="global_events_title">
                                                    {{$announcementAndNews->title}}
                                                </div>
                                            </a>
                                            <div class="global_events_information_2">
                                                <div class="global_events_user">
                                                    <a href="/profil/{{$announcementAndNews->user->username}}">
                                                        <i class="fas fa-user"></i>
                                                        {{$announcementAndNews->user->username}}
                                                    </a>
                                                </div>
                                            </div>
                                            <a href="/duyuru-haber/{{$announcementAndNews->link}}">
                                                <div class="global_events_text">
                                                    {{\Illuminate\Support\Str::limit($announcementAndNews->subject,220)}}
                                                    @if(Str::length($announcementAndNews->subject) > 220)
                                                        <a href="{{asset('duyuru-haber/'.$announcementAndNews->link)}}"
                                                           style="font-family: 'Varela Round', sans-serif;color: #1b90db;cursor: pointer;">
                                                            devamı için tıklayın
                                                        </a>
                                                    @endif
                                                </div>
                                            </a>
                                            <div class="global_events_information">
                                                <div class="global_events_date">
                                                    <i class="far fa-clock"></i>
                                                    {{\Illuminate\Support\Carbon::parse($announcementAndNews->created_at)->isoFormat('Do MMMM YYYY')}}
                                                </div>

                                                <div class="global_events_comments">
                                                    <i class="far fa-comments"></i>
                                                    {{$announcementAndNews->comments_count}}
                                                </div>

                                                <div class="global_events_view">
                                                    <i class="far fa-eye"></i>
                                                    {{$announcementAndNews->view_count}}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="no_sharing">
                                        <script
                                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                        <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_fyqtse3p.json"
                                                       background="transparent" speed="1"
                                                       style="width: 20px; height: 20px;" loop autoplay></lottie-player>
                                        <span>
                                            Hiçbir şey paylaşılmamış
                                        </span>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="club_global_events_paginate">
                            {{$announcementAndNewses->links()}}
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
