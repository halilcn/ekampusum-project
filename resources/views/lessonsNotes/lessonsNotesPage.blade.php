<!doctype html>
<html>
<head>
    @include('global/page_links')
</head>

<body>

@auth
    <div class="popup_container lesson_notes_new_popup">
        <div class="popup_filter"></div>
        <div class="new_lesson_notes">
            <div class="cancel"></div>
            <div class="popup_title">
                Yeni Not
            </div>
            <form id="newLessonNote">
                @csrf
                <input type="hidden" name="which" value="storeLessonNote">
                <ul class="new_lesson_notes_ul">
                    <li>
                        <div class="title">
                            Bölüm Adı ve Üniversite Adı
                        </div>
                        <input type="text" name="section_university_name">
                    </li>
                    <li>
                        <div class="title">
                            Ders Adı
                        </div>
                        <input type="text" name="lesson_name">
                        <div class="input_info">
                            Örn: Fizik 2
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            Dönem
                        </div>
                        <input type="text" name="period">
                        <div class="input_info">
                            Örn: 3. Dönem (2. Sınıf-Güz Dönemi)
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            Dosya
                        </div>
                        <input type="file" id="lesson_note_file" name="file">
                        <label for="lesson_note_file">
                            Dosya Yükle
                        </label>
                        <div class="input_info">
                            Boyut maksimum 15mb olmalıdır.
                        </div>
                    </li>
                    <li>
                        <div onclick="postNewLessonNotesPopup()" class="lesson_notes_save">
                            Gönder
                        </div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
@endauth
<div class="page_container">
    @include('global/page_header')
    <div class="page_bottom_content">
        @include('global/page_left_menu')
        <div class="page_content">
            <div class="page_content_header">
                <div class="page_content_title">
                    Ders Notları
                </div>
            </div>
            <div class="lesson_notes_job_header">
                @auth
                    <div onclick="showNewLessonNotesPopup()" class="new_lesson_notes_button">
                        Yeni Not Ekle
                    </div>
            @endauth
            <!--
                  <div class="lesson_notes_job_header_search">
                      <input type="text" placeholder="Bölüm Ara..">
                  </div>-->
            </div>
            <ul class="lesson_notes_job_ul">
                @forelse($schoolSections as $schoolSection)
                    <a href="{{route('lessonNotes.inPage',['sectionName'=>$schoolSection->link,'schoolId'=>$schoolSection->school_id])}}">
                        <li>
                            <div class="lesson_notes_job_ul_filter"></div>
                            <img src="{{asset('img/lessons/'.$schoolSection->background_image)}}"
                                 class="lesson_notes_job_ul_backgorund">
                            <div class="lesson_notes_job_ul_name">
                                {{$schoolSection->name}}
                            </div>
                            <div class="lesson_notes_job_ul_school">
                                <div
                                    class="@if($schoolSection->school_id == '1')ktun_filter @else su_filter @endif"></div>
                                <div class="name @if($schoolSection->school_id == '1')ktun_name @else su_name @endif">
                                    {{$schoolSection->school->name}}
                                </div>
                            </div>
                        </li>
                    </a>
                @empty
                @endforelse
            </ul>
            <div class="lesson_notes_paginate">
                {{$schoolSections->links()}}
            </div>
        </div>
    </div>
</div>
@include('global.page_mobile_bottom_menu')
</body>

</html>
