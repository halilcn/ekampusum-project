<?php

use Illuminate\Support\Facades\Route;

//Open to everyone
Route::get('/command', 'homeController@artisan_command')->name('command');

Route::get('/', 'homeController@ana_sayfa_get')->name('anasayfa')->name('main');

Route::post('/', 'homeController@ana_sayfa_post');

Route::get('/download', 'homeController@download_get')->name('download');

Route::get('/yardim-destek', 'homeController@yardim_destek_get')->name('support');

Route::post('/yardim-destek', 'homeController@yardim_destek_post');

Route::get('/hakkimizda', 'homeController@hakkimizda_get')->name('about');

Route::get('/hizmet-kosullari', 'homeController@hizmet_kosullari_get')->name('terms');

Route::get('/aday-ogrenci', 'homeController@aday_ogrenci_get')->name('candidateStudent');

Route::get('/profil/{username}', 'homeController@get_profil')->name('profile');

Route::get('/ders-notlari', 'homeController@lesson_notes_get');
Route::post('/ders-notlari', 'homeController@lesson_notes_post');

Route::group(['prefix' => '/ders-notlari', 'as' => 'lessonNotes.'], function () {
    Route::get('/{sectionName}', 'homeController@lesson_notes_in_page_get')->name('inPage');
});

Route::get('/gruplar', function () {
    return view('groups.groupsPage');
})->name('groups');

Route::group(['prefix' => '/tartismalar', 'as' => 'discussion.'], function () {
    Route::get('/', 'homeController@tartismalar_get')->name('main');
    Route::post('/', 'homeController@tartismalar_post');

    Route::get('/{discussionLink}', 'homeController@tartismalar_in_page_get')->name('page');
    Route::post('/{discussionLink}', 'homeController@tartismalar_in_page_post');
});

Route::group(['prefix' => '/duyuru-haber', 'as' => 'announcementAndNews.'], function () {
    Route::get('/', 'homeController@duyuru_haber_get')->name('main');
    Route::post('/', 'homeController@duyuru_haber_post');

    Route::get('/{link}', 'homeController@duyuru_haber_in_page_get')->name('page');
    Route::post('/{link}', 'homeController@duyuru_haber_in_page_post');
});

Route::group(['prefix' => '/etkinlik', 'as' => 'events.'], function () {
    Route::get('/', 'homeController@etkinlik_get')->name('main');
    Route::post('/', 'homeController@etkinlik_post');

    Route::get('/{link}', 'homeController@etkinlik_in_page_get')->name('page');
    Route::post('/{link}', 'homeController@etkinlik_in_page_post');
});

Route::group(['prefix' => '/kulupler', 'as' => 'club.'], function () {
    Route::get('/', 'homeController@kulupler_get')->name('main');
    Route::post('/', 'homeController@kulupler_post');

    Route::get('/{link}/', 'homeController@kulupler_in_page_get')->name('page');
    Route::post('/{link}/', 'homeController@kulupler_in_page_post');

    Route::get('/{link}/duyuru-haber', 'homeController@kulupler_duyuru_haber_get')->name('page.announcementAndNews');
    Route::post('/{link}/duyuru-haber', 'homeController@kulupler_duyuru_haber_post');

    Route::get('/{link}/etkinlik', 'homeController@kulupler_etkinlik_get')->name('page.event');
    Route::post('/{link}/etkinlik', 'homeController@kulupler_etkinlik_post');

    Route::get('/{link}/uyeler', 'homeController@kulupler_uyeler_get')->name('page.member');
    Route::post('/{link}/uyeler', 'homeController@kulupler_uyeler_post');

    Route::get('/{link}/ayarlar', 'homeController@kulupler_ayarlar_get')->name('page.setting');
    Route::post('/{link}/ayarlar', 'homeController@kulupler_ayarlar_post');

});

Route::get('/itiraflar', 'homeController@itiraflar_get')->name('confession');
Route::post('/itiraflar', 'homeController@itiraflar_post');

Route::get('/emailConfirmation/{confirmation_key}', 'homeController@eposta_onay_get')->name('emailConfirmation');

Route::post('/globalLinks', 'homeController@globalLinks');

//Only Visitors
Route::group(['middleware' => 'userGuestCheck'], function () {
    Route::get('/kayit-ol', 'homeController@kayit_ol_get')->name('kayit_ol');
    Route::post('/kayit-ol', 'homeController@kayit_ol_post');

    Route::get('/giris-yap', 'homeController@giris_yap_get')->name('login');
    Route::post('/giris-yap', 'homeController@giris_yap_post');

    Route::group(['prefix' => '/sifre-degistir', 'name' => 'passwordChange.'], function () {
        Route::get('/', 'homeController@main_sifre_degistir_get')->name('main');
        Route::post('/', 'homeController@main_sifre_degistir_post');

        Route::get('/{slug}', 'homeController@main_sifre_degistir_slug_get')->name('page');
        Route::post('/{slug}', 'homeController@main_sifre_degistir_slug_post');
    });
});

//Only Auth
Route::group(['middleware' => 'userLoginCheck'], function () {
    Route::get('/kuluplerim', 'homeController@kuluplerim_get');

    Route::group(['prefix' => '/ayarlar', 'as' => 'setting.'], function () {
        Route::get('/hesap', 'homeController@hesap_get')->name('account');
        Route::post('/hesap', 'homeController@hesap_post');

        Route::get('/sifre', 'homeController@sifre_degistir_get')->name('password');
        Route::post('/sifre', 'homeController@sifre_degistir_post');

        Route::get('/bildirimler', 'homeController@bildirimler_get')->name('notification');
        Route::post('/bildirimler', 'homeController@bildirimler_post');
    });
    Route::get('/cikis', 'homeController@cikis_get');
});



