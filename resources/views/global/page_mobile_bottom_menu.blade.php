@auth
    <div class="page_header_notifications_bottom notifications_mobile_bottom">
        <div class="page_header_notifications_fixed">
            <div class="page_header_notifications_fixed_title">
                Bildirimler
            </div>
            <div class="page_header_notifications_fixed_events">
                <i onclick="notificationsGet('update')" style="color: #474747;"
                   class="fas fa-sync-alt"></i>
                <i onclick="notificationsAllDelete()" style="color: #de3838;"
                   class="fas fa-trash-alt"></i>
            </div>
        </div>
        <ul class="page_header_notifications_ul">

        </ul>
    </div>
    <ul class="mobile_bottom_menu">
        <li class="{{empty(Request::segment(1))  ? 'active_button' : ''}}">
            <a href="{{asset('/')}}">
                <i class="fas fa-home"></i>
            </a>
        </li>
        <li class="{{Request::segment(1) ==='kuluplerim' ? 'active_button' : ''}}">
            <a href="{{asset('/kuluplerim')}}">
                <i class="fas fa-building"></i>
            </a>
        </li>
        <li onclick="mobileShowNotifications(this)">
            <div style="right: 23px;top: 8px;" class="page_header_notifications_count">
                @if($count= \App\Models\NotificationUser::where(['user_id'=>Auth::user()->id,'notification_view'=>'0'])->count())
                    {{$count == '0' ? '' :$count }}
                @endif
            </div>
            <i class="fas fa-bell"></i>
        </li>
        <li class="{{Request::segment(1) ==='ayarlar' ? 'active_button' : ''}}">
            <a href="{{asset('/ayarlar/hesap')}}">
                <i class="fas fa-cog"></i>
            </a>
        </li>
        <li class="{{Request::segment(2) === Auth::user()->username ? 'active_button' : ''}}">
            <a href="{{asset('/profil/'.auth()->user()->username)}}">
                @php($image=Auth::user()->image == '0'? 'default.png':Auth::user()->image)
                <img src="{{asset('img/user/'.$image)}}">
            </a>
        </li>
    </ul>
@endauth
@guest
    <div class="mobile_bottom_menu mobile_bottom_action_menu">
        <a href="{{asset('kayit-ol')}}">
            <div class="register_button">
                Kayıt Ol
            </div>
        </a>
        <a href="{{asset('giris-yap')}}">
            <div class="login_button">
                Giriş Yap
            </div>
        </a>
    </div>
@endguest
<div class="mobile_bottom_menu_bg"></div>
