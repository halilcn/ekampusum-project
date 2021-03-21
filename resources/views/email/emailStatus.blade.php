<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @include('global/page_links')
</head>
<body>

<script>
    @if($status=='1')
    Notiflix.Report.Success( 'BAŞARILI', 'E-mail doğrulama başarılı!', 'Anasayfaya Git',function () {
       window.location.href='/';
    });
    @else
    Notiflix.Report.Warning( 'HATA', 'E-mail doğrulamada bir hata oluştu.Lütfen daha sonra tekrar deneyiniz.', 'Anasayfaya Git',function () {
      window.location.href='/';
    });
    @endif
</script>

</body>
</html>
