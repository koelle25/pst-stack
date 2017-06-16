/*!
 * Start Bootstrap - SB Admin 2 v3.3.7+1 (http://startbootstrap.com/template-overviews/sb-admin-2)
 * Copyright 2013-2016 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap/blob/gh-pages/LICENSE)
 */
$(function() {
    $('#side-menu').metisMenu();

    function addChatMessageLeft() {
        $('.chat-panel .panel-body .chat').append(
            '<li class="left clearfix">' +
                '<span class="chat-img pull-left">' +
                    '<img src="http://placehold.it/50/55C1E7/fff" alt="User Avatar" class="img-circle">' +
                '</span>' +
                '<div class="chat-body clearfix">' +
                    '<div class="header">' +
                        '<strong class="primary-font">Jack Sparrow</strong>' +
                        '<small class="pull-right text-muted">' +
                            '<i class="fa fa-clock-o fa-fw"></i> 12 mins ago' +
                        '</small>' +
                    '</div>' +
                    '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>' +
                '</div>' +
            '</li>'
        ).trigger("heightChanged");

        window.setTimeout(addChatMessageRight, 5000);
    }

    function addChatMessageRight() {
        $('.chat-panel .panel-body .chat').append(
            '<li class="right clearfix">' +
                '<span class="chat-img pull-right">' +
                    '<img src="http://placehold.it/50/FA6F57/fff" alt="User Avatar" class="img-circle">' +
                '</span>' +
                '<div class="chat-body clearfix">' +
                    '<div class="header">' +
                        '<small class="pull-right text-muted">' +
                            '<i class="fa fa-clock-o fa-fw"></i> 11 mins ago' +
                        '</small>' +
                        '<strong class="pull-right primary-font">Bhaumik Patel</strong>' +
                    '</div>' +
                    '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales.</p>' +
                '</div>' +
            '</li>'
        ).trigger("heightChanged");

        window.setTimeout(addChatMessageLeft, 5000);
    }

    window.setTimeout(addChatMessageLeft, 5000);

    function scrollToChatPanelEnd() {
        $('.chat-panel .panel-body').animate({scrollTop: $('.chat-panel .panel-body .chat').height()}, 1000);
    }

    $('.chat-panel .panel-body .chat').on("heightChanged", scrollToChatPanelEnd);
    scrollToChatPanelEnd();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(window).on("load resize", function() {
    var topOffset = 50;
    var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
    if (width < 768) {
        $('div.navbar-collapse').addClass('collapse');
        topOffset = 100; // 2-row-menu
    } else {
        $('div.navbar-collapse').removeClass('collapse');
    }

    var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
    height = height - topOffset;
    if (height < 1) {
        height = 1;
    } else {
        height = height - 2;
    }
    if (height > topOffset) {
        $("#page-wrapper").css("min-height", (height) + "px");
    }
});

$(function() {
    var url = window.location;
    // var element = $('ul.nav a').filter(function() {
    //     return this.href == url;
    // }).addClass('active').parent().parent().addClass('in').parent();
    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }
});