<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
<div class="page_container">
    @auth()
        <div class="popup_container discussion_new_popup">
            <div class="popup_filter"></div>
            <div class="discussion_new">
                <div class="cancel"></div>
                <div class="popup_title">
                    Yeni Tartışma
                </div>
                <ul>
                    <li class="discussion_new_ul_header">
                        @if(Auth::user()->image==0)
                            <img src="/img/user/default.png">
                        @else
                            <img src="/img/user/{{Auth::user()->image}}">
                        @endif
                        <div class="username">
                            {{Auth::user()->username}}
                        </div>
                    </li>
                    <li>
                        <form id="discussionForm">
                            {{csrf_field()}}
                            <input type="hidden" name="which" value="newDiscussionSend">
                            <textarea name="title" placeholder="Başlık"></textarea>
                            <textarea name="subject" placeholder="Konu"></textarea>
                            <button class="button_not_click" type="button" onclick="discussionSend()">
                                Paylaş
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    @endauth
    @include('global/page_header')
    <div class="page_bottom_content">
        @include('global/page_left_menu')
        <div class="page_content">
            <ul class="discussion_ul">
                <div class="page_content_header">
                    <div class="page_content_title">Tartışmalar</div>
                    @auth()
                        @if(Auth::user()->register_email_confirmation == '1')
                            <div class="new_discussion_button">
                                Yeni Tartışma
                            </div>
                            <div class="new_discussion_button_mobile"><i class="fas fa-edit"></i></div>
                        @endif
                    @endauth
                </div>
                @foreach($discussions as $discussion)
                    <li onclick="discussionLink('{{$discussion->link}}')">
                        <div class="img_div">
                            @if($discussion->user->image=='0')
                                <a href="/profil/{{$discussion->user->username}}"><img
                                        src="../img/user/default.png"></a>
                            @else
                                <a href="/profil/{{$discussion->user->username}}"><img
                                        src="../img/user/{{$discussion->user->image}}"></a>
                            @endif

                            <div class="mobile_discussion_name">
                                <a href="/profil/{{$discussion->user->username}}">
                                    {{$discussion->user->username}}
                                </a>
                                @if($discussion->user->school_email_confirmation=='1')
                                    <div style="top: 2px;margin-right: 10px;" class="tooltip">
                                        <i class="verification"></i>
                                        <div class="tooltip_text verification_tooltip_text">
                                            Okul e-postası onaylanmış.
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div
                                class="mobile_discussion_time_posted">{{\Illuminate\Support\Carbon::parse($discussion->created_at)->diffForHumans(\Illuminate\Support\Carbon::now())}}</div>
                            <div class="mobile_discussion_comments_information"><i style="padding-right: 5px;"
                                                                                   class="fas fa-comments"></i>{{$discussion->comments->count()}}
                            </div>
                        </div>
                        <div class="content">
                            <div class="discussion_title">
                                {{\Illuminate\Support\Str::limit($discussion->title,90)}}
                            </div>
                            <div
                                class="discussion_content">{{\Illuminate\Support\Str::limit($discussion->subject,150)}}</div>
                            <div class="discussion_information">
                                <a href="/profil/{{$discussion->user->username}}">
                                    {{$discussion->user->username}}
                                </a>
                                @if($discussion->user->school_email_confirmation=='1')
                                    <div style="top: 2px;" class="tooltip">
                                        <i class="verification"></i>
                                        <div class="tooltip_text verification_tooltip_text">
                                            Okul e-postası onaylanmış.
                                        </div>
                                    </div>
                                @endif
                                <span>
                                {{\Illuminate\Support\Carbon::parse($discussion->created_at)->diffForHumans(\Illuminate\Support\Carbon::now())}}
                                </span>
                            </div>
                        </div>
                        <div class="content_2">
                            <div>
                                <i style="padding-right: 5px;"
                                   class="fas fa-comments"></i>{{$discussion->comments->count()}}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="discussion_paginate">
                {{ $discussions->links() }}
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
