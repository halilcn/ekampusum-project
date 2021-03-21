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
            <div class="settings_container">
                @include('settings/settings_left')
                <div class="settings_contain">
                    <div class="settings_title">Bildirimler</div>
                    <ul class="notifications_ul">
                        <form id="notificationsForm" enctype="multipart/form-data" method="post">
                            @csrf
                            <li>
                                <div class="checkbox_div">
                                    <input class="checkbox" name="discussion_new_comment" type="checkbox"
                                           id="checkbox"
                                           @if($notifications->discussion_new_comment=='1') checked
                                           @endif  value="1">
                                    <label class="checkbox_label" for="checkbox"></label>
                                </div>
                                <label for="checkbox" class="notifications_contain">
                                    Tartışmama yeni yorum geldiğinde <span>bildirim</span> gelsin.
                                </label>
                            </li>
                            <li>
                                <div class="checkbox_div">
                                    <input class="checkbox" name="new_events" type="checkbox" id="checkbox_2"
                                           @if($notifications->new_events=='1') checked @endif value="1"/>
                                    <label class="checkbox_label" for="checkbox_2"></label>
                                </div>
                                <label for="checkbox_2" class="notifications_contain">
                                    Yeni bir etkinlik paylaşıldığında <span>e-posta</span> ile bilgilendiriliyim.
                                </label>
                            </li>
                            <li style="opacity: 0.5;pointer-events: none;">
                                <div class="checkbox_div">
                                    <input class="checkbox" name="discussion_new_comment_mail" type="checkbox"
                                           id="checkbox_discussion_mail"
                                           @if($notifications->discussion_new_comment_mail=='1') checked
                                           @endif  value="1">
                                    <label class="checkbox_label" for="checkbox_discussion_mail"></label>
                                </div>
                                <label for="checkbox_discussion_mail" class="notifications_contain">
                                    Tartışmama yeni yorum geldiğinde <span>e-posta</span> gelsin.
                                </label>
                            </li>
                        </form>
                        <li class="notifications_button_li">
                            <button onclick="notificationsPost()" class="notifications_save">Kaydet</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
    @include('global.footer')
</div>
</body>

</html>
