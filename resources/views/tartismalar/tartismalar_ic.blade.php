<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
<script>
    //ekampusum.js İçine Atılmayacak
    function discussionMessageLi() {
        //Image
        @if(Auth::check())
        const image =
            @if(Auth::user()->image=='0')
                '<img src="../img/user/default.png">';
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
            '';
        @endif
            return '<li class="comment_li new_comment_li">' +
            '<div class="discussion_in_page_image">' +
            '<a href="/profil/{{Auth::user()->username}}">'
            + image +
            '</div>' +
            '<div class="discussion_in_page_content">' +
            '<div class="discussion_in_page_information">' +
            '<a href="/profil/{{Auth::user()->username}}">{{Auth::user()->username}}</a>' +
            confirmation +
            '<div class="discussion_in_page_information_time">1 saniye önce</div>' +
            `<div class="comment_vote"><span>0</span><i style="opacity: 0.4;pointer-events: none;" class="fas fa-angle-up"></i></div>` +
            '</div>' +
            '<div class="discussion_in_page_subject">' + returnText($(".comment textarea[name='comment']").val()) + '</div>' +
            '</div>' +
            '</li>';
        @endif
    }
</script>
<script>

</script>
<div class="page_container">
    @include('global/page_header')
    <div class="page_bottom_content">
        @include('global/page_left_menu')
        <div class="page_content">
            @if(!Auth::guest())
                <div class="page_content_header">
                    <div onclick="window.location='/tartismalar'" class="page_content_title discussion_back_button">
                        <i class="fas fa-chevron-left"></i>
                        geri
                    </div>
                </div>
                <ul class="discussion_in_page_ul" id="discussion_in_page_ul">
                    <li id="firstLi">
                        <div class="discussion_in_page_image">
                            <a href="/profil/{{$discussion->user->username}}">
                                @if($discussion->user->image=='0')
                                    <img src="../img/user/default.png">
                                @else
                                    <img src="../img/user/{{$discussion->user->image}}">
                                @endif
                            </a>
                        </div>
                        <div class="discussion_in_page_content">
                            <div class="discussion_in_page_information">
                                <a href="/profil/{{$discussion->user->username}}">{{$discussion->user->username}}</a>
                                @if($discussion->user->school_email_confirmation=='1')
                                    <div style="top: 2px;margin-left: 3px;" class="tooltip">
                                        <i class="verification"></i>
                                        <div class="tooltip_text verification_tooltip_text">
                                            Okul e-postası onaylanmış.
                                        </div>
                                    </div>
                                @endif
                                <div class="discussion_in_page_information_time">
                                    {{\Illuminate\Support\Carbon::parse($discussion->created_at)->diffForHumans(\Illuminate\Support\Carbon::now())}}
                                </div>
                                <div class="discussion_in_page_information_comments">
                                    <i style="padding-right: 5px;"
                                       class="fas fa-comments"></i>{{$discussion->comments->count()}}
                                </div>
                                @if($discussion->user_id==Auth::user()->id)
                                    <div class="discussion_in_page_settings">
                                        <i class="fas fa-cog"></i>
                                        <ul class="discussion_in_page_settings_contain">
                                            <li onclick="discussionEdit()"><i class="fas fa-pen"></i>düzenle</li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <div id="title" class="discussion_in_page_title">{{$discussion->title}}</div>
                            <div id="subject" class="discussion_in_page_subject">{{$discussion->subject}}</div>
                            @if($discussion->created_at!=$discussion->updated_at)
                                <div class="discussion_edited">
                                    <i class="fas fa-history"></i>
                                    {{\Illuminate\Support\Carbon::parse($discussion->updated_at)->diffForHumans(\Illuminate\Support\Carbon::now())}}
                                    düzenlendi
                                </div>
                            @endif
                        </div>
                    </li>

                    <li id="editLi">
                        <div class="discussion_page_in_edit">
                            <div class="discussion_page_in_edit_title">Düzenle</div>
                            <form id="discussionEditForm">
                                {{csrf_field()}}
                                <input type="hidden" name="which" value="edit">
                                <input name="title" type="text" value="">
                                <textarea onkeydown="textareaHeight($(this),250)" name="subject"></textarea>
                            </form>
                            <button onclick="editSave()" class="discussion_page_in_edit_save_button">Kaydet</button>
                        </div>
                    </li>

                    <li class="discussion_comments_header">
                        <div class="title">
                            Yorumlar
                        </div>

                        @auth()
                            <div class="discussion_comments_list_type">
                                <div class="available_list_type">
                                    Sıralama
                                    <i class="fas fa-caret-down"></i>
                                </div>
                                <ul>
                                    <li>
                                        <a @if(request()->listType == 'created_at' || !isset(request()->listType)) class="active_button"
                                           @else href="{{route('discussion.page',['listType'=>'created_at','discussionLink'=>request()->segment(2)])}} @endif">
                                            Tarihe Göre
                                        </a>
                                    </li>
                                    <li>
                                        <a @if(request()->listType == 'vote') class="active_button"
                                           @else href="{{route('discussion.page',['listType'=>'vote','discussionLink'=>request()->segment(2)])}}" @endif>
                                            Verilen Oya Göre
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </li>
                    @if(Auth::user()->register_email_confirmation==='1')
                        <li class="comment">
                            <form id="newComments">
                                {{csrf_field()}}
                                <input type="hidden" name="which" value="discussionNewComments">
                                <textarea
                                    onkeydown="textareaHeight($(this),200)" name="comment"
                                    placeholder="Bir şey yaz..."></textarea>
                            </form>
                            <button class="comment_button comment_button_not_click"
                                    onclick="discussionMessageAdd()">
                                Yorum Yap
                            </button>
                        </li>
                    @endif

                    @forelse($discussionComments as $comment)
                        <li class="comment_li">
                            <div class="discussion_in_page_image">
                                <a href="/profil/{{$comment->user->username}}">
                                    @if($comment->user->image=='0')
                                        <img src="../img/user/default.png">
                                    @else
                                        <img src="../img/user/{{$comment->user->image}}">
                                    @endif
                                </a>
                            </div>
                            <div class="discussion_in_page_content">
                                <div class="discussion_in_page_information">
                                    <a href="/profil/{{$comment->user->username}}">
                                        {{$comment->user->username}}
                                    </a>
                                    @if($comment->user->school_email_confirmation=='1')
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
                                    <div class="comment_vote">
                                        <span>{{$comment->vote}}</span>
                                        <i @if(Auth::id() != $comment->user_id) onclick="increaseDiscussionComment(this,{{$comment->id}})"
                                           @else style="opacity: 0.4;pointer-events: none;"
                                           @endif
                                           class="fas fa-angle-up"></i>
                                    </div>
                                </div>
                                <div class="discussion_in_page_subject">
                                    {{$comment->message}}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="discussion_comments_not">
                            Hiç yorum yapılmamış.
                        </li>
                    @endforelse
                </ul>
                <div class="discussion_paginate">
                    {{$discussionComments->appends(request()->input())->links()}}
                </div>
            @else
                <div class="guest_warning">
                    <div class="animate">
                        <script
                            src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player src="https://assets6.lottiefiles.com/packages/lf20_9LR70U.json"
                                       background="transparent" speed="1" style="width: 200px; height: 200px;" loop
                                       autoplay></lottie-player>
                    </div>
                    <span>
                          Bu alanı görmek için
                          <a href="/giris-yap">
                              Giriş
                          </a>
                          yapmanız gerekir.
                      </span>
                </div>
            @endif
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
