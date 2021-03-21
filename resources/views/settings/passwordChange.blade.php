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

                    <div class="password_settings">
                        <div class="settings_title">Şifre Değiştir</div>
                        <ul>
                            <form id="passwordChange">
                                {{csrf_field()}}
                                <li>
                                    <div class="password_title">Mevcut Şifre</div>
                                    <input name="real_password" type="password">
                                </li>
                                <li>
                                    <div class="password_title">Yeni Şifre</div>
                                    <input name="new_password" type="password">
                                </li>
                                <li>
                                    <div class="password_title">Yeni Şifre Tekrar</div>
                                    <input name="new_password_repeat" type="password">
                                </li>
                            </form>
                            <li>
                                <div onclick="changePassword()" class="change_password_button">Şifreyi Değiştir</div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
    @include('global.footer')
</div>
</body>
</html>
