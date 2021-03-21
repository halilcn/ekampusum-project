<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
@if(Session::has('postConfessionSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('postConfessionSuccess')}}');
    </script>
@endif
@auth()
    <div class="popup_container confession_new_popup">
        <div class="popup_filter"></div>
        <div class="confession_new">
            <div class="cancel"></div>
            <div class="popup_title">
                Yeni İtiraf
            </div>
            <ul>
                <li class="anonymous_profile">
                    <img src="">
                    <div class="username">
                    </div>
                    <div onclick="anonymousSettingsShow(true)" class="anonymous_settings_button">
                        <i class="fas fa-user-cog"></i>
                        ayarlar
                    </div>
                </li>
                <li class="anonymous_settings">
                    <form onsubmit="return false" id="confessionsUserSettingsForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="which" value="confessionUserSettings">
                        <div class="title">
                            Anonim Profili
                        </div>
                        <div class="content">
                            <div class="image_container">
                                <img src="../img/confessions/user_default.png">
                                <input name="image" type="file" id="anonymous_image">
                                <label for="anonymous_image">
                                    Değiştir
                                </label>
                            </div>
                            <input name="username" type="text" value="" placeholder="Takma Ad">
                            <div class="buttons">
                                <button onclick="anonymousSettingsShow(false)" class="back_button">
                                    geri
                                </button>
                                <button class="save" type="submit" onclick="confessionUserSettingsPost()">
                                    Kaydet
                                </button>
                            </div>
                        </div>
                    </form>
                </li>
                <li class="new_confession">
                    <form id="confessionForm">
                        @csrf
                        <input type="hidden" name="which" value="confessionPost">
                        <textarea placeholder="İtiraf" name="confession"></textarea>
                        <button class="button_not_click" type="button" onclick="newConfessionsPost()">
                            Paylaş
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
@endauth
<div class="page_container">
    @include('global/page_header')
    <div class="page_bottom_content">
        @include('global/page_left_menu')
        <div class="page_content">
            <div class="page_content_header">
                <div class="page_content_title">
                    İtiraflar
                </div>
                @auth()
                    @if(Auth::user()->register_email_confirmation == '1')
                        <div onclick="showConfessionPopup()" class="new_confession">
                            Yeni İtiraf
                        </div>
                    @endif
                @endauth
            </div>
            <ul class="confessions_ul">
                @forelse($confessions as $confession)
                    <li data-id="{{$confession->id}}">
                        <div class="confessions_ul_information">
                            @if($confession->users->image=='0')
                                <img src="/img/confessions/user_default.png">
                            @else
                                <img src="/img/confessions/{{$confession->users->image}}">
                            @endif
                            <div class="confessions_ul_information_username">
                                {{ $confession->users->username}}
                            </div>
                            <div class="confessions_ul_information_date">
                                {{ $confession->created_at}}
                            </div>
                        </div>
                        <div class="confessions_ul_content">
                            {{\Illuminate\Support\Str::limit($confession->confession_content,700,'')}}
                            @if(\Illuminate\Support\Str::length($confession->confession_content) > 700)
                                <div onclick="moreShowConfessionContent(this)" class="show_more">
                                    daha fazla göster
                                </div>
                            @endif
                            <span>
                          {{substr($confession->confession_content,700)}}
                            </span>
                        </div>
                        <div class="confessions_comments_count">
                            {{$confession->comments_count}} yorum
                        </div>
                        <div class="confessions_ul_comments">
                            @auth
                                @if($anonymousUser)
                                    <div class="confessions_ul_comments_add">
                                        @if($anonymousUser->image=='0')
                                            <img src="../img/confessions/user_default.png">
                                        @else
                                            <img src="../img/confessions/{{$anonymousUser->image}}">
                                        @endif
                                        <div class="confessions_comment_container">
                                <textarea onkeydown="textareaHeight($(this),200)"
                                          onkeyup="commentButtonDisableEnable(this)" placeholder="Bir şey yaz..."
                                          name="comment"></textarea>
                                            <button class="button"
                                                    onclick="confessionsCommentPost(this,'{{$confession->id}}')">
                                                Yorum Yap
                                            </button>
                                        </div>
                                        <div style="margin-top: 7px;" class="tooltip">
                                            <i style="margin-left: 3px;color: #929292;" class="fas fa-info-circle"></i>
                                            <div class="tooltip_text confession_comment_warning_mobile_tooltip_text">
                                                Anonim hesabınla yorum yapıyorsun.
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    @if(Auth::user()->register_email_confirmation)
                                        <div class="confessions_comment_anonymous_warning">
                                            <i class="fas fa-exclamation-circle"></i>
                                            Yorum yapabilmek için anonim kişi oluşturmalısınız.
                                            <span onclick="showConfessionPopup()">
                                            Şimdi Oluştur
                                        </span>
                                        </div>
                                    @endif
                                @endif
                            @endauth
                            @auth
                                <ul>
                                    @forelse($confession->comments as $comment)
                                        <li data-id="{{$comment->id}}">
                                            @if($comment->user->image=='0')
                                                <img src="../img/confessions/user_default.png">
                                            @else
                                                <img src="../img/confessions/{{$comment->user->image}}">
                                            @endif
                                            <div class="user_comment">
                                                <div class="information">
                                                    <div class="anonymous_username">
                                                        {{$comment->user->username}}
                                                    </div>
                                                    <div class="date">
                                                        {{$comment->created_at}}
                                                    </div>
                                                </div>
                                                <div class="comment_inner">
                                                    {{$comment->message}}
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                    @endforelse
                                    @if($confession->comments_count > 3)
                                        <li onclick="getMoreConfessionComment(this)" class="get_more">
                                            daha fazla göster
                                        </li>
                                    @endif
                                </ul>
                                <div class="no_other_comments">
                                    başka yorum yok
                                </div>
                            @endauth
                        </div>
                    </li>
                @empty
                    <li class="not_confessions">
                        <script
                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player
                            src="https://assets5.lottiefiles.com/packages/lf20_fyqtse3p.json"
                            background="transparent" speed="1" style="width: 30px; height: 30px;"
                            loop autoplay></lottie-player>
                        <span>
                                                Şuan hiç itiraf yok
                                            </span>
                    </li>
                @endforelse
            </ul>
            <div class="confession_paginate">
                {{$confessions->links()}}
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
