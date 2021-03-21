//*********Global Pages Start*********//
//------------------------------Page Header ------------------------------//
function delay(fn, ms) {
    let timer = 0
    return function (...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
}

function functionDelay(fn, timeout) {
    clearTimeout($(this).data('timer'))
    var timer = setTimeout(function () {
        fn();
    }, timeout);
    $(this).data('timer', timer);
}

function notificationsAllDelete() {
    Notiflix.Confirm.Show('Uyarı', 'Tüm bildirimleri silmek istediğinize emin misiniz?', 'Evet', 'Hayır',
        function () {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                type: 'POST',
                url: '/globalLinks',
                data: {which: 'notificationsAllDelete'},
                beforeSend: function () {
                    Notiflix.Block.Dots('.page_header_notifications_bottom');
                },
                success: function (response) {
                    Notiflix.Block.Remove('.page_header_notifications_bottom');
                    if (response.status === '1') {
                        Notiflix.Notify.Success(response.message);
                        $(".page_header_notifications_ul li").remove();
                        $(".page_header_notifications_ul").html('<li class="page_header_notifications_eror">Hiç bildirim yok</li>');

                    } else {
                        Notiflix.Notify.Failure(response.message);
                    }
                }
            })
        });
}

function clubInvitationAnswerPost(dataId, answer, clubInvitationId) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/globalLinks',
        data: {
            which: 'notificationsClubInvitationAnswer',
            dataId: dataId,
            answer: answer,
            clubInvitationId: clubInvitationId
        },
        beforeSend: function () {
            Notiflix.Block.Dots('.page_header_notifications_bottom');
        },
        success: function (response) {
            Notiflix.Block.Remove('.page_header_notifications_bottom', 200);
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                if (dataId != '0') {
                    Notiflix.Notify.Success(response.message);
                    $(".page_header_notifications_club_li[data-id=" + dataId + "] ").remove();
                    return 0;
                }
                location.reload();
            }
        }
    })
}

function notificationsGet(which) {
    function firstGet() {
        var clubGet = $("meta[name='clubGet']").attr('content');
        if (clubGet === '0') {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                type: 'POST',
                url: '/globalLinks',
                data: {which: 'notificationsGet', id: 0},
                beforeSend: function () {
                    $("meta[name='clubGet']").attr('content', '1');
                    Notiflix.Block.Dots('.page_header_notifications_bottom');
                },
                success: function (response) {
                    if (response.status) {
                        Notiflix.Notify.Failure(response.message);
                        return 0;
                    } else {
                        Notiflix.Block.Remove('.page_header_notifications_bottom', 200);
                        if (response.length > 0) {
                            for (var i = 0; i < response.length; i++) {
                                if (response[i]['notificationId'] == '1') {
                                    $(".page_header_notifications_ul").append(discussionCommentNotification(
                                        response[i]['username'],
                                        response[i]['userImg'],
                                        response[i]['title'],
                                        response[i]['date'],
                                        response[i]['link'],
                                        response[i]['dataId'],
                                        response[i]['view']
                                    ));
                                } else if (response[i]['notificationId'] == '2') {
                                    $(".page_header_notifications_ul").append(clubInvitationNotifications(
                                        response[i]['username'],
                                        response[i]['userImg'],
                                        response[i]['clubName'],
                                        response[i]['clubInvitationId'],
                                        response[i]['dataId'],
                                        response[i]['view']
                                    ))
                                } else if (response[i]['notificationId'] == '3') {
                                    $(".page_header_notifications_ul").append(clubRoleNotifications(
                                        response[i]['username'],
                                        response[i]['userImg'],
                                        response[i]['clubName'],
                                        response[i]['roleName'],
                                        response[i]['date'],
                                        response[i]['dataId'],
                                        response[i]['view']
                                    ))
                                }
                            }
                            if (response.length === 6) {
                                $(".page_header_notifications_ul").append('' +
                                    '<li class="page_header_notifications_button">\n' +
                                    '<button onclick="notificationsGet(\'getMore\')">Daha Fazla Yükle</button>\n' +
                                    '</li>');
                            }
                        } else {
                            $(".page_header_notifications_ul,.page_header_notifications_bottom").css('height', '100px');
                            $(".page_header_notifications_ul").append('<li class="page_header_notifications_eror">Hiç bildirim yok</li>');
                        }

                    }
                }
            })
        }
    }

    if (which === 'get') {
        firstGet();
    } else if (which === 'update') {
        $("meta[name='clubGet']").attr('content', '0');
        $(".page_header_notifications_ul").empty();
        firstGet();
    } else if (which === 'getMore') {
        var lastId = $(".page_header_notifications_ul li:nth-last-child(2)").attr('data-id');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            type: 'POST',
            url: '/globalLinks',
            data: {which: 'notificationsGet', id: lastId},
            beforeSend: function () {
                Notiflix.Block.Dots('.page_header_notifications_ul li:last-child');
            },
            success: function (response) {
                if (response.status) {
                    Notiflix.Notify.Failure(response.message);
                    return 0;
                } else {
                    if (response.length > 0) {
                        $(".page_header_notifications_ul li:last-child").remove();
                        for (var i = 0; i < response.length; i++) {
                            if (response[i]['notificationId'] == '1') {
                                $(".page_header_notifications_ul").append(discussionCommentNotification(
                                    response[i]['username'],
                                    response[i]['userImg'],
                                    response[i]['title'],
                                    response[i]['date'],
                                    response[i]['link'],
                                    response[i]['dataId'],
                                    response[i]['view']
                                ));
                            } else if (response[i]['notificationId'] == '2') {
                                $(".page_header_notifications_ul").append(clubInvitationNotifications(
                                    response[i]['username'],
                                    response[i]['userImg'],
                                    response[i]['clubName'],
                                    response[i]['clubInvitationId'],
                                    response[i]['dataId'],
                                    response[i]['view']
                                ))
                            } else if (response[i]['notificationId'] == '3') {
                                $(".page_header_notifications_ul").append(clubRoleNotifications(
                                    response[i]['username'],
                                    response[i]['userImg'],
                                    response[i]['clubName'],
                                    response[i]['roleName'],
                                    response[i]['date'],
                                    response[i]['dataId'],
                                    response[i]['view']
                                ))
                            }
                        }
                        $(".page_header_notifications_ul").append('' +
                            '<li class="page_header_notifications_button">\n' +
                            '<button onclick="notificationsGet(\'getMore\')">Daha Fazla Yükle</button>\n' +
                            '</li>');
                    } else {
                        $(".page_header_notifications_ul li:last-child").empty();
                        $(".page_header_notifications_ul li:last-child").html('<div class="page_header_notifications_eror">Başka bildirim yok</div>');
                    }

                }
            }
        })
    }

    function imageControl(src) {
        if (src == '0') {
            return '<img src="/img/user/default.png">'
        } else {
            return '<img src="/img/user/' + src + '">'
        }
    }

    function discussionCommentNotification(username, userImg, title, date, link, dataId, view) {
        var isView = view == '0' ? 'page_header_notifications_not_view' : '';
        return '<li data-id="' + dataId + '" class="page_header_notifications_global_li ' + isView + '">\n' +
            '<a href="/tartismalar/' + returnText(link) + '">' +
            imageControl(userImg) +
            '            <div class="page_header_notifications_global_li_contain">\n' +
            '            <div class="page_header_notifications_global_li_contain_text">\n' +
            '            <span>' + returnText(username) + '</span> adlı kullanıcı\n' +
            '            <span>' + returnText(title) + '</span> başlıklı\n' +
            '        tartışmana yorum yaptı.\n' +
            '        </div>\n' +
            '        <div class="page_header_notifications_information">\n' +
            date +
            '        </div>\n' +
            '        </div>\n' +
            '        </a>' +
            '        </li>\n';
    }

    function clubInvitationNotifications(username, userImg, clubName, clubInvitationId, dataId, view) {
        var isView = view == '0' ? 'page_header_notifications_not_view' : '';
        return ' <li data-id="' + dataId + '" class="page_header_notifications_club_li ' + isView + '">\n' +
            '                                <div class="page_header_notifications_club_li_top">\n' +
            imageControl(userImg) +
            '                                    <div class="page_header_notifications_club_li_contain">\n' +
            '                                        <div class="page_header_notifications_club_li_text">\n' +
            '                                            <span>' + returnText(username) + '</span> adlı kullanıcı seni\n' +
            '                                            <span>' + returnText(clubName) + '</span> adlı kulübe davet etti.\n' +
            '                                        </div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                                <div class="page_header_notifications_club_li_bottom">\n' +
            '                                    <button onclick="clubInvitationAnswerPost(' + dataId + ',\'1\',' + clubInvitationId + ')">Kabul Et</button>\n' +
            '                                    <button onclick="clubInvitationAnswerPost(' + dataId + ',\'0\',' + clubInvitationId + ')">Reddet</button>\n' +
            '                                </div>\n' +
            '                            </li>\n';
    }

    function clubRoleNotifications(username, userImg, clubName, roleName, date, dataId, view) {
        var isView = view == '0' ? 'page_header_notifications_not_view' : '';
        return '<li data-id="' + dataId + '" class="page_header_notifications_global_li ' + isView + '">\n' +
            '<a>' +
            imageControl(userImg) +
            '                                <div class="page_header_notifications_global_li_contain">\n' +
            '                                    <div class="page_header_notifications_global_li_contain_text">\n' +
            '                                        <span>' + returnText(username) + '</span> adlı kullanıcı\n' +
            '                                        <span>' + returnText(clubName) + '</span> adlı kulüpte rolünü\n' +
            '                                        <span>' + returnText(roleName) + '</span> olarak değiştirdi.\n' +
            '                                    </div>\n' +
            '                                    <div class="page_header_notifications_information">\n' +
            date +
            '                                    </div>\n' +
            '                                </div>\n' +
            '</a>'
        '                            </li>';
    }
}

//If Clicked Out Of Notifications Popup (Desktop)
$(document).ready(function () {
    $("body").click(function (e) {
        if (!$(e.target).is(".page_header_notifications_div , .page_header_notifications_div *")) {
            if ($(".notifications_desktop_bottom").css('display') === 'block') {
                $(".notifications_desktop_bottom").fadeOut(150);
                $(".page_header_notifications_div > i").toggleClass('page_header_active_button');
            }
        }
    })
});

//FadeToggle Notifications (Desktop)
function showNotifications() {
    $(".notifications_desktop_bottom").fadeToggle(150);
    $(".page_header_notifications_div > i").toggleClass('page_header_active_button');
    notificationsGet('get');
}

//FadeToggle Notifications (Mobile)
function mobileShowNotifications(element) {
    $(".notifications_mobile_bottom").fadeToggle(150);
    $(element).toggleClass('active_button').css('border-color', 'transparent');
    // Get First Notifications
    notificationsGet('get');
}

//Show User Dropdown (Desktop)
function showUserDropdown() {
    $(".page_header_user > ul").fadeToggle(150);
    $(".page_header_user ").toggleClass('page_header_active_button');
}

//If Clicked Out Of User Dropdown (Desktop)
$(document).ready(function () {
    $("body").click(function (e) {
        if (!$(e.target).is(".page_header_user , .page_header_user *")) {
            if ($(".page_header_user > ul").css('display') === 'block') {
                $(".page_header_user > ul").fadeOut(150);
                $(".page_header_user ").toggleClass('page_header_active_button');
            }
        }
    })
});

//All Page Textarea Height
function textareaHeight(element, height) {
    element.css('height', '');
    element.css('height', Math.min(element.prop('scrollHeight'), height));
}

//All Page Comment Button Disable/Enable
$(document).ready(function () {
    $('.comment textarea').on('keyup', function () {
        var lenght = $(this).val().length;
        if (lenght == 0) {
            $('.comment_button').addClass('comment_button_not_click');
        } else {
            $('.comment_button').removeClass('comment_button_not_click');
        }
    })
})

//All Page Comment Textarea
$(document).ready(function () {
    //Comment Textarea Open
    $(".comment > form > textarea ").focus(function () {
        //Discussion Comment Div
        $(".comment").animate({
            height: '100px'
        }, 200)
            .css("cssText", "border-color:#f1f1f1 !important;")
            .css('background-color', '#f5f5f5');

        setTimeout(function () {
            $(".comment").css("height", "auto");
            $(".comment > button").css("display", "block");
        }, 350);
    });
})

function returnText(text) {
    return text.replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

}

//All Popup FadeOut
$(document).ready(function () {
    $(".cancel,.popup_filter").on('click', function () {
        $(".popup_container").fadeOut(200);
        $('.discussion_new,.new_club_form,.club_global_popup,.confession_new,.new_lesson_notes').css('transform', 'scale(.9)');
    })
})

//Image Live Change
function readURL(input, target) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(target).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

//Close Comment Textarea
function closeCommentTextarea() {
    $(".comment .comment_button").addClass("comment_button_not_click").css('display', 'none');
    $(".comment form textarea").val('').css('height', '26px');
}


//------------------------------Mobile Left Menu------------------------------//
function mobileMenu() {
    $(".mobile_left_sidebar").animate({'left': '0px'}, 350);
    $(".mobile_left_sidebar_filter").animate({'opacity': '.7'}, 350);
    $(".mobile_left_sidebar_container").css('visibility', 'visible');
}

$(document).ready(function () {
    $(".mobile_left_sidebar_filter,.mobile_left_sidebar_top > i").on('click', () => {
        $(".mobile_left_sidebar").animate({'left': '-250px'}, 350);
        $(".mobile_left_sidebar_filter").animate({'opacity': '0'}, 350);
        setTimeout(() => {
            $(".mobile_left_sidebar_container").css('visibility', 'hidden');
        }, 350);
    });
})

//------------------------------Left Menu Selected------------------------------//
$(document).ready(() => {
    var link = window.location.pathname.split('/')[1];
    link == 'tartismalar' ? $(".page_left_menu ul a[id='tartismalar'] li").addClass("page_left_menu_active_button") : '';
    link == 'duyuru-haber' ? $(".page_left_menu ul a[id='duyuru-haber'] li").addClass("page_left_menu_active_button") : '';
    link == 'etkinlik' ? $(".page_left_menu ul a[id='etkinlik'] li").addClass("page_left_menu_active_button") : '';
    link == 'ders-notlari' ? $(".page_left_menu ul a[id='ders-notlari'] li").addClass("page_left_menu_active_button") : '';
    link == 'kulupler' ? $(".page_left_menu ul a[id='kulupler'] li").addClass("page_left_menu_active_button") : '';
    link == 'gruplar' ? $(".page_left_menu ul a[id='gruplar'] li").addClass("page_left_menu_active_button") : '';
    link == 'itiraflar' ? $(".page_left_menu ul a[id='itiraflar'] li").addClass("page_left_menu_active_button") : '';
});

//*********Global Pages End*********//

//------------------------------Main Page------------------------------//
//Post More Show
function textMoreShow(element) {
    $(element).css('display', 'none');
    $(element).parent().children('.more_text').css('display', 'inline');
}

//Create Button Manage
$(document).ready(function () {
    $(".main_page_post_create > .create_buttons > div").on('click', function () {
        var buttonClassName = $(this).attr('class');
        if (buttonClassName == 'discussion') {
            $('.discussion_new_popup')
                .css("display", "flex")
                .hide()
                .fadeIn(200);
            $('.discussion_new').css('transform', 'scale(1)');
        } else if (buttonClassName == 'announcement') {
            $('.global_popup_announcement_container')
                .css("display", "flex")
                .hide()
                .fadeIn(200);
            $('.global_popup_announcement').css('transform', 'scale(1)');
            announcementAndEventAuthorityCheck('#clubNewAnnouncementsAndNewsForm');
        } else if (buttonClassName == 'event') {
            $('.global_popup_event_container')
                .css("display", "flex")
                .hide()
                .fadeIn(200);
            $('.global_popup_event').css('transform', 'scale(1)');
            announcementAndEventAuthorityCheck('#clubEventsForm');
        } else if (buttonClassName == 'confession') {
            $('.confession_new_popup')
                .css("display", "flex")
                .hide()
                .fadeIn(200);
            $('.confession_new').css('transform', 'scale(1)');
            confessionAuthorityCheck();
        }
    });
});

//Club Authority Check
function announcementAndEventAuthorityCheck(form) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        data: {which: 'clubAuthorityCheck'},
        beforeSend: function () {
            Notiflix.Block.Dots('.club_global_popup');
        },
        success: function (response) {
            Notiflix.Block.Remove('.club_global_popup', 300);
            if (response.clubs.length > 0) {
                //Clubs List
                response.clubs.forEach(function (club) {
                    $(form).find('li select[name="club_name"]').html(`
                                <option value="0" selected>Bir kulüp seç</option>
                                <option value="${club.club_link}">${club.club_name}</option>`);
                })
            } else {
                //Popup Display None (If There Is No Club)
                $(form).parents(':eq(1)').children('.club_global_popup_not_authorization').css('display', 'flex');
            }
        }
    })
}

//Post Comment Disable/Enable
function commentButtonDisableEnable(element) {
    //Post Comment Textarea Length Control
    var button = $(element).parent().children('button');
    if ($(element).val().length > 0) {
        button.css('display', 'block');
    } else {
        button.css('display', 'none');
    }
}

//Main Page Create Text Style
$(document).ready(function () {
    $('.main_page > .main_page_inner_left > .main_page_post_create > .create_buttons > div').hover(function () {
        var buttonClassName = $(this).attr('class'), text, textClassName;
        if (buttonClassName == 'discussion') {
            text = 'Tartışma';
            textClassName = 'create_text_discussion';
        } else if (buttonClassName == 'announcement') {
            text = 'Duyuru-Haber';
            textClassName = 'create_text_announcement';
        } else if (buttonClassName == 'event') {
            text = 'Etkinlik';
            textClassName = 'create_text_event';
        } else if (buttonClassName == 'confession') {
            text = 'İtiraf';
            textClassName = 'create_text_confession';
        }
        //Change Class,Change Text
        $('.main_page > .main_page_inner_left > .main_page_post_create > .create_text > .text > span').text(text)
            .removeClass()
            .addClass(textClassName);
    });
})

//Comment Button Click
function commentButton(element, type, postType) {
    //Get Comment Id
    var dataId = $(element).parents(':eq(2)').attr('data-id'), lastId = 0, commentList, commentContainer,
        commentControl;

    if (type == 'firstGet') {
        commentContainer = $(element).parents(':eq(1)').children('.comment_container');
        commentControl = $(element).parents(':eq(1)').attr('data-comment');
        commentList = $(element).parents(':eq(1)').find('.comment_container .comment_list');

        //If Pulled Before
        if (commentContainer.css('display') == 'block') {
            return 0;
        }
        commentContainer.css('display', 'block');

        //Check Confession User
        if (postType == 'confession') {
            var confessionsUser = $("meta[name='checkConfessionUser']").attr('content');

            if (confessionsUser == 'true') {
                var image = $("meta[name='confessionUserImage']").attr('content');
                var confessionUserImage = image === '0' ? 'user_default.png' : image;
                commentContainer.children('.my_comment').children('img').attr('src', '../img/confessions/' + confessionUserImage);
                commentContainer.children('.my_comment').append(`<div style="margin-top: 7px;" class="tooltip">
                                            <i style="margin-left: 3px;color: #929292;" class="fas fa-info-circle"></i>
                                            <div class="tooltip_text confession_comment_warning_mobile_tooltip_text">Anonim hesabınla yorum yapıyorsun.</div>
                                        </div>`);
            } else {
                commentContainer.children('.my_comment').html(`<div class="warning_confession_user">
                        Yorum yapabilmek için anonim kişi oluşturmalısınız.
                        <span onclick="showConfessionPopup()">Şimdi Oluştur</span>
                        </div>`);
            }

        }
    } else if (type == 'moreGet') {
        commentControl = true;
        commentList = $(element).parent().children('.comment_list');
        lastId = $(element).parent().find('.comment_list li:last-child').attr('data-id');
    }

    if (commentControl || commentControl === 'true') {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            url: '/',
            type: 'POST',
            data: {
                which: 'moreComment',
                dataId: dataId,
                lastId: lastId
            },
            success: function (response) {
                //If There Are Is An Error
                if (response.status == '0') {
                    Notiflix.Notify.Failure(response.message);
                    return 0;
                }

                //Comments Length Control
                if (response.comments.length != '0') {
                    response.comments.forEach(function ($e) {
                        commentList.append(commentSet(
                            $e.id,
                            $e.user.username,
                            $e.user.image,
                            $e.created_at,
                            $e.message,
                            postType
                        ));
                    });
                    commentList.parent().children('.more_comments').css('display', 'inline-block');
                }

                //If there are not more comments
                if (response.comments.length < 5 || response.comments.length == '0') {
                    commentList.parent().children('.more_comments').css('display', 'none');
                    if (type == 'moreGet') {
                        commentList.parent().children('.not_more_comments').css('display', 'flex');
                    }
                }
            }
        })
    }
}

//Comment Set
function commentSet(id, username, image, date, comment, postType) {
    var userImage;
    if (postType == 'confession') {
        userImage = image == '0' ? 'user_default.png' : image;

        return `<li data-id="${id}">
                          <a><img src="../img/confessions/${userImage}">
                                            </a>
                                            <div class="comment_inner">
                                                <span class="username">
                                                    <a>
                                                    ${returnText(username)}
                                                    </a>
                                                </span>
                                                <span class="date">
                                                    ${date}
                                                </span>
                                                <div class="text">
                                                    ${returnText(comment)}
                                                </div>
                                            </div>
                                        </li>`
    } else {
        userImage = image == '0' ? 'default.png' : image;

        return `<li data-id="${id}">
                          <a href="/profil/${returnText(username)}">
                                                <img src="../img/user/${userImage}">
                                            </a>
                                            <div class="comment_inner">
                                                <span class="username">
                                                    <a href="/profil/${returnText(username)}">
                                                    ${returnText(username)}
                                                    </a>
                                                </span>
                                                <span class="date">
                                                    ${date}
                                                </span>
                                                <div class="text">
                                                    ${returnText(comment)}
                                                </div>
                                            </div>
                                        </li>`
    }


}

//Create Text Class And Text
$(document).ready(function () {
    $('.main_page > .main_page_inner_left > .main_page_post_create').on('mouseleave', function () {
        $(this).find('.create_text .text span')
            .text('şey')
            .removeClass();
    })
})

//Post Comment
var response;

async function postCommentSend(element, postType) {
    var postLi = $(element).parents(':eq(4)');
    var dataId = postLi.attr('data-id');
    var textarea = $(element).parent().children('textarea');
    var link = postLi.attr('data-link');
    var postId = postLi.attr('data-postid');
    var commentList = $(element).parents(':eq(2)').children('.comment_list');

    //Post Type
    var response;
    if (postType == 'discussion') {
        response = await postDiscussionCommentSend(link, textarea.val());
    } else if (postType == 'announcement') {
        response = await postAnnouncementCommentSend(link, textarea.val());
    } else if (postType == 'event') {
        response = await postEventCommentSend(link, textarea.val());
    } else if (postType == 'confession') {
        response = await postConfessionCommentSend(postId, textarea.val());
    }

    //If Failed
    if (response.status == '0') {
        Notiflix.Notify.Failure(response.message);
        return 0;
    }

    //Comment List Set
    if (postType != 'confession') {
        commentList.prepend(postCommentSet(textarea.val()));
    } else {
        commentList.prepend(confessionPostCommentSet(textarea.val()));
    }

    //Textarea Clear and Define Height
    textarea.val('').css('height', '30px');

    //Button Disable
    textarea.parent().children('button').css('display', 'none');

    //New Comment Animate
    $(".post_new_comment").animate({
        opacity: 1,
        marginLeft: '0px'
    }, 300);

}

//Post Discussion Comment Send
async function postDiscussionCommentSend(link, message) {
    response = await $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/tartismalar/' + link,
        data: {
            which: 'discussionNewComments',
            comment: message,
        },
        dataType: 'json',
        success: function (response) {
            return response;
        }
    });
    return response;
}

//Post Announcement Comment Send
async function postAnnouncementCommentSend(link, message) {
    response = await $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/duyuru-haber/' + link,
        data: {
            message: message,
        },
        success: function (response) {
            return response;
        }
    });
    return response;
}

//Post Event Comment Send
async function postEventCommentSend(link, message) {
    response = await $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/etkinlik/' + link,
        data: {
            message: message
        },
        success: function (response) {
            return response;
        }
    });
    return response;
}

//Post Confession Comment Send
async function postConfessionCommentSend(postId, message) {
    response = await $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/itiraflar',
        data: {
            which: 'commentPost',
            comment: message,
            confession_id: postId
        },
        beforeSend: function () {
        },
        success: function (response) {
            return response;
        }
    });
    return response;
}

//Post Comment Set
function postCommentSet(message) {
    return `
           <li class="post_new_comment">
            <a href="/profil/${profileUsernameGet()}">
              <img src="${profileImageGet()}">
            </a>
            <div class="comment_inner">
               <span class="username">
                 <a href="/profil/${returnText(profileUsernameGet())}">
                   ${returnText(profileUsernameGet())}
                 </a>
               </span>
               <span class="date">
                 az önce
               </span>
               <div class="text">
                 ${returnText(message)}
               </div>
            </div>
        </li>`;
}

function confessionPostCommentSet(message) {
    var image = $("meta[name='confessionUserImage']").attr('content') == '0' ? 'user_default.png' : $("meta[name='confessionUserImage']").attr('content');
    var username = $("meta[name='confessionUserUsername']").attr('content');
    return `<li class="post_new_comment">
            <a>
              <img src="../img/confessions/${image}">
            </a>
            <div class="comment_inner">
               <span class="username">
                   ${username}
               </span>
               <span class="date">
                 az önce
               </span>
               <div class="text">
                 ${returnText(message)}
               </div>
            </div>
        </li>`;
}

//Post Share Button Show
function postShareDisplay(element) {
    $(element).parent().children('.post_share_menu').fadeToggle(200);
}

//Post Share Copy Link Button
function postShareLinkCopy(element, which) {
    var link = $(element).parents(':eq(4)').attr('data-link');
    var postLink = window.location.href;

    //Post Type
    if (which == 'discussion') {
        postLink += 'tartismalar/' + link;
    } else if (which == 'announcement') {
        postLink += 'duyuru-haber/' + link;
    } else if (which == 'event') {
        postLink += 'etkinlik/' + link;
    } else if (which == 'confession') {
        postLink += 'itiraflar/' + link;
    }

    //Copy Successful
    $(element).html('<i class="fas fa-check" style="padding-right: 3px;"></i>Bağlantı Kopyalandı!').css('color', '#1da01d');

    //Copy Text
    var textElement = document.createElement('TEXTAREA');
    textElement.value = postLink;
    $(element).append(textElement);
    textElement.select();
    document.execCommand('copy');
    textElement.style.display = 'none';
}

//Redirect WP Link
function redirectWpLink(element, which) {
    var link = $(element).parents(':eq(5)').attr('data-link');
    var postLink = window.location.href;

    //Post Type
    if (which == 'discussion') {
        postLink += 'tartismalar/' + link;
    } else if (which == 'announcement') {
        postLink += 'duyuru-haber/' + link;
    } else if (which == 'event') {
        postLink += 'etkinlik/' + link;
    } else if (which == 'confession') {
        postLink += 'itiraflar/' + link;
    }
    window.location.replace("whatsapp://send?text=" + postLink);
}

//Share Button Disable
$(document).ready(function () {
    $("body").click(function (e) {
        if (!$(e.target).is(".post_share_menu,.inner")) {
            $(".post_share_menu").fadeOut(150);
        }
    })
});

window.onload = function () {
    if (window.location.pathname === '/') {
        morePostGet('0');
    }
}

//Get More Post
function morePostGet(lastId) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/',
        data: {
            which: 'morePost',
            lastId: lastId
        },
        beforeSend: function () {
            $(".wrapper").css('display', 'block');
        },
        success: function (response) {
            if (response.status == 0) {
                Notiflix.Notify.Failure(response.message);
                return 0;
            }
            var postList = $(".main_page_inner_left > .main_page_post_list");
            //Set Posts List
            response.posts.forEach(function ($e) {
                var dataId = ' data-id=\"' + $e.id + '\"';
                var dataLink = ' data-link=\"' + $e.post.link + '\"';
                var dataPostId = ' data-postid=\"' + $e.post.id + '\"';
                //Posts Type
                if ($e.post_type == 'discussion') {
                    postList.append('<li' + dataId + dataLink + '>' +
                        postTopGet(
                            'tartışma',
                            'discussion_ticket',
                            $e.post.user.username,
                            $e.post.user.image,
                            $e.post.user.school_email_confirmation,
                            $e.created_at
                        ) +
                        postMiddleDiscussionAndConfessionGet(
                            'discussion',
                            $e.post.title,
                            $e.post.subject
                        ) +
                        postBottomGet(
                            $e.post.comments_count,
                            $e.post_type
                        ) +
                        '</li>'
                    )
                } else if ($e.post_type == 'event') {
                    postList.append('<li ' + dataId + dataLink + '>' +
                        postTopGet(
                            'etkinlik',
                            'event_ticket',
                            $e.post.user.username,
                            $e.post.user.image,
                            $e.post.user.school_email_confirmation,
                            $e.created_at
                        ) +
                        postMiddleAnnouncementAndEventGet(
                            'event',
                            $e.post.link,
                            $e.post.title,
                            $e.post.title_image,
                            [$e.post.date, $e.post.club.club_name, $e.post.location]
                        ) +
                        postBottomGet(
                            $e.post.comments_count,
                            $e.post_type
                        ) +
                        '</li>');
                } else if ($e.post_type == 'announcement') {
                    postList.append('<li' + dataId + dataLink + '>' +
                        postTopGet(
                            'duyuru-haber',
                            'announcement_ticket',
                            $e.post.user.username,
                            $e.post.user.image,
                            $e.post.user.school_email_confirmation,
                            $e.created_at,
                        ) +
                        postMiddleAnnouncementAndEventGet(
                            'announcement',
                            $e.post.link,
                            $e.post.title,
                            $e.post.title_image,
                        ) +
                        postBottomGet(
                            $e.post.comments_count,
                            $e.post_type
                        ) +
                        '</li>');
                } else if ($e.post_type == 'confession') {
                    postList.append('<li' + dataId + dataPostId + '>' +
                        postTopGet(
                            'itiraf',
                            'confession_ticket',
                            $e.post.users.username,
                            $e.post.users.image,
                            '0',
                            $e.created_at
                        ) +
                        postMiddleDiscussionAndConfessionGet(
                            'confession',
                            '',
                            $e.post.confession_content
                        ) +
                        postBottomGet(
                            $e.post.comments_count,
                            $e.post_type
                        ) +
                        '</li>'
                    )
                }
            })

            if (!(response.userEmailConfirmation)) {
                $(".main_page_post_list > li > .bottom > .comment_container > .my_comment")
                    .css({
                        'opacity': '0.6',
                        'pointer-events': 'none'
                    })
            }

            //Infinite Scroll
            scrollPostLoading = true;

            //Loading Div None
            $(".wrapper").css('display', 'none');
        }
    })
}

//Post Top Div
function postTopGet(type, classType, username, userImage, schoolEmailConfirmation, date) {
    var image = userImage == '0' ? 'default.png' : userImage;
    var confessionImage = userImage == '0' ? 'user_default.png' : userImage;
    var moreDropdown = '';
    var verification = '';

    if (schoolEmailConfirmation == '1') {
        verification = `<div style="top: 2px;margin-right: 10px;" class="tooltip">
                                <i class="verification"></i>
                                <div class="tooltip_text verification_tooltip_text">
                                    Okul e-postası onaylanmış.
                                </div>
                            </div>`;
    }

    if (authCheck) {
        moreDropdown = `<div class="more">
                                    <i class="fas fa-ellipsis-h"></i>
                                    <ul class="more_dropdown">
                                        <li onclick="postReport(this)">
                                           <i style="color: #d92222;padding-right:3px;" class="fas fa-exclamation-circle"></i>
                                            Bildir
                                        </li>
                                    </ul>
                                </div>`;
    }

    var imageLink, usernameLink;
    if (type == 'itiraf') {
        imageLink = ` <a><img src="../img/confessions/${confessionImage}"></a>`;

        usernameLink = `<a><div class="username">${returnText(username)}</div></a>`;
    } else {
        imageLink = ` <a href="/profil/${returnText(username)}">
                                    <img src="../img/user/${image == '0' ? 'default.png' : image}">
                                </a>`;

        usernameLink = `<a href="/profil/${returnText(username)}">
                                        <div class="username">
                                           ${returnText(username)}
                                           ${verification}
                                        </div>
                                    </a>`;
    }


    return `<div class="top">
                                  ${imageLink}
                                <div class="information">
                                     ${usernameLink}
                                    <div class="date">
                                       ${date}
                                    </div>
                                </div>
                                <div class="ticket ${classType}">
                                    ${returnText(type)}
                                </div>
                                 ${moreDropdown}
                            </div>`
}

//Post Discussion And Confession Middle Dic
function postMiddleDiscussionAndConfessionGet(type, title, subject) {
    var text = subject, moreText = '', moreTextDiv = '';

    //More Get Button
    if (subject.length > 100) {
        text = subject.substr(0, 195);
        moreText = subject.substr(195);
        moreTextDiv = `<span onclick="textMoreShow(this)" class="more_button">
                            daha fazla göster
                            </span>
                             <span  class="more_text">
                              ${returnText(moreText)}
                             </span>`;
    }

    return ` <div class="middle">
                                <div class="discussion_and_confession">
                                    <div class="title">
                                        ${returnText(title)}
                                    </div>
                                    <div class="subject">
                                      ${returnText(text)}
                                      ${moreTextDiv}
                                    </div>
                                </div>
                            </div>`
}

//Post Announcement And Event Middle Div
function postMiddleAnnouncementAndEventGet(type, link, title, image, otherInfo) {
    var fullLink, fullImage, eventInfo;

    //Post Type
    if (type == 'event') {
        fullLink = '/etkinlik/' + link;
        fullImage = image == '0' ? 'events/events_default.png' : 'events/' + image;
        var date = new Date(returnText(otherInfo[0])).toLocaleString();
        eventInfo = `<div class="information">
                                                <div>
                                                    <i class="far fa-clock"></i>
                                                    ${date}
                                                </div>
                                                <div>
                                                    <i class="fas fa-users"></i>
                                                    ${returnText(otherInfo[1])}
                                                </div>
                                                <div>
                                                    <i class="fas fa-map-marker"></i>
                                                    ${returnText(otherInfo[2])}
                                                </div>
                                            </div>`;
    } else {
        fullLink = '/duyuru-haber/' + link;
        fullImage = image == '0' ? 'announcements_and_news/announce_and_news_default.png' : 'announcements_and_news/' + image;
        eventInfo = '';
    }

    return ` <div class="middle">
                                <div class="news">
                                    <a href="${returnText(fullLink)}">
                                        <div class="title">
                                            ${returnText(title)}
                                        </div>
                                        <div class="image">
                                            <img src="../img/${returnText(fullImage)}">
                                            ${eventInfo}
                                        </div>
                                    </a>
                                </div>
                            </div>`
}

//Post Bottom Div
function postBottomGet(commentCount, postType) {
    var actionButtons = '', buttonDisable = '';
    var commentCountStyle = `onclick = "commentButton(this,\'firstGet\','${postType}')"`;

    if (!registerEmailCheck) {
        buttonDisable = 'action_buttons_not_click';
    }

    if (authCheck) {
        var shareButton;
        if (postType == 'confession') {
            shareButton = '';
        } else {
            shareButton = `<div><div class="inner" onclick="postShareDisplay(this)">
                                            <i class="fas fa-share"></i>
                                            Paylaş
                                        </div>
                                        <ul class="post_share_menu">
                                            <li onclick="postShareLinkCopy(this,\'${returnText(postType)}'\)">
                                                <i class="fas fa-clone"></i>
                                                Bağlantıyı Kopyala
                                            </li>
                                            <li>
                                            <div onclick="redirectWpLink(this, \'${returnText(postType)}'\)">
                                            <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<lottie-player src="https://assets9.lottiefiles.com/packages/lf20_dyf5lscb.json"  background="transparent"  speed="1"  style="width: 20px; height: 20px;"  loop  autoplay></lottie-player>
                                            <span>
                                            WhatsApp'ta Paylaş
                                            </span>
                                             </div>
                                             </li>
                                       </ul>
                                    </div>`;
        }

        actionButtons = `<div class="action_buttons">
                                 <div class="${buttonDisable}" onclick="commentButton(this,'firstGet','${postType}')">
                                        <div class="inner">
                                            <i class="fas fa-comment"></i>
                                            Yorum Yap
                                        </div>
                                    </div>
                                    ${shareButton}
                              </div>
                                 <div class="comment_container">
                                    <div class="my_comment">
                                        <img src="${profileImageGet()}">
                                        <div class="comment_inner">
                                            <textarea onkeydown="textareaHeight($(this),200)"
                                                      onkeyup="commentButtonDisableEnable(this)"
                                                      placeholder="Bir şey yaz..."></textarea>
                                            <button onclick="postCommentSend(this,'${postType}')">Yorum Yap</button>
                                        </div>
                                    </div>
                                    <ul class="comment_list">

                                    </ul>
                                    <div onclick="commentButton(this,'moreGet','${postType}')" class="more_comments">daha fazla yorum</div>
                                    <div class="not_more_comments">
                                   <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<lottie-player src="https://assets9.lottiefiles.com/packages/lf20_fyqtse3p.json"  background="transparent"  speed="1.4"  style="width: 20px; height: 20px;"  loop  autoplay></lottie-player>
                                    <span>başka yorum yok</span>
                                    </div>
                                </div>`
    }

    if (!authCheck || commentCount == '0') {
        commentCountStyle = 'style="text-decoration: none;cursor:default;padding-right:4px;padding-bottom:3px;"';
    }

    return `<div data-comment="${commentCount != '0'}" class="bottom">
                                <div class="information">
                                    <div class="comment_count" ${commentCountStyle}>
                                        ${commentCount == '0' ? 'Hiç yorum yok' : commentCount + ' yorum'}
                                    </div>
                                </div>
                               ${actionButtons}
                            </div>`
}

//Post Report
function postReport(element) {
    const postId = $(element).parents(':eq(3)').attr('data-id');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/',
        data: {
            which: 'reportPost',
            postId: postId
        },
        success: function (response) {
            if (response.status == '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                Notiflix.Report.Success(
                    'Başarılı!',
                    'Bildirme işlemi bize ulaştı! Uygunsuz içerik görüldüğü takdirde paylaşım kaldırılır.',
                    'tamam'
                );
            }
        }
    })
}

//Get Profile Image
function profileImageGet() {
    return $(".page_header_user > .information > img").attr('src');
}

//Get Profile Username
function profileUsernameGet() {
    return $(".page_header_user > .information > .username").text().trim();
}

//Infinite Scroll
var scrollPostLoading = false;
$(document).ready(function () {
    $(window).scroll(function () {
        var scrollHeight = $(this).scrollTop();
        var bodyHeight = $(document).height() - $(this).height();
        var lastDataId = $(".main_page_inner_left > .main_page_post_list > li:last-child").attr('data-id');

        if (scrollPostLoading) {
            if (scrollHeight >= bodyHeight - 100) {
                scrollPostLoading = false;
                morePostGet(lastDataId);
            }
        }
    });
});

//Show Confession Settings Profile
function anonymousSettingsShow(which) {
    var profile = $(".confession_new > ul > .anonymous_profile");
    var settings = $(".confession_new > ul > .anonymous_settings");
    if (which) {
        profile.css('display', 'none');
        settings.css('display', 'block');
    } else {
        profile.css('display', 'flex');
        settings.css('display', 'none');
    }
}

//Selected Confession User Settings Image
$(document).ready(function () {
    $(".anonymous_settings > form > .content > .image_container > input[id='anonymous_image']").change(function () {
        $(this).parent().children("label[for='anonymous_image']").css('color', '#21ca48').text('Seçildi');

        //Image
        readURL(this, $(this).parent().children("img"));
    })
})

//------------------------------Register Page------------------------------//

//Register Post
function registerSend() {
    $.ajax({
        type: 'POST',
        url: '/kayit-ol',
        data: $("#register_form").serialize(),
        dataType: 'json',
        beforeSend: function () {
            $("#NotiflixLoadingWrap").css("display", "block");
            Notiflix.Loading.Dots('Kayıt Ediliyor',);
        },
        success: function (response) {
            $("#NotiflixLoadingWrap").css("display", "none");
            if (response.status === '0') {
                Notiflix.Notify.Warning(response.message,
                    {
                        timeout: 4000,
                        useIcon: true,
                        fontFamily: "Quicksand",
                        useGoogleFont: true,
                        cssAnimation: true,
                    });
            } else if (response.status === '1') {
                window.location.href = response.redirectTo;
            }
        }
    })

}


//Register İnput Check
function ajaxCheck(input, which) {


    if (input.value.length < 3) {
        if (which === 'usernameCheck') {
            $("#username_warning").text(' ');
        } else {
            $("#register_email_warning").text(' ');
        }
    } else {
        functionDelay(function () {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                type: 'POST',
                url: '/kayit-ol',
                data: {which: which, value: input.value},
                dataType: 'json',
                success: function (response) {
                    if (response.which === 'usernameCheck') {
                        if (response.status === '0') {
                            $("#username_warning").removeClass("input_warning_success");
                            $("#username_warning").addClass("input_warning_error");
                            $("#username_warning").text("'" + input.value + "' " + "daha önce alınmış");
                        } else {
                            $("#username_warning").removeClass("input_warning_error");
                            $("#username_warning").addClass("input_warning_success");
                            $("#username_warning").text("'" + input.value + "' " + "uygun");
                        }
                    } else if (response.which === 'registerEmailCheck') {
                        if (response.status === '0') {
                            $("#register_email_warning").removeClass("input_warning_success");
                            $("#register_email_warning").addClass("input_warning_error");
                            $("#register_email_warning").text("Daha önce kullanılmış.");
                        } else {
                            $("#register_email_warning").removeClass("input_warning_error");
                            $("#register_email_warning").addClass("input_warning_success");
                            $("#register_email_warning").text("Daha önce kullanılmamış.");
                        }
                    }
                }
            })
        }, 500);

    }
}

//------------------------------Sign in Page------------------------------//
//Sign in Post
function sign() {
    $.ajax({
        type: 'POST',
        url: '/giris-yap',
        data: $("#signForm").serialize(),
        dataType: 'json',
        beforeSend: function () {
            $("#NotiflixLoadingWrap").css("display", "block");
            Notiflix.Loading.Dots('Giriş Yapılıyor',);
        },
        success: function (response) {
            $("#NotiflixLoadingWrap").css("display", "none");
            if (response.status === '0') {
                Notiflix.Notify.Warning(response.message,
                    {
                        timeout: 4000,
                        useIcon: true,
                        fontFamily: "Quicksand",
                        useGoogleFont: true,
                        cssAnimation: true,
                    });
            } else {
                window.location.href = response.redirectTo;
            }

        }
    });
}

$(document).ready(function () {
    $("#signForm input[name='username_or_email'],#signForm input[name='password']").on('keyup', function (e) {
        if (e.key === 'Enter') {
            sign();
        }
    })
})

//------------------------------Password Change Page------------------------------//

//User Information Post passwordPage
function changePasswordPost() {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/sifre-degistir',
        data: {data: $("input[id='username_and_email']").val()},
        beforeSend: function () {
            Notiflix.Block.Dots('.register_content');
        },
        success: function (response) {
            Notiflix.Block.Remove('.register_content', 200);
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                $(".register_content").remove();
                $(".page_content_not_left_menu .password_change_code_post_success").css('display', 'flex');
                $(".page_content_not_left_menu .password_change_code_post_success > .text > span").html(response.message);
            }
        }
    });
}

//New Password passwordInPage
function newPasswordPost() {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: location.pathname,
        data: $("#newPasswordForm").serialize(),
        beforeSend: function () {
            Notiflix.Block.Dots('.register_content');
        },
        success: function (response) {
            Notiflix.Block.Remove('.register_content');
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                location.href = response.redirect;
            }
        }
    });
}

//------------------------------pageLinksSend------------------------------//
function pageLinksSend(which, csrf_token) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrf_token},
        type: 'POST',
        url: '/globalLinks',
        data: {'which': which},
        dataType: 'json',
        beforeSend: function () {
            $("#NotiflixLoadingWrap").css("display", "block");
            Notiflix.Loading.Pulse('E-mail tekrar gönderiliyor.');
        },
        success: function (response) {
            $("#NotiflixLoadingWrap").css("display", "none");
            if (response.status === '1') {
                Notiflix.Notify.Success('E-mail tekrar gönderildi.');
            } else {
                Notiflix.Notify.Failure('E-mail gönderirken bir hata oluştu.');
            }
        }
    });
}

//------------------------------/tartismalar Page------------------------------//
//New Discussion Button
//New Discussion Popup Enable
$(document).ready(function () {
    $(".new_discussion_button,.new_discussion_button_mobile").on('click', function () {
        $('.discussion_new_popup')
            .css("display", "flex")
            .hide()
            .fadeIn(200);
        $('.discussion_new').css('transform', 'scale(1)');
    });
});

//New Discussion Button Disable/Enable
$(document).ready(function () {
    var root = $('.discussion_new > ul > li > form');
    root.children('textarea').on('keyup', function () {
        var titleLenght = root.children('textarea[name="title"]').val().length;
        var subjectLenght = root.children('textarea[name="subject"]').val().length;
        if (titleLenght <= 4 || subjectLenght < 4) {
            root.children('button').addClass('button_not_click');
        } else {
            root.children('button').removeClass('button_not_click');
        }
    })
});

//New Discussion Post
function discussionSend() {
    $.ajax({
        type: 'POST',
        url: '/tartismalar',
        data: $("#discussionForm").serialize(),
        dataType: 'json',
        beforeSend: function () {
            Notiflix.Block.Dots('.discussion_new');
        },
        success: function (response) {
            Notiflix.Block.Remove('.discussion_new', 200);
            if (response.status == '0') {
                Notiflix.Notify.Warning(response.message);
            } else {
                location.reload();
            }
        }
    })
}

//Discussion ul > li Link Function
function discussionLink(link) {
    window.location.href = '/tartismalar/' + link;
}

//------------------------------/tartismalar/{slug} Page------------------------------//
//Discussion Edit Div
function discussionEdit() {
    $("#firstLi").css('display', 'none');
    $("#editLi").css('display', 'block');

    $(".discussion_page_in_edit input[name='title']").val($("#title").text().trim());
    $(".discussion_page_in_edit textarea[name='subject']").text($("#subject").text().trim());
}

//Discussion Edit Post
function editSave() {
    var link = window.location.pathname.split('/')[2];
    $.ajax({
        type: 'POST',
        url: '/tartismalar/' + link,
        data: $("#discussionEditForm").serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status == '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                window.location.replace('/tartismalar/' + response.link);
            }
        }
    });
}

//Discussion Message Add Post  (discussionMessageLi Sayfa İçerisinde)
function discussionMessageAdd() {
    var link = window.location.pathname.split('/')[2];
    $.ajax({
        type: 'POST',
        url: '/tartismalar/' + link,
        data: $("#newComments").serialize(),
        dataType: 'json',
        success: function (response) {
            Notiflix.Block.Remove('.comment', 200);
            if (response.status == '0') {
            } else {
                $(discussionMessageLi()).insertAfter(".comment");
                closeCommentTextarea();

                $(".discussion_in_page_ul > .new_comment_li")
                    .css('display', 'flex')
                    .hide()
                    .fadeIn(300);
                setTimeout(function () {
                    $(".discussion_in_page_ul > .comment_li").removeClass('new_comment_li');
                }, 1000);
            }
        }
    });
}

var count = 0;

function increaseDiscussionComment(element, commentId) {
    var countElement = $(element).parent().children('span');
    var hasCount = parseInt(countElement.text());
    countElement.text(hasCount + 1);
    count++;
    functionDelay(function () {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
            type: 'POST',
            url: window.location.pathname,
            data: {
                which: 'increaseDiscussionComment',
                commentId: commentId,
                count: count
            },
            success: function (response) {
                if (response.status == '0') {
                    Notiflix.Notify.Failure(response.message);
                }
            }
        });
        count = 0;
    }, 400);


}

//Discussion Comment Open
//Sayfa İçerisinde

//------------------------------/duyuru-haber  Page------------------------------//
//Boş

//------------------------------/duyuru-haber/{slug}  Page------------------------------//
function announcementsNewCommentAdd() {
    var link = window.location.pathname.split('/')[2];
    $.ajax({
        type: 'POST',
        url: '/duyuru-haber/' + link,
        data: $("#announcementsNewComment").serialize(),
        success: function (response) {
            Notiflix.Block.Remove('.comment', 150);
            if (response.status == '1') {
                $(".global_events_comments_ul").prepend(globalEventsComment());
                $(".global_events_comments_ul > li:first-child")
                    .css('display', 'flex')
                    .hide()
                    .fadeIn(300);
                closeCommentTextarea();
            } else {
                Notiflix.Notify.Failure(response.message);
            }
        }
    });
}

//globalEventsComment() sayfa içinde.
//İmage Popup Sayfa İçinde.

//------------------------------/etkinlik/{slug}  Page------------------------------//


//------------------------------/etkinlik/{slug}  Page------------------------------//
function eventsNewCommentAdd() {
    var link = window.location.pathname.split('/')[2];
    $.ajax({
        type: 'POST',
        url: '/etkinlik/' + link,
        data: $("#eventsNewComment").serialize(),
        success: function (response) {
            if (response.status == '1') {
                $(".global_events_comments_ul").prepend(globalEventsComment());
                $(".global_events_comments_ul > li:first-child")
                    .css('display', 'flex')
                    .hide()
                    .fadeIn(200);
                closeCommentTextarea();
            } else {
                Notiflix.Notify.Failure(response.message);
            }
        }
    });
}

//------------------------------/ayarlar Left Menu Page------------------------------//
$(document).ready(() => {
    var link = window.location.pathname.split('/')[2];
    link == 'hesap' ? $(".settings_left ul a li[id='hesap']").addClass("settings_left_li_active") : '';
    link == 'sifre' ? $(".settings_left ul a li[id='sifre']").addClass("settings_left_li_active") : '';
    link == 'bildirimler' ? $(".settings_left ul a li[id='bildirimler']").addClass("settings_left_li_active") : '';
});

//------------------------------/ayarlar/hesap Page------------------------------//
//profile image upload
$(document).ready(function () {
    $("#image").change(function () {
        readURL(this, '.account_image');
    });
});

function accountPost(which) {
    $("#accountForm input[name='which']").val(which);
    $.ajax({
        type: 'POST',
        url: '/ayarlar/hesap',
        data: new FormData($("#accountForm")[0]),
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function () {
            if (which == 'email') {
                Notiflix.Loading.Pulse('E-mail Gönderiliyor');
            } else if (which == 'accountSave') {
                Notiflix.Block.Dots('.account_settings', 'Ayarlar Kaydediliyor');
            }
        },
        success: function (response) {
            $("#NotiflixLoadingWrap").css("display", "none");
            Notiflix.Block.Remove('.account_settings', 200);
            if (response.status == '0') {
                Notiflix.Notify.Warning(response.message);
            } else {
                if (response.school_email_confirm_button === true) {
                    $(".input_mail_div .school_email_warning").css('color', '#eebe29').html(`Okul e-postan  onaylanmamış. <span onclick="accountPost('email')">Onayla</span>`)
                }
                Notiflix.Notify.Success(response.message);
            }
        }
    })
}

//------------------------------/ayarlar/sifre Page------------------------------//
function changePassword() {
    $.ajax({
        type: 'POST',
        url: '/ayarlar/sifre',
        data: $("#passwordChange").serialize(),
        beforeSend: function () {
            Notiflix.Block.Dots('.password_settings', 'Şifre Değiştiriliyor');
        },
        success: function (response) {
            Notiflix.Block.Remove('.password_settings', 200);
            if (response.status == '0') {
                Notiflix.Notify.Warning(response.message);
            } else {
                Notiflix.Notify.Success(response.message);
                $(".password_settings input").val('');
            }
        }
    });
}

//------------------------------/ayarlar/bildirimler Page------------------------------//
function notificationsPost() {
    $.ajax({
        type: 'POST',
        url: '/ayarlar/bildirimler',
        data: $("#notificationsForm").serialize(),
        beforeSend: function () {
            Notiflix.Block.Dots('.settings_contain', 'Bildirimler Kaydediliyor');
        },
        success: function (response) {
            Notiflix.Block.Remove('.settings_contain', 200);
            if (response.status == '0') {
                Notiflix.Notify.Warning(response.message);
            } else {
                Notiflix.Notify.Success(response.message);
            }
        }
    });
}

//------------------------------/ders-notlari Page------------------------------//
// Show New Lesson Notes Popup
function showNewLessonNotesPopup() {
    $('.lesson_notes_new_popup')
        .css("display", "flex")
        .hide()
        .fadeIn(200);
    $('.new_lesson_notes').css('transform', 'scale(1)');
}

// Selected Lesson Notes File
$(document).ready(function () {
    $(".new_lesson_notes form li input[type='file'] ").change(function () {
        $(".new_lesson_notes form li label").addClass("new_lesson_notes_file_selected")
            .html('Dosya Seçildi <i class="far fa-check-circle"></i>');
    })
})

//Post New Lesson Notes
function postNewLessonNotesPopup() {
    $.ajax({
        type: 'POST',
        url: '/ders-notlari',
        data: new FormData($("#newLessonNote")[0]),
        processData: false,
        contentType: false,
        beforeSend: function () {
            Notiflix.Block.Dots('.new_lesson_notes');
        },
        success: function (response) {
            Notiflix.Block.Remove('.new_lesson_notes');
            if (response.status == '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                Notiflix.Report.Success(
                    'Notlar Başarıyla Gönderildi!',
                    'Gönderilen notlar en geç 24 içerisinde kontrol edilir.',
                    'Tamam',
                    function () {
                        location.reload();
                    });
            }
        },
    });
}


//------------------------------/kuluplerim Page------------------------------//
function myClubExit(clubLink) {
    Notiflix.Confirm.Show('Uyarı', 'Kulüpten çıkmak istediğine emin misin?', 'Evet', 'Hayır',
        function () {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: '/kulupler/' + clubLink,
                data: {which: 'userClubExist'},
                beforeSend: function () {
                    Notiflix.Loading.Dots();
                },
                success: function (response) {
                    $("#NotiflixLoadingWrap").remove();
                    if (response.status === '0') {
                        Notiflix.Notify.Failure(response.message);
                    } else {
                        window.location.pathname = '/';
                    }
                }
            })
        }
    );
}

//------------------------------ Club Global ------------------------------//
//Header
//Club Exit
function clubExit() {
    var link = window.location.pathname.split('/')[2];
    Notiflix.Confirm.Show('Uyarı', 'Kulüpten çıkmak istediğine emin misin?', 'Evet', 'Hayır',
        function () {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: '/kulupler/' + link,
                data: {which: 'userClubExist'},
                beforeSend: function () {
                    Notiflix.Loading.Dots();
                },
                success: function (response) {
                    $("#NotiflixLoadingWrap").remove();
                    if (response.status === '0') {
                        Notiflix.Notify.Failure(response.message);
                    } else {
                        window.location.pathname = '/kulupler';
                    }
                }
            })
        }
    );
}

//Club Join Post
function clubJoinPost() {
    var link = window.location.pathname.split('/')[2];
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/kulupler/' + link,
        data: {which: 'userClubJoin'},
        beforeSend: function () {
            Notiflix.Loading.Dots();
        },
        success: function (response) {
            $("#NotiflixLoadingWrap").remove();
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                Notiflix.Notify.Success('Kulübe istek atıldı.');
                $(".left_menu_container > .user_action > div").css({
                    backgroundColor: '#fff5e3',
                    color: '#d9af29',
                    cursor: 'not-allowed',
                    pointerEvents: 'none'
                }).text('Kulübe İstek Atılmış');
            }
        }
    })
}

//Club Change Element
$(document).ready(function () {
    if (window.screen.width < 720) {
        $(".club_in_page_links").appendTo($(".club_ın_page_bottom_contain_left_menu"));
    }
});

//------------------------------/kulupler Page------------------------------//
//Popup Filter
$(document).ready(function () {
    //Popup Image
    $(".new_club_ul li input[type='file'] ").change(function () {
        $(".new_club_ul li label").addClass("new_club_ul_image_label_selected");
        $(".new_club_ul li label").html('Fotoğraf Seçildi <i class="far fa-check-circle"></i>');
    })

    //Popup Enable
    $(".club_new_button").on('click', function () {
        $(".new_club_popup")
            .css('display', 'flex')
            .hide()
            .fadeIn(200);
        $('.new_club_form').css('transform', 'scale(1)');
    })
})

//New Club Post
function newClubFormPost() {
    Notiflix.Confirm.Show('Uyarı',
        'Verdiğim sosyal medya hesap linkinin aktif olarak kullanıldığını,' +
        ' e-posta hesabım ile bilgilendirileceğimi',
        'Onaylıyorum', 'Onaylamıyorum',
        function () {
            $.ajax({
                type: 'POST',
                url: '/kulupler',
                data: new FormData($("#newClubForm")[0]),
                processData: false,
                contentType: false,
                beforeSend: function () {
                    Notiflix.Block.Dots('.popup_container', 'Gönderiliyor...');
                },
                success: function (response) {
                    Notiflix.Block.Remove('.popup_container', 200);
                    if (response.status == '0') {
                        Notiflix.Notify.Warning(response.message);
                    } else {
                        window.location.reload();
                    }
                }
            });
        }
    );
}

//Club List Search
$(document).ready(function () {
    var oldClubList = $(".club_list_ul").html();

    // Desktop Input And Mobile Input
    $(".page_content_header input[class='club_search_input'],.mobile_club_search > input").keyup(delay(function () {
        if ($(this).val() === '') {
            $(".club_list_ul").html(oldClubList);
        } else {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: '/kulupler',
                data: {which: 'searchClub', club_name: $(this).val()},
                beforeSend: function () {
                    Notiflix.Block.Dots('.club_list_ul');
                },
                success: function (response) {
                    Notiflix.Block.Remove('.club_list_ul', 600);
                    $(".club_list_ul > a").remove();
                    $(".club_list_ul > .not_search_club").css('display', 'none');
                    if (response.status === '0') {
                        Notiflix.Notify.Failure(response.message);
                    } else {
                        if (response.clubs.length > 0) {
                            response.clubs.forEach(function (value) {
                                $(".club_list_ul").append(clubListReturn(
                                    value.club_link,
                                    value.settings.image,
                                    value.club_name,
                                    value.members_count
                                ));
                            })
                        } else {
                            $(".club_list_ul > .not_search_club").css('display', 'flex');
                        }
                    }
                }
            });
        }
    }, 500));

    function clubListReturn(clubLink, image, clubName, membersCount) {
        return ` <a href="/kulupler/${clubLink}">
                        <li>
                            <img src="/img/club/${image}">
                            <div class="information">
                                <div class="name">${clubName}</div>
                                <div class="detail">
                                    <div>
                                        <i class="fas fa-users"></i>
                                        ${membersCount}
                                    </div>
                                </div>
                            </div>
                        </li>
                    </a>`;
    }
})

//------------------------------/kulupler/{slug}/ayarlar Page------------------------------//
//Club image and background_image Settings
function clubSettingsImage(element, which) {
    let imageLoadPath = '';
    if (which === 'image') {
        imageLoadPath = '.club_ın_page_logo > img';
    } else {
        imageLoadPath = '.club_ın_page_background > img';
    }
    if (element.files && element.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(imageLoadPath).attr('src', e.target.result);
        }
        reader.readAsDataURL(element.files[0]);
    }
    $("meta[name=" + which + "]").attr('content', $(imageLoadPath).attr('src'));
    $(element).parent().children('label').addClass('club_settings_ul_content_inner_label_selected').text('Fotoğraf Seçildi');
    $(element).parent().children('.club_settings_ul_content_inner_image_warning').css('display', 'block');
    $(element).parent().children('.club_settings_ul_content_inner_image_delete').css('display', 'block');
}

//Club Selected Image Delete
function clubSettingsImageSelectedDelete(element, which) {
    $(element).css('display', 'none');
    $(element).parent().children('input[type="file"]').val(null);
    $(element).parent().children('label').removeClass("club_settings_ul_content_inner_label_selected").text('Fotoğraf Değiştir');
    $(element).parent().children('.club_settings_ul_content_inner_image_warning').css('display', 'none');
    if (which === 'image') {
        $(".club_ın_page_logo > img").attr('src', $("meta[name=" + which + "]").attr('content'));
    } else {
        $(".club_ın_page_background > img").attr('src', $("meta[name=" + which + "]").attr('content'));
    }
}

//Club Social Media Input Add/Delete
function clubSettingsSocialMediaInput(element, which, event) {
    let whichIcon;
    if (which === 'facebook') {
        whichIcon = 'fab fa-facebook-f';
    } else if (which === 'instagram') {
        whichIcon = 'fab fa-instagram';
    } else if (which === 'twitter') {
        whichIcon = 'fab fa-twitter';
    } else if (which === 'linkedin') {
        whichIcon = 'fab fa-linkedin-in';
    }
    if (event === 'add') {
        $(element).remove();
        $(".club_settings_ul_social_media_ul").append(`<li class="club_settings_ul_content_inner club_settings_ul_content_inner_social_media">
                                                       <i class="${whichIcon}"></i>
                                                       <input type="text" name="${which}">
                                                        <span onclick="clubSettingsSocialMediaInput(this,'${which}','delete')">
                                                        <i class="fas fa-trash"></i>
                                                        </span>
                                                       </li>`);
    } else if (event === 'delete') {
        $(element).parent().remove();
        $(".club_settings_ul_social_media_ul .club_settings_ul_social_media_add_ul").append(`<li onclick="clubSettingsSocialMediaInput(this,'${which}','add')"><i class="${whichIcon}"></i></li>`)
    }

}

//Club Settings Post
function clubSettingsPost() {
    var link = window.location.pathname.split('/')[2];
    $.ajax({
        type: 'POST',
        url: '/kulupler/' + link + '/ayarlar',
        data: new FormData($("#clubSettingsForm")[0]),
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function () {
            Notiflix.Block.Dots('.page_bottom_content');
        },
        success: function (response) {
            Notiflix.Block.Remove('.page_bottom_content', 200);
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                window.location.reload();

            }
        }
    });
}


//------------------------------/kulupler/{slug}/uyeler Page------------------------------//
//All Function
var link = window.location.pathname.split('/')[2];

//Member Setting
$(document).ready(function () {
    $("#clubRoleSelect").change(function () {
        var value = $(this).val();
        if (value == 1) {
            $(".club_members_settings_task_name").css('display', 'flex');
        } else {
            $(".club_members_settings_task_name").css('display', 'none');
        }
        if (value == 0) {
            $("#clubAuthoritySelect option[value='2']").remove();
        } else {
            if ($("#clubAuthoritySelect option[value='2']").length !== 1) {
                $("#clubAuthoritySelect ").append(' <option value="2">Üst Düzey Yetki</option>')
            }
        }
    })
})

//Member Settings Get
function memberSettings(id) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/kulupler/' + link + '/uyeler',
        data: {memberId: id, which: 'clubMemberSettingGet'},
        beforeSend: function () {
            $(".club_members_settings_popup")
                .css('display', 'flex')
                .hide()
                .fadeIn(200);
            $(".club_members_settings").css('transform', 'scale(1)');
            Notiflix.Block.Dots('.club_global_popup');
        },
        success: function (response) {
            Notiflix.Block.Remove('.club_global_popup');
            if (response.status === '1') {
                $(".club_members_settings_ul input[name='username']").val(response.username);
                $("#clubRoleSelect").val(response.role);
                $("#clubAuthoritySelect").val(response.authority);
                if (response.role === '0') {
                    $("#clubAuthoritySelect option[value='2']").remove();
                }
                if (response.role === '1') {
                    $(".club_members_settings_task_name").css('display', 'flex');
                    $(".club_members_settings_task_name input[name='role_name']").val(response.role_name);
                } else {
                    $(".club_members_settings_task_name").css('display', 'none');
                }
            } else {
                Notiflix.Notify.Failure(response.message);
                $(".club_members_settings_popup").fadeOut(200);
            }
        }
    });
}

//Member Settings Button
function clubMembersSettingsButtons(which) {
    if (which === 'delete') {
        Notiflix.Confirm.Show('Uyarı!', 'Kullanıcıyı üyelikten çıkarmaya devam etmek istiyor musun?', 'Evet', 'Hayır',
            function () {
                deleteAndSavePost();
            });
    } else {
        deleteAndSavePost();
    }

    function deleteAndSavePost() {
        $("#clubMemberForm input[name='which']").val(which);
        $.ajax({
            type: 'POST',
            url: '/kulupler/' + link + '/uyeler',
            data: $("#clubMemberForm").serialize(),
            beforeSend: function () {
                Notiflix.Block.Dots('.club_global_popup');
            },
            success: function (response) {
                Notiflix.Block.Remove('.club_global_popup');
                if (response.status === '0') {
                    Notiflix.Notify.Failure(response.message);
                } else {
                    location.reload();
                }
            }
        })
    }
}

//Popup Open
function newMember() {
    $(".new_member_popup")
        .css('display', 'flex')
        .hide()
        .fadeIn(200);
}

//Popup Buttons Active
function openNewMemberButtons(which) {
    if (which === 'invitation') {
        $(".new_member_invitation_ul").css('display', 'block');
        $(".new_member_add_ul").css('display', 'none');
        $(".new_member_buttons div:first-child").addClass('new_member_buttons_activated');
        $(".new_member_buttons div:last-child").removeClass();
    } else {
        $(".new_member_invitation_ul").css('display', 'none');
        $(".new_member_add_ul").css('display', 'block');
        $(".new_member_buttons div:first-child").removeClass();
        $(".new_member_buttons div:last-child").addClass('new_member_buttons_activated');
    }
}

//Popup Search User
function searchUser() {
    var username = $(".new_member_add_ul_top input[name='username']").val();
    if (username === '') {
        Notiflix.Notify.Warning('Lütfen kullanıcı adı alanını boş bırakmayınız.');
    } else {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/kulupler/' + link + '/uyeler',
            data: {username: username, which: 'searchUser'},
            beforeSend: function () {
                Notiflix.Block.Dots('.new_member_add_ul');
            },
            success: function (response) {
                Notiflix.Block.Remove('.new_member_add_ul');
                $(".new_member_add_ul li").remove();
                if (response.status === '1') {
                    if (response.users.length === 0) {
                        $(".new_member_add_ul").append('<li style="color: #cb2e2e;" class="new_member_add_ul_li_start_warning">Bu veya buna benzer bir kullanıcı yok.</li>');
                    } else {
                        response.users.forEach(function (value) {
                            let img;
                            let school_email_confirmation;
                            let button;

                            value.image === '0' ? img = ' <img src="/img/user/default.png">' : img = `<img src="/img/user/${value.image}">`;

                            value.school_email_confirmation === '0' ? school_email_confirmation = '' : school_email_confirmation = `<div style="top: 2px;margin-right: auto;margin-left:5px;top: 5px;" class="tooltip"> <i class="verification"></i> <div class="tooltip_text verification_tooltip_text">Okul e-postası onaylanmış.</div></div>`;

                            if (value.user_member === '0') {
                                if (value.school_email_confirmation === '1') {
                                    button = `<div class="new_member_add_ul_buttons"><button data-username="${value.username}" onclick="postInvitation('${value.username}')">İstek Gönder</button></div>`;
                                } else {
                                    button = `<div class="new_member_add_ul_buttons"><button class="warning_button">İstek Gönder</button></div>`;
                                }
                            } else if (value.user_member === '1') {
                                button = `<div class="new_member_add_ul_buttons_warning"><div>Zaten Üye</div> </div>`;
                            } else {
                                button = `<div class="new_member_add_ul_buttons"><button data-username="${value.username}"  class="wait_button">Bekleyen İstek</button></div>`;
                            }

                            let user_li = `<li><a href="/profil/${value.username}">${img}<div>${value.username}</div></a>
                                              ${school_email_confirmation}${button}</li>`;
                            $(".new_member_add_ul").append(user_li);
                        })
                    }
                } else {
                    Notiflix.Notify.Failure(response.message);
                }
            }
        })
    }
}

//Invitation
function clubInvitation(username, answer) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/kulupler/' + link + '/uyeler',
        data: {
            which: 'clubInvitationAnswer',
            username: username,
            answer: answer
        },
        beforeSend: function () {
            Notiflix.Block.Dots('.new_member_add_ul');
        },
        success: function (response) {
            Notiflix.Block.Remove('.new_member_add_ul', 200);
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                window.location.reload();
            }
        }
    })
}

//Member Search
$(document).ready(function () {
    var oldClubMemberDiv = $(".club_members_div").html();
    $(".club_ın_page_bottom_contain_content_inner_club_member_search input[name='clubMemberListSearch']").keyup(function () {
        if ($(this).val() === '') {
            $(".club_members_div").html(oldClubMemberDiv);
        } else {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: '/kulupler/' + link + '/uyeler',
                data: {which: 'searchMemberUser', username: $(this).val()},
                beforeSend: function () {
                    Notiflix.Block.Dots('.club_members_div');
                },
                success: function (response) {
                    Notiflix.Block.Remove('.club_members_div');
                    $(".club_members_div").text('');
                    if (response.status === '0') {
                        Notiflix.Notify.Failure(response.message);
                    } else {
                        if (response.clubMembersList.length > 0) {
                            response.clubMembersList.forEach(function (value) {
                                $(".club_members_div").append(clubMemberListDiv(value.id, value.role, value.role_name, value.username, value.image));
                            })
                        } else {
                            $(".club_members_div").append('<div class="club_members_div_warning">Böyle bir üye yok.</div>');
                        }
                    }
                }
            });
        }
    })

    function clubMemberListDiv(id, role, roleName, username, image) {
        var image_2;
        var roleSetting;
        image === '0' ? image_2 = '<img src="/img/user/default.png">' : image_2 = '<img src="/img/user/' + image + '">';
        role !== '3' ? roleSetting = `<div class="club_members_settings_icon"><i onclick="memberSettings('${id}')" class="fas fa-cog"></i></div>` : roleSetting = '';
        return `<div data-username="${username}" class="club_members_li_div">
                <a href="/profil/${username}">
                ${image_2}
                <div class="club_members_name">${username}</div>
                <div class="club_members_role_name">${roleName}</div>
                </a>
                ${roleSetting}
                </div>`;

    }
})

//Post Invitation
function postInvitation(username) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/kulupler/' + link + '/uyeler',
        data: {which: 'clubInvitationPost', username: username},
        beforeSend: function () {
            Notiflix.Block.Dots('.new_member_add_ul');
        },
        success: function (response) {
            Notiflix.Block.Remove('.new_member_add_ul', 200);
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                $(".new_member_add_ul li .new_member_add_ul_buttons button[data-username='" + username + "']").text('İstek Gönderildi.').addClass("warning_button");
            }
        }
    });
}

//Transfer President Check Username
function transferPresidentPostUsername() {
    $.ajax({
        url: '/kulupler/' + link + '/uyeler',
        type: 'POST',
        data: $("#transferCheckUsernameForm").serialize(),
        success: function (response) {
            if (response.status === '0') {
                Notiflix.Notify.Warning(response.message);
            } else {
                $(".club_members_transfer_president > .check_username").css('display', 'none');
                $(".club_members_transfer_president > .confirmation_transfer").css('display', 'flex');

                confirmationTransferInformation = $(".club_members_transfer_president > .confirmation_transfer > .information");
                confirmationTransferInformation.children('input').val(response.user.id);
                confirmationTransferInformation.children('.name_surname').text(response.user.name_surname);
                confirmationTransferInformation.children('.username').text('@' + response.user.username);

                var image = response.user.image;
                if (response.user.image == '0') {
                    image = 'default.png';
                }
                confirmationTransferInformation.children('img').attr('src', '/img/user/' + image);


                if (response.user.school_email_confirmation === '0') {
                    confirmationTransferInformation.children('.school_email_error').css('display', 'block');
                    $(".club_members_transfer_president > .confirmation_transfer > .transfer_president_confirmation").css({
                        opacity: '0.3',
                        pointerEvents: 'none'
                    });
                }
            }
        }
    });
}

//Transfer President Show President
function showTransferPresident() {
    $(".transfer_president_popup")
        .css('display', 'flex')
        .hide()
        .fadeIn(200);

    $(".transfer_president_popup_div").css('transform', 'scale(1)');
}

//Transfer President Confirmation
function confirmationTransferPresident() {
    Notiflix.Confirm.Show(
        'Uyarı',
        'Başkanlık değişikliği yapmak üzeresiniz.Bu işlem geri alınamaz.Devam etmek istiyor musunuz?',
        'Evet',
        'Hayır',
        function () {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                type: 'POST',
                url: '/kulupler/' + link + '/uyeler',
                data: {
                    which: 'transferPresidentConfirmationUser',
                    userId: $(".club_members_transfer_president > .confirmation_transfer > .information > input").val()
                },
                success: function (response) {
                    if (response.status == '0') {
                        Notiflix.Notify.Warning(response.message);
                    } else {
                        location.reload();
                    }
                }
            });
        });
}


//------------------------------/kulupler/{slug}/duyuru-haber and /kulupler/{slug}/etkinlik Page------------------------------//
$(document).ready(function () {
    //global_events_settings_button aynı zamanda events_button dır.
    //club_new_announcements_news_button aynı zamanda new_events_button dır.
    if (window.screen.width < '720') {
        $(".global_events_settings_button").html('<i class="fas fa-cog"></i>');
    }
    //Popup Enable
    $(".club_new_announcements_news_button").on('click', function () {
        $(".global_new_popup")
            .css('display', 'flex')
            .hide()
            .fadeIn(200);

        $('.club_global_popup').css('transform', 'scale(1)');
    })
    $(".club_global_popup_ul li input[name='title_image'] ").change(function () {
        //global_edit_popup
        $(this).parent().children(".club_global_popup_title_image").children("label[for='title_image_edit']").addClass("new_club_ul_image_label_selected").html('Fotoğraf Seçildi <i class="far fa-check-circle"></i>');
        //global_new_popup
        $(this).parent().children("label").addClass("new_club_ul_image_label_selected").html('Fotoğraf Seçildi <i class="far fa-check-circle"></i>');
    })
    $(".club_global_popup_ul li input[name='images[]']").change(function () {
        var count = this.files.length;
        $(this).parent().children('label').addClass("new_club_ul_image_label_selected");
        $(this).parent().children('label').html(count + ' Adet Fotoğraf Seçildi <i class="far fa-check-circle"></i>');
    })
})

//Announcement Popup New Button Disable/Enable
$(document).ready(function () {
    var titleLenght = '0';
    var subjectLenght = '0';
    var selectClubName = '0';

    if (window.location.pathname !== '/') {
        selectClubName = '1';
    }

    //If Change Input Value
    $('#clubNewAnnouncementsAndNewsForm input[name="title"],#clubNewAnnouncementsAndNewsForm textarea[name="subject"]').on('keyup', function () {
        titleLenght = $('#clubNewAnnouncementsAndNewsForm input[name="title"]').val().length;
        subjectLenght = $('#clubNewAnnouncementsAndNewsForm textarea[name="subject"]').val().length;
        announcementPopupNewButtonDisableEnable(titleLenght, subjectLenght, selectClubName);
    })

    //If Change Select Value
    $('#clubNewAnnouncementsAndNewsForm select[name="club_name"]').change(function () {
        selectClubName = $(this).val();
        announcementPopupNewButtonDisableEnable(titleLenght, subjectLenght, selectClubName);
    })

    //Form Value Control
    function announcementPopupNewButtonDisableEnable(titleLenght, subjectLenght, selectClubName) {
        var formButton = $('#clubNewAnnouncementsAndNewsForm').parent().children('li').children('button');
        if (titleLenght != '0' && subjectLenght != '0' && selectClubName != '0') {
            formButton.removeClass('button_not_click');
        } else {
            formButton.addClass('button_not_click');
        }
    }
});

//Event Popup New Button Disable/Enable
$(document).ready(function () {
    var titleLength = '0', subjectLength = '0', selectClubName = '0', selectDate = '0', locationLength = '0';

    //If Change Input Value
    $('#clubEventsForm input[name="title"],#clubEventsForm textarea[name="subject"],#clubEventsForm input[name="location"]')
        .on('keyup', function () {
            titleLength = $('#clubEventsForm input[name="title"]').val().length;
            subjectLength = $('#clubEventsForm textarea[name="subject"]').val().length;
            locationLength = $('#clubEventsForm input[name="location"]').val().length;
            eventPopupNewButtonDisableEnable();
        })

    //If Selected Online Etkinlik
    $('#clubEventsForm input[name="location_online"]').on('change', function () {
        if (this.value == '1') {
            locationLength = '1';
        } else {
            locationLength = '0';
        }
        eventPopupNewButtonDisableEnable();
    });

    //If Change Select Value
    var clubNameSelect = $('#clubEventsForm select[name="club_name"]');
    if (clubNameSelect.length == '1') {
        clubNameSelect.change(function () {
            selectClubName = $(this).val();
            eventPopupNewButtonDisableEnable();
        })
    } else {
        selectClubName = '1';
    }


    //If Selected Date
    $('#clubEventsForm input[name="date"]').change(function () {
        selectDate = '1'
        eventPopupNewButtonDisableEnable();
    })

    //Form Value Control
    function eventPopupNewButtonDisableEnable() {
        var formButton = $('#clubEventsForm').parent().children('li').children('button');
        if (titleLength != '0' && subjectLength != '0' && selectClubName != '0' && locationLength != '0' && selectDate != '0') {
            formButton.removeClass('button_not_click');
        } else {
            formButton.addClass('button_not_click');
        }
    }


});

//Edit Popup Image Settings
function globalEventsImage(which, action, element) {
    if (which === 'title_image') {
        var src = $(".club_global_popup_ul  li .global_events_edit_title_image").attr('src');
        $(".club_global_popup_ul li input[name='title_image_delete']").val(src);
        $(".club_global_popup_ul  li .global_events_edit_title_image").remove();
        $(".club_global_popup_ul  li div[class='global_events_edit_title_image_button']").remove();
        $(".club_global_popup_ul  .clubEditTıtleImageLi .club_global_popup_title_image").append('<label for="title_image_edit">Fotoğraf Yükle</label>');
    } else if (which === 'images') {
        if (action === 'delete') {
            var src = $(element).parent('li').children('img').attr('src');
            var oldValue = $(".club_global_popup_ul  li input[name='delete_images']").val();
            $(".club_global_popup_ul  li input[name='delete_images']").val(oldValue + src + ',');
            $(element).parent('li').remove();
        }
    }
}

//Edit Popup Get And Post
function globalEventsEdit(which, id, action) {
    var link = window.location.pathname.split('/')[2];
    let data, processData = true, contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
    if (action === 'popupGet') {
        data = {which: 'editGet', id: id};
    } else if (action === 'popupEditPost') {
        data = which === 'duyuru-haber' ? new FormData($("#clubNewAnnouncementsAndNewsEditForm")[0]) : new FormData($("#clubEventsEditForm")[0]);
        //data = new FormData($("#clubNewAnnouncementsAndNewsEditForm")[0]);
        processData = false;
        contentType = false;
    }
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/kulupler/' + link + '/' + which,
        data: data,
        processData: processData,
        contentType: contentType,
        beforeSend: function () {
            if (action == 'popupGet') {
                $(".global_edit_popup")
                    .css('display', 'flex')
                    .hide()
                    .fadeIn(200);
                $(".global_edit_popup > .club_global_popup").css('transform', 'scale(1)');
            }
            Notiflix.Block.Dots('.club_global_popup');
        },
        success: function (response) {
            if (action === 'popupGet') {
                if (response.status === '0') {
                    Notiflix.Notify.Warning(response.message);
                } else {
                    Notiflix.Block.Remove('.club_global_popup', 200);
                    //GLOBAL EDIT POPUP
                    var titleImage = '', imagePath = '';
                    //id
                    $(".global_edit_popup .club_global_popup_ul  input[name='id']").val(response.values.id);
                    //image path
                    if (which === 'duyuru-haber') {
                        imagePath = '/img/announcements_and_news/'
                    } else {
                        imagePath = '/img/events/'
                    }
                    //title_image
                    if (response.values.title_image === '0') {
                        $(".global_edit_popup .club_global_popup_ul  .clubEditTıtleImageLi .club_global_popup_title_image").html('<label for="title_image_edit">Fotoğraf Yükle</label>');
                    } else {
                        titleImage = which === 'duyuru-haber' ? imagePath + response.values.title_image : imagePath + response.values.title_image;
                        $(".global_edit_popup .club_global_popup_ul  .clubEditTıtleImageLi .club_global_popup_title_image").html(`<img class="global_events_edit_title_image" src="${titleImage}"><div onclick="globalEventsImage('title_image')"class="global_events_edit_title_image_button"><i class="fas fa-trash"></i></div>`);
                    }
                    //title_image_delete input
                    $(".global_edit_popup .club_global_popup_ul li input[name='title_image_delete']").val('');
                    //title and subject
                    $(".global_edit_popup .club_global_popup_ul li input[name='title']").val(response.values.title);
                    $(".global_edit_popup .club_global_popup_ul li textarea[name='subject']").val(response.values.subject);
                    //images upload button
                    $(".global_edit_popup .club_global_popup_ul .clubEditImagesLi .global_events_edit_images_ul").empty();
                    //images
                    $(".global_edit_popup .club_global_popup_ul li label[for='images_edit']").removeClass('new_club_ul_image_label_selected').text('Fotoğraf Yükle');
                    $(".global_edit_popup .club_global_popup_ul li input[name='images[]']").val('');
                    if (response.values.image.length > 1) {
                        response.values.image.split(',').forEach(function (value) {
                            $(".global_edit_popup .club_global_popup_ul .clubEditImagesLi .global_events_edit_images_ul").append(`
                                    <li>
                                      <img src="${imagePath}${value}">
                                      <i onclick="globalEventsImage('images','delete',this)" class="fas fa-trash"></i>
                                    </li>`);
                        });
                    }
                    //delete_images input
                    $(".global_edit_popup .club_global_popup_ul li input[name='delete_images']").val('');

                    //EVENT EDIT POPUP
                    if (which === 'etkinlik') {
                        $(".global_edit_popup .club_global_popup_ul li input[name='date']").val(new Date(response.values.date).toJSON().slice(0, 19));
                        $(".global_edit_popup .club_global_popup_ul li input[name='location']").val(response.values.location);
                        if (response.values.location === 'Online Etkinlik') {
                            $(".global_edit_popup .club_global_popup_ul li .club_global_popup_location input[name='location_online']").attr('checked', true);
                            $(".global_edit_popup .club_global_popup_ul li input[name='location']").addClass('input_not_write');
                        } else {
                            $(".global_edit_popup .club_global_popup_ul li .club_global_popup_location input[name='location_online']").attr('checked', false).val(1);
                            $(".global_edit_popup .club_global_popup_ul li input[name='location']").removeClass('input_not_write');
                        }
                    }
                }
            } else {
                Notiflix.Block.Remove('.club_global_popup', 300);
                if (response.status === '0') {
                    Notiflix.Notify.Failure(response.message);
                } else {
                    location.reload();
                }
            }
        }
    })
}

//Events and AnnouncementsAndNews Post
function globalEventsAdd(which, form) {
    var clubName = window.location.pathname.split('/')[2];
    // If Main Page Post
    if (window.location.pathname == '/') {
        clubName = $(form).children('li').children('select[name="club_name"]').val();
    }
    $.ajax({
        type: 'POST',
        url: '/kulupler/' + clubName + '/' + which,
        data: new FormData($(form)[0]),
        processData: false,
        contentType: false,
        beforeSend: function () {
            Notiflix.Block.Dots('.global_popup_event,.global_popup_announcement,.club_global_popup', 'Paylaşılıyor...');
        },
        success: function (response) {
            Notiflix.Block.Remove('.global_popup_event,.global_popup_announcement,.club_global_popup');
            if (response.status == '0') {
                Notiflix.Notify.Warning(response.message);
            } else {
                location.reload();
            }
        }
    })
}

//Events and AnnouncementsAndNews Delete
function globalEventsDelete(which, id) {
    Notiflix.Confirm.Show('Uyarı', 'Silme işlemi tamamlandığında yorumlar ve fotoğraflarda silinir.Devam etmek istiyor musun?', 'Evet', 'Hayır',
        function () {
            let link = window.location.pathname.split('/')[2];
            $.ajax({
                headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
                type: 'POST',
                url: '/kulupler/' + link + '/' + which,
                data: {which: 'delete', id: id},
                beforeSend: function () {
                    Notiflix.Block.Dots('.global_events_ul')
                },
                success: function (response) {
                    Notiflix.Block.Remove('.global_events_ul', 300);
                    if (response.status === '1') {
                        Notiflix.Notify.Success(response.message);
                        $(".global_events_ul  li[data-id='" + id + "']").remove();
                    } else {
                        Notiflix.Notify.Failure(response.message);
                    }
                }
            })
        });
}

function globalEventsTextarea(element) {
    textareaHeight($(element).parent().children('textarea'), 250);
    $(element).remove();
}

//------------------------------/kulupler/{slug}/etkinlik Page------------------------------//
//Event Location Online Checkbox
$(document).ready(function () {
    $(".club_global_popup_ul .club_global_popup_location input[name='location_online']").on('change', function () {
        let value = $(this).val()
        if (value === '1') {
            $(this).parents(':eq(1)').children('input[name="location"]')
                .addClass('input_not_write')
                .val('Online Etkinlik')
        } else {
            $(this).parents(':eq(1)').children('input[name="location"]')
                .removeClass('input_not_write')
                .val('')
        }
        $(this).val(value === '1' ? '0' : '1');
    });
})

//------------------------------/kulupler Page------------------------------//

//------------------------------/itiraflar Page------------------------------//
//More Show Confession Content
function moreShowConfessionContent(element) {
    $(element).parent().children("span").fadeIn();
    $(element).remove();
}

//Confession New Comment Post
function confessionsCommentPost(element, id) {
    let message = $(element).parent().children('textarea').val();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/itiraflar',
        data: {
            which: 'commentPost',
            comment: message,
            confession_id: id
        },
        success: function (response) {
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                var image = response.image === '0' ? '<img src="/img/confessions/user_default.png">' : '<img src="/img/confessions/' + response.image + '">';
                $(element).parents(':eq(2)').children('ul').prepend(`<li class="new_comment">
                                ${image}
                                <div class="user_comment">
                                    <div class="information">
                                        <div class="anonymous_username">
                                          ${response.anonymous_username}
                                        </div>
                                        <div class="date">
                                        az önce
                                        </div>
                                    </div>
                                    <div class="comment_inner">
                                       ${returnText(message)}
                                    </div>
                                </div>
                            </li>`);
                $(element).parents(':eq(2)').children('ul').children('.new_comment:first-child')
                    .css('display', 'flex')
                    .hide()
                    .fadeIn(200);
                $(element).parent().children('textarea').val('');
            }
        }
    });
}

//Confession Profile Check
function confessionAuthorityCheck() {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '/',
        data: {which: 'confessionAuthorityCheck'},
        beforeSend: function () {
            Notiflix.Block.Dots('.confession_new');
        },
        success: function (response) {
            Notiflix.Block.Remove('.confession_new', 200);
            //Show Settings Div If User Null
            if (response.user == null) {
                $(".anonymous_settings").css('display', 'flex');
                $(".anonymous_settings > form > .content > .buttons > .back_button").css('display', 'none');
            } else {
                //Confession Image
                var image = '../img/confessions/' + response.user.image;
                if (response.user.image == '0') {
                    image = '../img/confessions/user_default.png';
                }

                //Profile Div
                $(".anonymous_profile").css('display', 'flex');
                $(".anonymous_profile > img").attr('src', image);
                $(".anonymous_profile > .username").text(response.user.username);

                //Settings Div
                $(".anonymous_settings > form > .content > .image_container > img").attr('src', image);
                $(".anonymous_settings > form > .content > input[name='username']").val(response.user.username);
            }
        }
    })
}

//Confession Settings Popup
function confessionUserSettingsPost() {
    var formData = new FormData($("#confessionsUserSettingsForm")[0]);
    $.ajax({
        type: 'POST',
        url: '/itiraflar',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            Notiflix.Block.Dots('.anonymous_settings');
        },
        success: function (response) {
            Notiflix.Block.Remove('.anonymous_settings');
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                Notiflix.Notify.Success(response.message);
                //Settings Div None
                $(".anonymous_settings").css('display', 'none');

                //Profile Set
                $(".anonymous_profile").css('display', 'flex');
                $(".anonymous_profile > img").attr('src',
                    response.image == '0'
                        ? `../img/confessions/user_default.png`
                        : `../img/confessions/${response.image}`
                );
                $(".anonymous_profile > .username").text(formData.get('username'));
            }
        }
    });
}

//New Confession Post
function newConfessionsPost() {
    $.ajax({
        type: 'POST',
        url: '/itiraflar',
        data: new FormData($("#confessionForm")[0]),
        processData: false,
        contentType: false,
        beforeSend: function () {
            Notiflix.Block.Dots('.confessions_new .confessions_new_ul');
        },
        success: function (response) {
            Notiflix.Block.Remove('.confessions_new .confessions_new_ul', 200);
            if (response.status === '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                location.reload();
            }
        }
    });
}

//Confession Popup Button Disable/Enable
$(document).ready(function () {
    $('#confessionForm textarea[name="confession"]').on('keyup', function () {
        var confessionLenght = $(this).val().length;
        var username = $(".anonymous_profile > .username").text().trim();

        //Confession Lenght && Confession Username
        if (confessionLenght != 0 && username != '') {
            $("#confessionForm > button").removeClass('button_not_click');
        } else {
            $("#confessionForm > button").addClass('button_not_click');
        }
    })
})

// Show Confession Popup
function showConfessionPopup() {
    $('.confession_new_popup')
        .css("display", "flex")
        .hide()
        .fadeIn(200);
    $('.confession_new').css('transform', 'scale(1)');
    confessionAuthorityCheck();
}

// Get More Confession Comment
function getMoreConfessionComment(element) {
    var lastCommentId = $(element).parent().children(':nth-last-child(2)').attr('data-id');
    var confessionId = $(element).parents(':eq(2)').attr('data-id');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')},
        type: 'POST',
        url: '',
        data: {
            which: 'getMoreComment',
            lastCommentId: lastCommentId,
            confessionId: confessionId
        },
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status == '1') {
                var image;
                if (response.comments.length > 0) {
                    response.comments.forEach(function (comment) {
                        if (comment.user.image == '0') {
                            image = '../img/confessions/user_default.png';
                        } else {
                            image = '../img/confessions/' + comment.user.image;
                        }

                        $(element).before(`
                     <li data-id="${comment.id}">
                    <img src="${image}">
                    <div class="user_comment">
                        <div class="information">
                            <div class="anonymous_username">
                          ${comment.user.username}
                    </div>
                    <div class="date">
                          ${comment.created_at}
                    </div>
                </div>
                <div class="comment_inner">
                          ${comment.message}
                    </div>
                </div>
            </li>`);

                        if (response.comments.length < 5) {
                            $(element).css('display', 'none');
                        }

                    });
                } else {
                    $(element).parents(':eq(1)').children('.no_other_comments').css('display', 'block');
                    $(element).css('display', 'none');
                }
            } else {
                Notiflix.Notify.Failure(response.message);
            }
        }
    })
}


//------------------------------/yardim-destek Page------------------------------//
function postQuestion() {
    $.ajax({
        type: 'POST',
        url: '/yardim-destek',
        data: $("#questionForm").serialize(),
        success: function (response) {
            if (response.status == '0') {
                Notiflix.Notify.Failure(response.message);
            } else {
                Notiflix.Report.Success(
                    'Soru Başarıyla Gönderildi!',
                    'Sorular en geç 24 saat içerisinde cevaplanır.Anlayışınız için teşekkür ederiz.',
                    'Tamam',
                    function () {
                        location.reload();
                    });
            }
        }
    })
}

