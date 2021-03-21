<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>

@if(Session::has('postEventSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('postEventSuccess')}}');
    </script>
@endif

@if(Session::has('editEventSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('editEventSuccess')}}');
    </script>
@endif

<div class="page_container">
    @if($clubAuth['clubAuthority']=='1' || $clubAuth['clubMiddleAuthority'] == '1')
        <div class="popup_container global_new_popup">
            <div class="popup_filter"></div>
            <div class="club_global_popup">
                <div class="cancel"></div>
                <div class="popup_title">Yeni Etkinlik</div>
                <ul class="club_global_popup_ul">
                    <form id="clubEventsForm" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="which" value="add">
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
                        <button class="button_not_click" onclick="globalEventsAdd('etkinlik','#clubEventsForm')">
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
                <ul class="club_global_popup_ul">
                    <form id="clubEventsEditForm" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="which" value="editPost">
                        <input type="hidden" name="id">
                        <li>
                            <div class="club_global_popup_title">Tarih</div>
                            <input name="date" type="datetime-local">
                        </li>
                        <li>
                            <div class="club_global_popup_title">Konum</div>
                            <input name="location" type="text">
                            <div class="club_global_popup_location">
                                <input class="inp-cbx" id="location_online_edit" type="checkbox" name="location_online"
                                       style="display: none">
                                <label class="cbx" for="location_online_edit">
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
                        <button onclick="globalEventsEdit('etkinlik','0','popupEditPost')">
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
                                    Yeni Etkinlik
                                </div>
                            </div>
                        @endif
                        <div class="club_ın_page_bottom_contain_content_inner">
                            <ul class="global_events_ul event_ul">
                                @forelse($events as $event)
                                    <li data-id="{{$event->id}}">
                                        <ul class="event_information_ul">
                                            <li class="title">
                                                <a href="/etkinlik/{{$event->link}}">
                                                    {{$event->title}}
                                                </a>
                                            </li>
                                            <li class="information">
                                                <div>
                                                    <i class="far fa-clock"></i>
                                                    {{\Illuminate\Support\Carbon::parse($event->date)->isoFormat('Do MMMM YYYY')}}
                                                </div>
                                                <div>
                                                    <a href="/profil/{{$event->user->username}}">
                                                        <i class="fas fa-user"></i>
                                                        {{$event->user->username}}
                                                    </a>
                                                </div>
                                                <div>
                                                    <i class="fas fa-map-marker"></i>
                                                    {{$event->location}}
                                                </div>
                                            </li>
                                            @if(\Illuminate\Support\Carbon::now()->gte($event->date))
                                                <li style="color: #acacac;" class="remaining_time">
                                                    <i class="fas fa-history"></i>
                                                    Bu etkinlik sona erdi.
                                                </li>
                                            @else
                                                @if($clubAuth['clubAuthority']=='1' || $clubAuth['clubMiddleAuthority']=='1')
                                                    <li class="global_events_settings event_settings_mobile">
                                                        <div class="global_events_settings_button">
                                                            <i class="fas fa-cog"></i> ayarlar
                                                        </div>
                                                        <ul>
                                                            <li onclick="globalEventsDelete('etkinlik','{{$event->id}}')">
                                                                <i style="padding-right: 3px;"
                                                                   class="fas fa-trash-alt"></i>sil
                                                            </li>
                                                            <li onclick="globalEventsEdit('etkinlik','{{$event->id}}','popupGet')">
                                                                <i style="padding-right: 3px;" class="fas fa-pen"></i>düzenle
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="events_settings">
                                                        <div class="delete"
                                                             onclick="globalEventsDelete('etkinlik','{{$event->id}}')">
                                                            <i style="padding-right: 3px;"
                                                               class="fas fa-trash-alt"></i>sil
                                                        </div>
                                                        <div class="edit"
                                                             onclick="globalEventsEdit('etkinlik','{{$event->id}}','popupGet')">
                                                            <i style="padding-right: 3px;" class="fas fa-pen"></i>düzenle
                                                        </div>
                                                    </li>
                                                @endif
                                            @endif
                                        </ul>
                                        <ul class="event_detail_ul">
                                            <li class="image">
                                                <a href="/etkinlik/{{$event->link}}">
                                                    @if($event->title_image=='0')
                                                        <img src="/img/events/events_default.png">
                                                    @else
                                                        <img src="/img/events/{{$event->title_image}}">
                                                    @endif
                                                </a>
                                            </li>
                                            <li class="action_information">
                                                <div style="font-size: 13px;">
                                                    <i class="fas fa-comments"></i>
                                                    {{$event->comments_count}}
                                                </div>
                                                <div style="font-size: 13px;">
                                                    <i class="fas fa-eye"></i>
                                                    {{$event->view_count}}
                                                </div>
                                            </li>
                                        </ul>
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
                            <div class="club_global_events_paginate">
                                {{$events->links()}}
                            </div>
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
