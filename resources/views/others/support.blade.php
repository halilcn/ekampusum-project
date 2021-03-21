<!doctype html>
<html>
<head>
    @include('global.page_links')
</head>
<body>

<div class="page_container">
    @include('global.page_header')
    <div class="page_bottom_content">
        <div class="page_content_not_left_menu">
            <div class="help_container">
                <div class="title">
                    Yardım & Destek
                </div>
                <div class="help">
                    @if(!isset(request()->helpType))
                        <ul class="help_main">
                            <li>
                                <div class="title">
                                    Hesap
                                </div>
                                <ul class="help_list">
                                    <li>
                                        <a href="{{route('support',['helpType'=>'nko'])}}">
                                            <div class="animate">
                                                <script
                                                    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                <lottie-player
                                                    src="https://assets8.lottiefiles.com/packages/lf20_wd1udlcz.json"
                                                    background="transparent" speed="1"
                                                    style="width: 150px; height: 150px;"
                                                    loop autoplay></lottie-player>
                                            </div>
                                            <div class="text">
                                                Nasıl kayıt olunur?
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('support',['helpType'=>'oen'])}}">
                                            <div class="animate">
                                                <script
                                                    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                <lottie-player
                                                    src="https://assets1.lottiefiles.com/packages/lf20_tDEN64.json"
                                                    background="transparent" speed="1"
                                                    style="width: 150px; height: 150px;"
                                                    loop autoplay></lottie-player>
                                            </div>
                                            <div class="text">
                                                Okul e-postası nedir?
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('support',['helpType'=>'oeno'])}}">
                                            <div class="animate">
                                                <script
                                                    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                <lottie-player
                                                    src="https://assets9.lottiefiles.com/packages/lf20_taz4H9.json"
                                                    background="transparent" speed="1"
                                                    style="width: 150px; height: 150px;"
                                                    loop autoplay></lottie-player>
                                            </div>
                                            <div class="text">
                                                Okul e-postası nasıl onaylanır?
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('support',['helpType'=>'sns'])}}">
                                            <div class="animate">
                                                <script
                                                    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                <lottie-player
                                                    src="https://assets1.lottiefiles.com/packages/lf20_IQ2Fuq.json"
                                                    background="transparent" speed="1"
                                                    style="width: 150px; height: 150px;"
                                                    loop autoplay></lottie-player>
                                            </div>
                                            <div class="text">
                                                Şifre nasıl sıfırlanır?
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <div class="title">
                                    Kulüp
                                </div>
                                <ul class="help_list">
                                    <li>
                                        <a href="{{route('support',['helpType'=>'nka'])}}">
                                            <div class="animate">
                                                <script
                                                    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                <lottie-player
                                                    src="https://assets8.lottiefiles.com/private_files/lf30_ykc5k1fg.json"
                                                    background="transparent" speed="1"
                                                    style="width: 150px; height: 150px;"
                                                    loop autoplay></lottie-player>
                                            </div>
                                            <div class="text">
                                                Nasıl kulüp açılır?
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('support',['helpType'=>'uyn'])}}">
                                            <div class="animate">
                                                <script
                                                    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                <lottie-player
                                                    src="https://assets7.lottiefiles.com/packages/lf20_BnQ5WP.json"
                                                    background="transparent" speed="1"
                                                    style="width: 150px; height: 150px;"
                                                    loop autoplay></lottie-player>
                                            </div>
                                            <div class="text">
                                                Üye yetkileri nelerdir?
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('support',['helpType'=>'uynd'])}}">
                                            <div class="animate">
                                                <script
                                                    src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                                <lottie-player
                                                    src="https://assets7.lottiefiles.com/packages/lf20_1dwcx6ob.json"
                                                    background="transparent" speed="1"
                                                    style="width: 150px; height: 150px;"
                                                    loop autoplay></lottie-player>
                                            </div>
                                            <div class="text">
                                                Üye yetkisi nasıl değiştirilir?
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <div class="title">
                                    Aradığını Bulamadın mı?
                                </div>
                                <ul class="ask_us">
                                    <li class="text">
                                        Bize sor,cevaplayalım!
                                    </li>
                                    <li>
                                        <form id="questionForm" action="post">
                                            @csrf
                                            <textarea name="question"></textarea>
                                            <div class="form_bottom">
                                                <input name="email" type="email"
                                                       @auth value="{{Auth::user()->register_email}}" readonly
                                                       style="opacity: 0.5;cursor: default;"
                                                       @endauth  placeholder="E-mail">
                                                <div onclick="postQuestion()" class="save">
                                                    Gönder
                                                </div>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                    @if(request()->helpType == 'nko')
                        <ul class="help_detail">
                            <li onclick="window.history.back()" class="back_button">
                                geri
                            </li>
                            <li class="title">
                                Nasıl Kayıt Olunur?
                            </li>
                            <li>
                                <ul class="step_list">
                                    <li>
                                        <div class="number">
                                            1.
                                        </div>
                                        <div class="explanation">
                                            <a href="#">
                                                ekampusum.com
                                            </a> adresine gidin.Sağ üst köşedeki <span>Kayıt Ol</span>'a
                                            tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            2.
                                        </div>
                                        <div class="explanation">
                                            Açılan sayfada gerekli alanları doldurun.Alanları doldururken uyarıları
                                            dikkate alın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            3.
                                        </div>
                                        <div class="explanation">
                                            <span>Kaydol</span>'a tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            4.
                                        </div>
                                        <div class="explanation">
                                            Hesap oluşturmayı tamamlamak için e-posta adresinizi
                                            <span>doğrulamanız</span> gerekir.
                                        </div>
                                    </li>
                                    <li class="under_help">
                                        <div class="number">
                                            1.
                                        </div>
                                        <div class="explanation">
                                            E-posta hesabınıza giriş yapın.
                                        </div>
                                    </li>
                                    <li class="under_help">
                                        <div class="number">
                                            2.
                                        </div>
                                        <div class="explanation">
                                            <span>ekampusum.com</span>'dan gelen e-postaya girin ve <span>onayla</span>'ya
                                            tıklayın.
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @elseif(request()->helpType == 'oen')
                        <ul class="help_detail">
                            <li onclick="window.history.back()" class="back_button">
                                geri
                            </li>
                            <li class="title">
                                Okul E-postası Nedir?
                            </li>
                            <li>
                                <ul class="explanation">
                                    <li>
                                        Okul e-postası <span>ekampusum.com</span>'da gerçek öğrenci olduğunuzu kanıtlar.
                                        Bazı işlemleri yapmak için okul e-posta <span>şartı</span> vardır.
                                        Okul e-postanızı <span>onaylamak</span> için <a
                                            href="{{route('support',['helpType'=>'oeno'])}}">buraya tıklayın.</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @elseif(request()->helpType == 'oeno')
                        <ul class="help_detail">
                            <li onclick="window.history.back()" class="back_button">
                                geri
                            </li>
                            <li class="title">
                                Okul E-postası Nasıl Onaylanır?
                            </li>
                            <li>
                                <ul class="step_list">
                                    <li>
                                        <div class="explanation">
                                            Eğer kayıt olurken okul e-postası kullandıysanız, okul e-postası
                                            <span>onaylanmış</span> sayılır.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            1.
                                        </div>
                                        <div class="explanation">
                                            <a href="{{asset('/giris-yap')}}">Giriş</a> yapın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            2.
                                        </div>
                                        <div class="explanation">
                                            <span>Profili Düzenle</span>'ye gidin.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            3.
                                        </div>
                                        <div class="explanation">
                                            Kayıtlı e-posta hesabının onaylandığına emin ol.
                                            Kayıtlı e-posta hesabın onaylanmadan okul e-postası <span>onaylanamaz</span>.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            4.
                                        </div>
                                        <div class="explanation">
                                            Okul e-postanızı yazın ve <span>Kaydet</span>'e tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            5.
                                        </div>
                                        <div class="explanation">
                                            <span>Onayla</span>'ya tıklayın.İşlemin tamamlanması için gönderilen <span>e-mailin</span>
                                            onaylanması gerekir.
                                        </div>
                                    </li>
                                    <li class="under_help">
                                        <div class="number">
                                            1.
                                        </div>
                                        <div class="explanation">
                                            Okul e-posta hesabınıza giriş yapın.
                                        </div>
                                    </li>
                                    <li class="under_help">
                                        <div class="number">
                                            2.
                                        </div>
                                        <div class="explanation">
                                            <span>ekampusum.com</span>'dan gelen e-postaya girin ve <span>onayla</span>'ya
                                            tıklayın.
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @elseif(request()->helpType == 'sns')
                        <ul class="help_detail">
                            <li onclick="window.history.back()" class="back_button">
                                geri
                            </li>
                            <li class="title">
                                Şifre Nasıl Sıfırlanır?
                            </li>
                            <li>
                                <ul class="step_list">
                                    <li>
                                        <div class="number">
                                            1.
                                        </div>
                                        <div class="explanation">
                                            <a href="{{asset('/giris-yap')}}">Giriş Yap</a>'a ya da
                                            <a href="{{asset('/kayit-ol')}}">Kayıt Ol</a>'a tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            2.
                                        </div>
                                        <div class="explanation">
                                            Aşağıda bulunan <span>Şifreni mi unuttun</span>'a tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            3.
                                        </div>
                                        <div class="explanation">
                                            <span>Kullanıcı adını</span> veya <span>kayıtlı e-posta</span>yı girin.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            4.
                                        </div>
                                        <div class="explanation">
                                            <span>E-mail gönderildi</span> onay mesajını gördükten sonra e-posta
                                            hesabınıza girin ve
                                            <span>ekampusum.com</span>'dan gelen iletiyi açın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            5.
                                        </div>
                                        <div class="explanation">
                                            <span>Devam Et</span>'e tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            6.
                                        </div>
                                        <div class="explanation">
                                            Gelen ekranda yeni şifrenizi girin ve <span>Şifreyi Değiştir</span>'e
                                            tıklayın.
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @elseif(request()->helpType == 'nka')
                        <ul class="help_detail">
                            <li onclick="window.history.back()" class="back_button">
                                geri
                            </li>
                            <li class="title">
                                Nasıl Kulüp Açılır?
                            </li>
                            <li>
                                <ul class="step_list">
                                    <li>
                                        <div class="number">
                                            1.
                                        </div>
                                        <div class="explanation">
                                            <span>ekampusum.com</span>'a <a href="{{asset('/giris-yap')}}">giriş</a>
                                            yapın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            2.
                                        </div>
                                        <div class="explanation">
                                            Okul e-posta hesabı <a href="{{route('support',['helpType'=>'oeno'])}}">onaylama
                                                işlemini</a> yaptığınıza emin olun.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            3.
                                        </div>
                                        <div class="explanation">
                                            <span>Okul Kulüplerine</span> tıklayın ve çıkan ekranda
                                            <span>Yeni Kulüp</span>'e
                                            tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            4.
                                        </div>
                                        <div class="explanation">
                                            Açılan ekranda uyarıları dikkate alarak gerekli alanları doldurun ve <span>Gönder</span>'e
                                            tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            5.
                                        </div>
                                        <div class="explanation">
                                            <span>İstek Başarıyla Oluşturdu</span> uyarısını aldığınızda işleminiz
                                            sıraya
                                            alınır.En geç 24 saat içerisinde kullanıcı bilgilendirilir.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="explanation">
                                            <span>Not:</span>
                                            Yeni Kulüp isteğinde bulunulduğunda, başvuran kullanıcı <span>başkan</span>
                                            olarak atanır.
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @elseif(request()->helpType == 'uyn')
                        <ul class="help_detail">
                            <li onclick="window.history.back()" class="back_button">
                                geri
                            </li>
                            <li class="title">
                                Üye Yetkileri Nelerdir?
                            </li>
                            <li>
                                <ul class="step_list">
                                    <li>
                                        <div class="explanation">
                                            <span>Yetki Yok:</span> Standart üyedir.Üye olduğunda varsayılan olarak
                                            kullanıcıya atanır.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="explanation">
                                            <span>Orta Düzey Yetki:</span> Kullanıcı sadece Duyur-Haber ve Etkinlik
                                            paylaşabilir.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="explanation">
                                            <span>Üst Düzey Yetki:</span> Başkan ile aynı yetkilere sahiptir.Başkanın
                                            yaptığı her işlemi yapabilir.
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @elseif(request()->helpType == 'uynd')
                        <ul class="help_detail">
                            <li onclick="window.history.back()" class="back_button">
                                geri
                            </li>
                            <li class="title">
                                Üye Yetkisi Nasıl Değiştirilir?
                            </li>
                            <li>
                                <ul class="step_list">
                                    <li>
                                        <div class="number">
                                            1.
                                        </div>
                                        <div class="explanation">
                                            <span>ekampusum.com</span>'a <a href="{{asset('/giris-yap')}}">giriş</a>
                                            yapın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            2.
                                        </div>
                                        <div class="explanation">
                                            <span>Kulüplerim</span>'e gidin ve işlem yapmak istediğiniz kulübün
                                            sayfasına gidin.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            3.
                                        </div>
                                        <div class="explanation">
                                            <span>Üyeler</span>'e tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            4.
                                        </div>
                                        <div class="explanation">
                                            Yetkisini değiştirmek istediğiniz kullanıcıyı bulun ve <span>ayarlar simgesine</span>
                                            tıklayın.
                                        </div>
                                    </li>
                                    <li>
                                        <div class="number">
                                            5.
                                        </div>
                                        <div class="explanation">
                                            Açılan pencerede seçiminizi yaptıktan sonra <span>Kaydet</span>'e tıklayın.
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('global.footer')
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
