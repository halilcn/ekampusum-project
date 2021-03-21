<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
<script>
    if (window.screen.width > 720) {
        function imagePage(src) {
            $(".global_events_image_page_contain img").attr('src', '../img/announcements_and_news/' + src);
            $(".global_events_image_page_div").fadeIn(300);
        }

        $(document).ready(function () {
            $(".global_events_image_page_div .global_events_image_page_contain i,.global_events_image_page_div .global_events_image_page_filter").on('click', function () {
                $(".global_events_image_page_div").fadeOut(300);
            });
        })
    }

    @auth()
    function globalEventsComment() {
        //Image
        const image =
            @if(Auth::user()->image=='0')
                '<img src="../img/user/default.png">'
        @else
            '<img src="../img/user/{{Auth::user()->image}}">'
        @endif
        ;

        //School Email Confirmation
        const confirmation =
            @if(Auth::user()->school_email_confirmation=='1')
            `<div style="top: 2px;margin-left: 3px;" class="tooltip">
            <i class="verification"></i>
            <div class="tooltip_text verification_tooltip_text">
            Okul e-postası onaylanmış.
            </div>
            </div>`;
        @else
        ``
        @endif

            return '<li>' +
            '<div class="discussion_in_page_image">' +
            '<a href="/profil/{{Auth::user()->username}}">'
            + image +
            '</a>' +
            '</div>' +
            '<div class="discussion_in_page_content">' +
            '<div class="discussion_in_page_information">' +
            '<a href="/profil/{{Auth::user()->username}}">{{Auth::user()->username}}</a>' +
            confirmation +
            '<div class="discussion_in_page_information_time">1 saniye önce</div>' +
            '</div>' +
            '<div class="discussion_in_page_subject">' + returnText($(".comment textarea[name='message']").val()) + '</div>' +
            '</div>' +
            '</li>';
    }

    @endauth
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
                <div onclick="window.history.back();"
                     class="page_content_title global_events_in_page_back_button">
                    <i class="fas fa-chevron-left"></i>
                    geri
                </div>
            </div>
            <div class="global_events_ın_page">
                <div style="margin-top: 0px;" class="global_events_ın_page_top">
                    @if($announcementAndNews->title_image=='0')
                        <img src="../img/announcements_and_news/announce_and_news_default.png">
                    @else
                        <img src="../img/announcements_and_news/{{$announcementAndNews->title_image}}">
                    @endif
                    <div class="global_events_ın_page_information">
                        <div class="global_events_ın_page_user">
                            <a href="/profil/{{$announcementAndNews->user->username}}">
                                <i class="fas fa-user"></i>
                                {{$announcementAndNews->user->username}}
                            </a>
                        </div>
                        @if($announcementAndNews->club_id!='0')
                            <div class="global_events_ın_page_group">
                                <a href="/kulupler/{{$announcementAndNews->club->club_link}}">
                                    <i class="fas fa-users"></i>
                                    {{$announcementAndNews->club->club_name}}
                                </a>
                            </div>
                        @endif

                        <div>
                            &#124;
                        </div>
                        <div class="global_events_ın_page_date">
                            <i class="far fa-clock"></i>
                            {{\Illuminate\Support\Carbon::parse($announcementAndNews->created_at)->isoFormat('Do MMMM YYYY')}}
                        </div>
                        <div class="global_events_ın_page_comments">
                            <i class="far fa-comments"></i>
                            {{$announcementAndNews->comments->count()}}
                        </div>
                        <div class="global_events_ın_page_view">
                            <i class="far fa-eye"></i>
                            {{$announcementAndNews->view_count}}
                        </div>
                    </div>
                </div>
                <div class="global_events_ın_page_title">
                    {{$announcementAndNews->title}}
                </div>
                <div class="global_events_ın_page_text">
                    {{$announcementAndNews->subject}}
                </div>

                @if($announcementAndNews->image!='0')
                    <div class="global_events_images">
                        @foreach(explode(',',$announcementAndNews->image) as $image)
                            <img onclick="imagePage('{{$image}}')"
                                 src="../img/announcements_and_news/{{$image}}">
                        @endforeach
                    </div>
                @endif
                <div class="global_events_comments_title">
                    Yorumlar
                </div>

                @if(!Auth::guest())
                    @if(Auth::user()->register_email_confirmation)
                        <div class="comment">
                            <form id="announcementsNewComment">
                                {{csrf_field()}}
                                <input type="hidden" name="which" value="announcementsNewComment">
                                <textarea
                                    onkeydown="textareaHeight($(this),250)" required name="message"
                                    placeholder="Bir şey yaz..."></textarea>
                            </form>
                            <button class="comment_button comment_button_not_click"
                                    onclick="announcementsNewCommentAdd()">
                                Yorum Yap
                            </button>
                        </div>
                    @endif
                    <ul class="global_events_comments_ul">
                        @forelse($announcementAndNewsComments as $comment)
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
                                        @if($comment->user->school_email_confirmation == 1)
                                            <div style="top: 2px;left: 3px;" class="tooltip">
                                                <i class="verification"></i>
                                                <div class="tooltip_text verification_tooltip_text">
                                                    Okul e-postası onaylanmış.
                                                </div>
                                            </div>
                                        @endif
                                        <div
                                            class="discussion_in_page_information_time">{{$comment->created_at}}</div>
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
                    {{ $announcementAndNewsComments->links() }}
                @else
                    <div style="margin-top: 10px;" class="global_events_not_comments">
                        Bu alanı görmek <a href="/giris-yap" style="color: #129AD4;">Giriş</a> yapın.
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
