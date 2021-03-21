<div class="club_ın_page_background">
    @if($club->settings->background_image!='0')
        <img src="/img/club/{{$club->settings->background_image}}">
    @else
        <img src="/img/club/club_background_default.png">
    @endif
</div>
<div class="club_ın_page_background_bottom">
    <div class="club_ın_page_logo">
        <img src="/img/club/{{$club->settings->image}}">
    </div>
</div>
<div class="mobile_div">
</div>
<div class="club_in_page_links">
    <a class="{{!Request::segment(3) ? 'active_button' : ''}}" href="/kulupler/{{Request::segment(2)}}/">
        Hakkında
    </a>
    <a class="{{Request::segment(3) == 'duyuru-haber' ? 'active_button' : ''}}"
       href="/kulupler/{{Request::segment(2)}}/duyuru-haber">
        Duyuru/Haber
    </a>
    <a class="{{Request::segment(3) == 'etkinlik' ? 'active_button' : ''}}"
       href="/kulupler/{{Request::segment(2)}}/etkinlik">
        Etkinlik
    </a>
    <a class="{{Request::segment(3) == 'uyeler' ? 'active_button' : ''}}"
       href="/kulupler/{{Request::segment(2)}}/uyeler">
        Üyeler
    </a>
</div>
