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
            <div class="password_change_code_post_success">
                <div class="animate">
                    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                    <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_UDFLD9.json"
                                   background="transparent" speed="1" style="width: 200px; height: 200px;" loop
                                   autoplay></lottie-player>
                </div>
                <div class="text">
                    <span class="email">asdsadasdasdhas@gmail.com</span>
                    adresine bilgilendirici e-mail gönderildi.Şifre değişikliği 1 saat içinde
                    yapılmazsa istek otomatik olarak iptal edilir.
                </div>
            </div>
            <div class="register_content">
                <div class="title">Şifre Değiştir</div>
                <br>
                <div class="input_box">
                    <input id="username_and_email" required type="text">
                    <label>Kullanıcı Adı veya E-mail Hesabı</label>
                </div>
                <button onclick="changePasswordPost()" class="register_and_sign_in_button">Gönder</button>
            </div>
            <br>
            <div class="register_questions">Zaten hesabın var mı?<a href="/giris-yap">Giriş Yap</a></div>
            <div class="register_questions"><a href="/kayit-ol">Kayıt Ol</a></div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>

</body>
</html>
