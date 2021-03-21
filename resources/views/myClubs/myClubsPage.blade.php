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
            <div class="my_clubs">
                <div class="title">
                    Kulüplerim
                </div>
                <ul class="my_clubs_list">
                    @forelse($clubMembers as $clubMember)
                        <li>
                            <div class="image">
                                <a href="{{asset('kulupler/'.$clubMember->clubs->club_link)}}">
                                    <img src="{{asset('img/club/'.$clubMember->clubs->settings->image)}}">
                                </a>
                            </div>
                            <div class="club_text">
                                <div class="name">
                                    <a href="{{asset('kulupler/'.$clubMember->clubs->club_link)}}">
                                        {{$clubMember->clubs->club_name}}
                                    </a>
                                </div>
                                <div class="authorization">
                                    <i class="fas fa-id-badge"></i>
                                    <span>
                                    {{$clubMember->role_name}}
                                </span>
                                </div>
                            </div>
                            @if($clubMember->role !== '3')
                                <div onclick="myClubExit('{{$clubMember->clubs->club_link}}')" class="club_exit">
                                    <i class="fas fa-door-open"></i>
                                    <span>
                                     Kulüpten Çık
                                   </span>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="not_my_clubs">
                            <script
                                src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                            <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_ITOCol.json"
                                           background="transparent" speed="1" style="width: 50px; height: 50px;" loop
                                           autoplay></lottie-player>
                            <span>
                                Hiç kulübe üye değilsin
                            </span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@include('global.footer')
@include('global.page_mobile_bottom_menu')
</body>
</html>
