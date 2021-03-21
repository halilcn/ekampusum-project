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
            <div class="club_ın_page_container">
                @include('club.global.clubInPageHeader')
                <div class="club_ın_page_bottom_contain">
                    @include('club.global.clubInPageLeftMenu')
                    <div class="club_ın_page_bottom_contain_content">
                        <div class="club_ın_page_bottom_contain_content_inner">
                            <div class="club_ın_page_intro">
                                @if(\Illuminate\Support\Str::length($club->settings->introduction_text) > 1)
                                    {{$club->settings->introduction_text}}
                                @else
                                    <div class="not_intro">
                                        <script
                                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                        <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_fyqtse3p.json"
                                                       background="transparent" speed="1"
                                                       style="width: 20px; height: 20px;" loop autoplay></lottie-player>
                                        <span>
                                           Hiçbir şey eklenmemiş
                                       </span>
                                    </div>
                                @endif
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
