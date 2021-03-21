<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
<script>
    if (window.screen.width > 720) {
        function imagePage(src) {
            $(".global_events_image_page_contain img").attr('src', '../img/events/' + src);
            $(".global_events_image_page_div").fadeIn(300);
            $(".global_events_image_page_contain").css('transform', 'scale(1)');
        }

        $(document).ready(function () {
            $(".global_events_image_page_div .global_events_image_page_contain i,.global_events_image_page_div .global_events_image_page_filter").on('click', function () {
                $(".global_events_image_page_div").fadeOut(300);
                $(".global_events_image_page_contain").css('transform', 'scale(.9)');
            });
        })
    }

    @if(Auth::check())
    function globalEventsComment() {
        const image =
            @if(Auth::user()->image=='0')
                '<img src="../img/user/default.png">'
        @else
            '<img src="../img/user/{{Auth::user()->image}}">'
        @endif
        ;
        return '<li style="display:none;">' +
            '<div class="discussion_in_page_image">' +
            '<a href="/profil/{{Auth::user()->username}}">'
            + image +
            '</div>' +
            '<div class="discussion_in_page_content">' +
            '<div class="discussion_in_page_information">' +
            '<a href="/profil/{{Auth::user()->username}}">{{Auth::user()->username}}</a>' +
            @if(Auth::user()->school_email_confirmation)
            `<div style="top: 2px;margin-left: 3px;" class="tooltip">
                                <i class="verification"></i>
                                <div class="tooltip_text verification_tooltip_text">
                                    Okul e-postası onaylanmış.
                                </div>
                            </div>`
                @else
                ` `
            @endif
            + '<div class="discussion_in_page_information_time">1 saniye önce</div>' +
            '</div>' +
            '<div class="discussion_in_page_subject">' + returnText($(".comment textarea[name='message']").val()) + '</div>' +
            '</div>' +
            '</li>';
    }
    @endif

</script>

<div class="page_container">
    <div class="global_events_image_page_div">
        <div class="global_events_image_page_filter"></div>
        <div class="global_events_image_page_contain">
            <i class="cancel"></i>
            <img src="">
        </div>
    </div>
    @include('global/page_header')
    <div class="page_bottom_content">
        @include('global/page_left_menu')
        <div class="page_content">
            <div class="page_content_header">
                <div onclick="window.history.back();" class="page_content_title">
                    <div class="back_button">
                        <i class="fas fa-chevron-left"></i>
                        geri
                    </div>
                </div>
            </div>
            <div class="global_events_ın_page">
                <div class="events_in_page_top">
                    <div class="title">
                        {{$event->title}}
                    </div>
                    <div class="information">
                        @if(\Illuminate\Support\Carbon::now()->gte($event->date))
                            <div style="color: #808080;">
                                <i class="fas fa-history"></i>
                                Bu etkinlik sona erdi.
                            </div>
                        @else
                            <div style="color: #000000;" class="close_activity">
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
                            </div>
                            <div>
                                <i class="far fa-clock"></i>
                                {{$event->date->isoFormat('Do MMMM YYYY HH:mm')}}
                            </div>
                        @endif
                        <div>
                            <a href="{{asset('kulupler/'.$event->club->club_link)}}">
                                <i class="fas fa-users"></i>
                                {{$event->club->club_name}}
                            </a>
                        </div>
                        <div>
                            <i class="fas fa-map-marker"></i>
                            {{$event->location}}
                        </div>
                    </div>
                </div>
                <div class="global_events_ın_page_top">
                    @if($event->title_image=='0')
                        <img src="../img/events/events_default.png">
                    @else
                        <img src="../img/events/{{$event->title_image}}">
                    @endif
                </div>
                <div style="color: #454545;font-size: 19px;" class="global_events_ın_page_title">
                    Açıklama
                </div>
                <div class="global_events_ın_page_text">
                    {{$event->subject}}
                </div>

                @if($event->image!='0')
                    <div class="global_events_images">
                        @foreach(explode(',',$event->image) as $image)
                            <img onclick="imagePage('{{$image}}')"
                                 src="../img/events/{{$image}}">
                        @endforeach
                    </div>
                @endif
                <div class="global_events_comments_title">
                    Yorumlar
                </div>
                @if(Auth::check())
                    @if(Auth::user()->register_email_confirmation)
                        <div class="comment">
                            <form id="eventsNewComment">
                                {{csrf_field()}}
                                <input type="hidden" name="which" value="eventsNewComment">
                                <textarea
                                    onkeydown="textareaHeight($(this),150)" required name="message"
                                    placeholder="Bir şey yaz..."></textarea>
                            </form>
                            <button class="comment_button comment_button_not_click"
                                    onclick="eventsNewCommentAdd()">
                                Yorum Yap
                            </button>
                        </div>
                    @endif
                    <ul class="global_events_comments_ul">
                        @forelse($eventComments as $comment)
                            <li>
                                <div class="discussion_in_page_image">
                                    <a href="/profil/{{$comment->user->username}}">
                                        @if($comment->user->image!='0')
                                            <img src="/img/user/{{$comment->user->image}}">
                                        @else
                                            <img src="/img/user/default.png">
                                        @endif
                                    </a>
                                </div>
                                <div class="discussion_in_page_content">
                                    <div class="discussion_in_page_information">
                                        <a href="/profil/{{$comment->user->username}}">{{$comment->user->username}}</a>
                                        @if($comment->user->school_email_confirmation)
                                            <div style="top: 2px;margin-left: 3px;" class="tooltip">
                                                <i class="verification"></i>
                                                <div class="tooltip_text verification_tooltip_text">
                                                    Okul e-postası onaylanmış.
                                                </div>
                                            </div>
                                        @endif
                                        <div class="discussion_in_page_information_time">
                                            {{$comment->created_at}}
                                        </div>
                                    </div>
                                    <div class="discussion_in_page_subject">
                                        {{$comment->message}}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="global_events_not_comments">
                                Hiç yorum yapılmamış.
                            </li>
                        @endforelse
                    </ul>
                    {{$eventComments->links()}}
                @else
                    <div style="margin-top: 10px;" class="global_events_not_comments">
                        Bu alanı görmek için <a href="/giris-yap" style="color: #129AD4;">Giriş</a> yapın.
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>

</body>

</html>
