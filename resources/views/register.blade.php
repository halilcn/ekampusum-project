<!doctype html>

<head>
    @include('global/page_links')
</head>

<body>

<div class="page_container">
    @include('global/page_header')
    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <div class="register_title">
                Sende ekampusum'e Katıl!
            </div>
            <div class="register_content">
                <div class="title">Kayıt Ol</div>
                <form id="register_form">
                    {{csrf_field()}}
                    <input type="hidden" value="register" name="which">
                    <div class="input_box">
                        <input name="name_surname" required type="text">
                        <label>Ad-Soyad</label>
                    </div>
                    <div class="input_box">
                        <input onkeyup="ajaxCheck(this,'usernameCheck')" name="username" required type="text">
                        <label>Kullanıcı Adı</label>
                        <div id="username_warning" class="input_warning"></div>
                    </div>
                    <div class="input_box">
                        <input onkeyup="ajaxCheck(this,'registerEmailCheck')" class="register_email"
                               name="register_email" required
                               type="text">
                        <label>E-posta<span style="font-size: 8px;">(*Okul e-posta hesabın tüm özellikleri kullanmanı sağlar.)</span></label>
                        <div id="register_email_warning" class="input_warning"></div>
                    </div>
                    <div class="input_box">
                        <input name="password" required type="password">
                        <label>Şifre</label>
                    </div>
                    <div class="input_box">
                        <input name="password_2" required type="password">
                        <label>Şifre Tekrar</label>
                    </div>
                    <div class="register_checkbox">
                        <input class="inp-cbx" id="cbx" type="checkbox" name="conditions"
                               style="display: none"/>
                        <label class="cbx" for="cbx">
                            <span>
                            <svg width="9px" height="6px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                            </span>
                        </label>
                        <span>
                                Bu kutucuğu onaylayarak
                                <a target="_blank" href="{{asset('hizmet-kosullari')}}">
                                    hizmet koşullarını
                                </a>
                                kabul etmiş sayılırsınız.
                            </span>
                    </div>


                </form>
                <button onclick="registerSend()" class="register_and_sign_in_button">Kaydol</button>
            </div>
            <br>
            <div class="register_questions">Zaten hesabın var mı?<a href="/giris-yap">Giriş Yap</a></div>
            <div class="register_questions"><a href="/sifre-degistir">Şifreni mi unuttun?</a></div>

        </div>
    </div>
</div>

</body>

</html>
