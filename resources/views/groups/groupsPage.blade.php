<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>

<body>
<div class="page_container">
    @include('global/page_header')

    <div class="page_bottom_content">
        @include('global/page_left_menu')

        <div class="page_content">

            <div class="page_content_header">
                <div class="page_content_title">
                    Gruplar
                </div>
            </div>
            <div class="group">
                <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                <div class="desktop_animate">
                    <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_znCQsa.json"
                                   background="transparent"
                                   speed="0.9" style="width: 500px; height: 300px;" loop autoplay></lottie-player>
                </div>
                <div class="mobile_animate">
                    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                    <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_znCQsa.json"
                                   background="transparent" speed="0.9" style="width: 300px; height: 300px;" loop
                                   autoplay></lottie-player>
                </div>
                <div class="text">
                    Yakında
                </div>
            </div>
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>


</body>


</html>
