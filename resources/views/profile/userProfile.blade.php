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
            <div class="profile_container">
                <div class="profile">
                    <div class="background_image">
                        <img class="background_image" src="{{asset('img/user/bg-default.jpg')}}">
                    </div>
                    <div class="information">
                        @if($user->image == '0')
                            <img src="/img/user/default.png">
                        @else
                            <img src="{{asset('img/user/'.$user->image)}}">
                        @endif
                        <div class="name">
                            {{$user->name_surname}}
                        </div>
                        <div class="username">
                            {{'@'.$user->username}}
                        </div>
                    </div>
                    @auth
                        @if($user->id==Auth::id())
                            <div class="edit_profile">
                                <a href="{{asset('/ayarlar/hesap')}}">
                                    <i class="fas fa-cog"></i>
                                    Profili Düzenle
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="profile_information">
                    <div class="links">
                        <a @if($pageType=='clubs') class="active_button" @endif
                        href="{{route('profile',['username'=>$user->username,'selection'=>'clubs'])}}">
                            <div>Kulupler</div>
                        </a>
                        <a @if($pageType=='lastDiscussion') class="active_button" @endif
                        href="{{route('profile',['username'=>$user->username,'selection'=>'lastDiscussion'])}}">
                            <div>Son Tartışmalar</div>
                        </a>
                        <a @if($pageType=='about') class="active_button"
                           @endif href="{{route('profile',['username'=>$user->username,'selection'=>'about'])}}">
                            <div>
                                Hakkında
                            </div>
                        </a>
                    </div>
                    <div class="profile_information_content">
                        @if($pageType=='about')
                            <div class="profile_about">
                                @if($user->about)
                                    {{$user->about}}
                                @else
                                    <div class="not_about">
                                        <i style="margin-right: 5px;" class="far fa-frown-open"></i>
                                        Hakkında hiçbir şey yok
                                    </div>
                                @endif
                            </div>
                        @elseif($pageType=='clubs')
                            <ul class="profile_clubs">
                                @forelse($user->clubsMember as $member)
                                    <li>
                                        <img src="{{asset('img/club/'.$member->clubs->settings->image)}}">
                                        <div class="club_name">
                                            <a href="{{asset('kulupler/'.$member->clubs->club_link)}}">
                                                {{$member->clubs->club_name}}
                                            </a>
                                        </div>
                                        <div class="authorization">
                                            <i class="fas fa-id-badge"></i>
                                            {{$member->role_name}}
                                        </div>
                                    </li>
                                @empty
                                    <li class="not_clubs_member">
                                        <i style="margin-right: 5px;" class="far fa-frown-open"></i>
                                        Hiçbir kulübe üye değil
                                    </li>
                                @endforelse
                            </ul>
                        @elseif($pageType=='lastDiscussion')
                            <ul class="profile_last_discussion">
                                @forelse($user->lastDiscussions as $discussion)
                                    <li>
                                        <a href="{{asset('tartismalar/'.$discussion->link)}}">
                                            <div class="title">
                                                {{$discussion->title}}
                                            </div>
                                            <div class="discussion_info">
                                                <div class="date">
                                                    <i class="fas fa-clock"></i>
                                                    {{\Illuminate\Support\Carbon::parse($discussion->created_at)->diffForHumans(\Illuminate\Support\Carbon::now())}}
                                                </div>
                                                <div class="comments_count">
                                                    <i class="fas fa-comments"></i>
                                                    {{$discussion->comments_count}}
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="not_last_discussion">
                                        <i style="margin-right: 5px;" class="far fa-frown-open"></i>
                                        Hiç tartışması yok
                                    </li>
                                @endforelse
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <!-- silinecek css leri unutma! -->
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
    @include('global.footer')
</div>
</body>
</html>
