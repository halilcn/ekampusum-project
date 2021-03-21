<!-- Mobile Left Menu | Left Menu Selected -->
<div class="page_left_menu">
    <div class="page_left_menu_content">
        @auth()
            <div class="page_left_menu_profile">
                <a href="{{asset('profil/'.Auth::user()->username)}}">
                    @if(Auth::user()->image == '0')
                        <img src="../img/user/default.png">
                    @else
                        <img src="../img/user/{{Auth::user()->image}}">
                    @endif
                    <div class="username">
                        {{Auth::user()->username}}
                        @if(Auth::user()->school_email_confirmation == '1')
                            <div style="top: 2px;margin-right: 10px;" class="tooltip">
                                <i class="verification"></i>
                                <div class="tooltip_text verification_tooltip_text">
                                    Okul e-postası onaylanmış.
                                </div>
                            </div>
                        @endif
                    </div>
                </a>
            </div>
        @endauth
        <ul>
            <a id="tartismalar" href="/tartismalar">
                <li><i style="color: #0e7cc1;" class="fas fa-comments"></i><span>Tartışmalar</span></li>
            </a>
            <a id="duyuru-haber" href="/duyuru-haber">
                <li><i style="color: #4c4c4c;" class="fas fa-bullhorn"></i><span>Duyuru-Haber</span></li>
            </a>
            <a id="etkinlik" href="/etkinlik">
                <li><i style="color: #0ac62e;" class="fas fa-calendar-alt"></i><span>Etkinlik</span></li>
            </a>
            <a id="ders-notlari" href="/ders-notlari">
                <li><i style="color: #818181;" class="fas fa-book"></i><span>Ders Notları</span></li>
            </a>
            <a id="kulupler" href="/kulupler">
                <li><i style="color: #e7a427;" class="fas fa-building"></i><span>Okul Kulüpleri</span></li>
            </a>
            <a id="gruplar" href="/gruplar">
                <li><i style="color: #14cbba;" class="fas fa-users"></i><span>Gruplar</span></li>
            </a>
            <a id="itiraflar" href="/itiraflar">
                <li><i style="color: #de1717;" class="far fa-eye-slash"></i><span>İtiraflar</span></li>
            </a>
        </ul>
        <div class="page_left_menu_footer">
            <div class="page_left_menu_footer_links">
                <a href="{{asset('hakkimizda')}}">
                    Hakkımızda -
                </a>
                <a href="{{asset('yardim-destek')}}">
                    Yardım & Destek -
                </a>
                <a href="{{asset('hizmet-kosullari')}}">
                    Koşullar
                </a>
            </div>
            <div class="page_left_menu_footer_copyright">
                Copyright &#169 2021 ekampusum
            </div>
        </div>
    </div>
</div>

