<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>

<body>

<div class="page_container">
    @include('global/page_header')
    <div class="page_bottom_content">
        @include('global/page_left_menu')
        <div class="page_content">
            <div class="page_content_header">
                <div class="page_content_title">
                    Duyuru-Haber
                </div>
            </div>
            <ul class="global_events_ul">
                @foreach($announcementAndNewsGet as $announcementAndNews)
                    <li>
                        <div class="global_events_image">
                            <a href="/duyuru-haber/{{$announcementAndNews->link}}">
                                @if($announcementAndNews->title_image=='0')
                                    <img src="../img/announcements_and_news/announce_and_news_default.png">
                                @else
                                    <img src="../img/announcements_and_news/{{$announcementAndNews->title_image}}">
                                @endif
                            </a>
                        </div>
                        <div class="global_events_contain">
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
                                @if($announcementAndNews->club_id!='0')
                                    <div>
                                        <a href="/kulupler/{{$announcementAndNews->club->club_link}}">
                                            <i class="fas fa-users"></i>
                                            {{$announcementAndNews->club->club_name}}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <a href="/duyuru-haber/{{$announcementAndNews->link}}">
                                <div class="global_events_title">
                                    {{\Illuminate\Support\Str::limit($announcementAndNews->title,100)}}
                                </div>
                            </a>
                            <div class="global_events_information_2">
                                <div class="global_events_user">
                                    <a href="/profil/{{$announcementAndNews->user->username}}">
                                        <i class="fas fa-user"></i>
                                        {{$announcementAndNews->user->username}}
                                    </a>
                                </div>
                                @if($announcementAndNews->club_id!='0')
                                    <div class="global_events_group">
                                        <a href="/kulupler/{{$announcementAndNews->club->club_link}}">
                                            <i class="fas fa-users"></i>
                                            {{$announcementAndNews->club->club_name}}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <a href="/duyuru-haber/{{$announcementAndNews->link}}">
                                <div class="global_events_text">
                                    {{Str::limit($announcementAndNews->subject,220)}}
                                    @if(Str::length($announcementAndNews->subject) > 220)
                                        <a href="/duyuru-haber/{{$announcementAndNews->link}}"
                                           style="font-family: 'Varela Round', sans-serif;color: #1b90db;cursor: pointer;">devamı
                                            için
                                            tıklayın</a>
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
                @endforeach
                {{$announcementAndNewsGet->links()}}
            </ul>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>


</body>


</html>
