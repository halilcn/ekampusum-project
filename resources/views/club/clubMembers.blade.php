<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>
<body>
@if(Session::has('transferPresidentSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('transferPresidentSuccess')}}');
    </script>
@endif

@if(Session::has('deleteMemberSuccess'))
    <script>
        Notiflix.Notify.Success('{{Session::get('deleteMemberSuccess')}}');
    </script>
@endif

<div class="page_container">
    @if($clubAuth['clubAuthority']==='1')
        <div class="popup_container club_members_settings_popup">
            <div class="popup_filter"></div>
            <div class="club_global_popup  club_members_settings">
                <div class="cancel"></div>
                <div class="popup_title">Üye Ayarları</div>
                <ul class="club_members_settings_ul">
                    <form id="clubMemberForm">
                        {{csrf_field()}}
                        <input type="hidden" name="which">
                        <li>
                            <div class="club_members_settings_ul_title">
                                Kullanıcı Adı
                            </div>
                            <input name="username" type="text" value="">
                        </li>

                        <li>
                            <div class="club_members_settings_ul_title">
                                Rolü
                            </div>
                            <select name="role" id="clubRoleSelect">
                                <option value="2">Başkan Yardımcısı</option>
                                <option value="1">Görevli</option>
                                <option value="0">Normal Üye</option>
                            </select>
                        </li>

                        <li class="club_members_settings_task_name">
                            <div class="club_members_settings_ul_title">
                                Görevi
                            </div>
                            <div style="margin-right: auto;margin-left: 5px;" class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <div style="font-family: 'Montserrat', sans-serif;font-size: 9px;" class="tooltip_text">
                                    Lütfen kısa ve anlaşılır görev ismi veriniz.
                                </div>
                            </div>
                            <input name="role_name" type="text">
                        </li>

                        <li>
                            <div class="club_members_settings_ul_title">
                                Yetkisi
                                <div style="margin-right: auto;margin-left: 5px;" class="tooltip">
                                    <i class="fas fa-info-circle"></i>
                                    <div class="tooltip_text tooltip_text_2">
                                        <span>Üst Düzey Yetki:</span>
                                        Başkan ile aynı yetkilere sahip olur.
                                        <br>
                                        <span>Orta Düzey Yetki:</span>
                                        Sadece Duyuru/Haber ve Etkinlik paylaşabilir.
                                        <br>
                                        <span>Yetki Yok:</span>
                                        Standart üyedir.Üye olduğunda varsayılan olarak kullanıcıya atanır.
                                    </div>
                                </div>
                            </div>
                            <select name="authority" id="clubAuthoritySelect">
                                <option value="0">
                                    Yetki Yok
                                </option>
                                <option value="1">
                                    Orta Düzey Yetki
                                </option>
                                <option value="2">
                                    Üst Düzey Yetki
                                </option>
                            </select>
                        </li>
                    </form>
                    <li>
                        <button onclick="clubMembersSettingsButtons('delete')">
                            <i class="fas fa-user-minus"></i>
                            Üyelikten Çıkar
                        </button>
                        <button onclick="clubMembersSettingsButtons('save')">
                            <i class="fas fa-user-lock"></i>
                            Kaydet
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="popup_container new_member_popup">
            <div class="popup_filter"></div>
            <div class="club_global_popup  new_member_popup_div">
                <div class="cancel"></div>
                <div class="new_member_buttons">
                    <div onclick="openNewMemberButtons('invitation')" class="new_member_buttons_activated">
                        <i class="fas fa-bell"></i>
                        Üye İstekleri
                    </div>
                    <div onclick="openNewMemberButtons('add')">
                        <i class="fas fa-user-plus"></i>
                        Üye Ekle
                    </div>
                </div>
                <ul class="new_member_invitation_ul">
                    @forelse($clubInvitations as $clubInvitation)
                        <li data-username="{{$clubInvitation->user->username}}">
                            <a href="/profil/{{$clubInvitation->user->username}}">
                                @if($clubInvitation->user->image=='0')
                                    <img src="/img/user/default.png">
                                @else
                                    <img src="/img/user/{{$clubInvitation->user->image}}">
                                @endif
                                <div class="new_member_invitation_username">{{$clubInvitation->user->username}}</div>
                            </a>
                            <div class="new_member_invitation_date">
                                <i style="margin-right: 5px;" class="far fa-clock"></i>
                                {{\Illuminate\Support\Carbon::parse($clubInvitation->created_at)->diffForHumans(\Illuminate\Support\Carbon::now())}}
                            </div>
                            <div class="new_member_invitation_buttons">
                                <button onclick="clubInvitation('{{$clubInvitation->user->username}}','1')">
                                    <i class="far fa-check-circle"></i>
                                    Kabul Et
                                </button>
                                <button onclick="clubInvitation('{{$clubInvitation->user->username}}','0')">
                                    <i class="far fa-times-circle"></i>
                                    Reddet
                                </button>
                            </div>
                        </li>
                    @empty
                        <li class="new_member_invitation_warning">
                            <i class="far fa-frown-open"></i>
                            Hiç istek yok.
                        </li>
                    @endforelse
                </ul>
                <ul class="new_member_add_ul">
                    <div class="new_member_add_ul_top">
                        <input placeholder="Kullanıcı Adı" name="username" type="text">
                        <button onclick="searchUser()">
                            <i class="fas fa-search"></i>
                            Ara
                        </button>
                    </div>
                    <li class="new_member_add_ul_li_start_warning">
                        Lütfen aramak istediğiniz kullanıcı adını yazınız.
                    </li>
                </ul>
            </div>
        </div>
        <div class="popup_container transfer_president_popup">
            <div class="popup_filter"></div>
            <div class="club_global_popup  transfer_president_popup_div">
                <div class="cancel"></div>
                <div class="popup_title">Başkanlık Aktarma</div>
                <ul class="club_members_transfer_president">
                    <li class="check_username">
                        <form onsubmit="return false;" id="transferCheckUsernameForm">
                            @csrf
                            <input type="hidden" name="which" value="transferPresidentCheckUsername">
                            <div class="input_container">
                                <div class="title">
                                    Kullanıcı Adı:
                                </div>
                                <input type="text" name="username">
                            </div>
                            <div onclick="transferPresidentPostUsername()" class="next_button">
                                devam et
                            </div>
                        </form>
                    </li>
                    <li class="confirmation_transfer">
                        <div class="text">
                            Başkanlık aktarılacak kişi
                            <span>(Sadece kulübe üye olan kullanıcıya aktarılabilir.)</span>
                        </div>
                        <div class="information">
                            <input type="hidden" name="user_id" value="">
                            <img src="">
                            <div class="name_surname">

                            </div>
                            <div class="username">

                            </div>
                            <div class="school_email_error">
                                Okul e-postası onaylanmamış.
                            </div>
                        </div>
                        <div onclick="confirmationTransferPresident()" class="transfer_president_confirmation">
                            Onayla
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @endif
    @include('global/page_header')
    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <div class="club_ın_page_container">
                @include('club.global.clubInPageHeader')
                <div class="club_ın_page_bottom_contain">
                    @include('club.global.clubInPageLeftMenu')
                    <div class="club_ın_page_bottom_contain_content">
                        @auth()
                            <div class="club_ın_page_bottom_contain_content_header">
                                @if($clubAuth['clubAuthorityAdmin']==='1')
                                    <div onclick="showTransferPresident()" class="transfer_president">
                                        Başkanlığı Aktar
                                    </div>
                                @endif

                                @if($clubAuth['clubAuthority']==='1')
                                    @if($clubInvitations!='[]')
                                        <i style="color: #d9d92b;margin-right: 5px;" class="fas fa-exclamation"></i>
                                    @endif
                                    <div onclick="newMember()" class="club_new_announcements_news_button">
                                        Yeni Üye
                                    </div>
                                @endif
                            </div>
                        @endauth
                        <ul class="club_members">
                            <li>
                                <div class="title">
                                    Takım
                                    <span class="members_count">
                                        ({{$teamMembers->count()}} kişi)
                                    </span>
                                </div>
                                <ul class="club_members_list">
                                    @foreach($teamMembers as $clubMember)
                                        <li>
                                            @if($clubMember->role!=='3' && $clubAuth['clubAuthority']=='1' && $clubMember->user_id!=Auth::id() )
                                                <div onclick="memberSettings({{$clubMember->id}})"
                                                     class="club_member_setting">
                                                    <i class="fas fa-cog"></i>
                                                </div>
                                            @endif
                                            <a href="{{asset('/profil/'.$clubMember->user->username)}}">
                                                @if($clubMember->user->image =='0')
                                                    <img src="{{asset('img/user/default.png')}}">
                                                @else
                                                    <img src="{{asset('img/user/'.$clubMember->user->image)}}">
                                                @endif
                                            </a>
                                            <div class="name">
                                                {{$clubMember->user->name_surname}}
                                            </div>
                                            <div class="username">
                                                <a href="{{asset('profil/'.$clubMember->user->username)}}">
                                                    @ {{$clubMember->user->username}}
                                                </a>
                                                <div style="top: 2px;"
                                                     class="tooltip">
                                                    <i class="verification"></i>
                                                    <div class="tooltip_text verification_tooltip_text">
                                                        Okul e-postası onaylanmış.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="club_task_name">
                                                <i class="fas fa-circle"></i>
                                                {{$clubMember->role_name}}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <!--
                            <div class="club_member_search">
                                <input type="text" name="clubMemberListSearch" placeholder="Üye Ara...">
                            </div>-->
                            <li>
                                <div class=" title">
                                    Normal Üyeler
                                    <span class="members_count">
                                        ({{$regularMembers->count()}} kişi)
                                    </span>
                                </div>
                                <ul class="club_members_list club_normal_members_list">
                                    @forelse($regularMembers as $clubMember)
                                        <li>
                                            @if($clubMember->role!=='3' && $clubAuth['clubAuthority']=='1')
                                                <div onclick="memberSettings({{$clubMember->id}})"
                                                     class="club_member_setting">
                                                    <i class="fas fa-cog"></i>
                                                </div>
                                            @endif
                                            <a href="{{asset('profil/'.$clubMember->user->username)}}">
                                                @if($clubMember->user->image =='0')
                                                    <img src="{{asset('img/user/default.png')}}">
                                                @else
                                                    <img src="{{asset('img/user/'.$clubMember->user->image)}}">
                                                @endif
                                            </a>
                                            <div class="name">
                                                {{$clubMember->user->name_surname}}
                                            </div>
                                            <div class="username">
                                                <a href="{{asset('profil/'.$clubMember->user->username)}}">
                                                    @ {{$clubMember->user->username}}
                                                </a>
                                                <div style="top: 2px;"
                                                     class="tooltip">
                                                    <i class="verification"></i>
                                                    <div class="tooltip_text verification_tooltip_text">
                                                        Okul e-postası onaylanmış.
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="not_clubs_member">
                                            <script
                                                src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                            <lottie-player
                                                src="https://assets5.lottiefiles.com/packages/lf20_fyqtse3p.json"
                                                background="transparent" speed="1" style="width: 20px; height: 20px;"
                                                loop autoplay></lottie-player>
                                            <span>
                                                Şuan hiç üye yok.
                                           </span>
                                        </li>
                                    @endforelse
                                </ul>
                            </li>
                        </ul>
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
