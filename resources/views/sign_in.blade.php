<html>

<head>
    @include('global/page_links')
</head>
<body>
@if(\Illuminate\Support\Facades\Session::has('passwordChangeSuccess'))
    <script>
        Notiflix.Notify.Success('{{\Illuminate\Support\Facades\Session::get('passwordChangeSuccess')}}');
    </script>
@endif
<div class="page_container">
    @include('global/page_header')
    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <div class="sign_in_content">
                <div class="title">Giriş Yap</div>
                <form id="signForm" method="post" action="">
                    {{csrf_field()}}
                    <br>
                    <div class="input_box">
                        <input required type="text" name="username_or_email">
                        <!--     onkeydown="if (event.keyCode==13) alert()" -->
                        <label>Kullanıcı Adı veya E-posta</label>
                    </div>
                    <div class="input_box">
                        <input required type="password" name="password">
                        <label>Şifre</label>
                    </div>
                    <div class="remember_me">
                        <input class="inp-cbx" id="cbx" type="checkbox" name="remember_me" value="1"
                               style="display: none"/>
                        <label class="cbx" for="cbx">
                            <span>
                            <svg width="9px" height="6px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                            </span>
                            <span>
                               Beni Hatırla
                            </span>
                        </label>
                    </div>
                </form>
                <button onclick="sign()" style="margin-top: 0px;" class="register_and_sign_in_button">Giriş Yap</button>
            </div>
            <br>
            <div class="register_questions">Hesabın yok mu?<a href="/kayit-ol">Kayıt Ol</a></div>
            <div class="register_questions"><a href="/sifre-degistir">Şifreni mi unuttun?</a></div>
        </div>
    </div>
</div>
</body>

</html>
