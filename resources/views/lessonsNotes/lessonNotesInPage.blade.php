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
                    Ders Notları /
                    <span>
                    {{$schoolSection->name}}
                    </span>
                </div>
            </div>
            @forelse($periodFiles as $periodNumber=>$files)
                <div class="lesson_notes_download_container">
                    <div class="lesson_notes_download">
                        <div class="title">
                            <i class="far fa-clock"></i>
                            {{$periodNumber.'.'.'Dönem'}}
                        </div>
                        <ul class="lesson_notes_download_ul">
                            @foreach($files as $file)
                                <li>
                                    <div class="lesson_name">
                                        {{$file->lesson_name}}
                                    </div>
                                    <div class="lesson_information">
                                        <div>
                                            <i class="fas fa-user"></i>
                                            {{$file->user->username}}
                                        </div>
                                        <div>
                                            <i class="fas fa-folder"></i>
                                            {{$file->file_size.'mb'}}
                                        </div>
                                    </div>
                                    <a href="{{route('download',['file'=>$file->file_path])}}"
                                       class="lesson_download">
                                        <i class="far fa-arrow-alt-circle-down"></i>
                                        İndir
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <div class="not_lesson_notes">
                    Hiç ders notu yok.
                </div>
            @endforelse
        </div>
    </div>
    @include('global.page_mobile_bottom_menu')
</div>
</body>
</html>
