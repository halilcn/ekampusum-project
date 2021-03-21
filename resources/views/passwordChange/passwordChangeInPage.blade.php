<!doctype html>
<head>
    @include('global.page_links')
</head>
<body>
<div class="page_container">
    @include('global.page_header')

    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <br>
            <br>
            <div class="register_content">
                <br>
                <form id="newPasswordForm">
                    @csrf
                    <div class="input_box">
                        <input name="new_password" type="password" required>
                        <label>Yeni Şifre</label>
                    </div>
                    <div class="input_box">
                        <input name="new_password_repeat" type="password" required>
                        <label>Yeni Şifre Tekrar</label>
                    </div>
                </form>
                <button onclick="newPasswordPost()" class="register_and_sign_in_button">Değiştir</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>
