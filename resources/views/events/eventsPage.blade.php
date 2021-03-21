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
                    Etkinlik
                </div>
            </div>
            <div class="event_container">
                <ul class="event_ul">
                    @foreach($events as $event)
                        <li>
                            <ul class="event_information_ul">
                                <li class="title">
                                    <a href="/etkinlik/{{$event->link}}">
                                        {{$event->title}}
                                    </a>
                                </li>
                                <li class="information">
                                    <div>
                                        <i class="far fa-clock"></i>
                                        {{$event->date->isoFormat('Do MMMM YYYY')}}
                                    </div>
                                    <div>
                                        <a href="/kulupler/{{$event->club->club_link}}">
                                            <i class="fas fa-users"></i>
                                            {{$event->club->club_name}}
                                        </a>
                                        /
                                        <a href="/profil/{{$event->user->username}}">
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
                                    <li style="margin-top: 10px;" class="close_activity">
                                        <script
                                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                        <script
                                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                        <lottie-player
                                            src="https://assets5.lottiefiles.com/datafiles/UbZGZjPb1PfKE36/data.json"
                                            background="transparent" speed="0.8" style="width: 20px; height: 20px;"
                                            loop autoplay></lottie-player>
                                        <div>
                                            {{$event->date->diffForHumans(now())}}
                                        </div>
                                    </li>
                                @endif
                            </ul>
                            <ul class="event_detail_ul">
                                <li class="image">
                                    <a href="/etkinlik/{{$event->link}}">
                                        @if($event->title_image=='0')
                                            <img src="../img/events/events_default.png">
                                        @else
                                            <img src="../img/events/{{$event->title_image}}">
                                        @endif
                                    </a>
                                </li>
                                <li class="action_information">
                                    <div>
                                        <i class="fas fa-comments"></i>
                                        {{$event->comments_count}}
                                    </div>
                                    <div>
                                        <i class="fas fa-eye"></i>
                                        {{$event->view_count}}
                                    </div>
                                </li>
                            </ul>
                        </li>
                    @endforeach
                    {{$events->links()}}
                </ul>
                <ul class="event_club_ul">
                    <li class="title">
                        Yeni Kulüpler
                    </li>
                    <ul>
                        @foreach($clubs as $club)
                            <li>
                                <a href="/kulupler/{{$club->club_link}}">
                                    <img src="http://127.0.0.1:8000/img/club/{{$club->settings->image}}">
                                    <div class="information">
                                        <div class="name">
                                            {{$club->club_name}}
                                        </div>
                                        <div class="user_count">
                                            <i class="fas fa-users"></i>
                                            {{$club->members_count}}
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </ul>
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>

</body>

</html>
