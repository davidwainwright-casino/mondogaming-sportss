(function ($) {
    "use strict";
    $.Master = function (settings) {
        var config = {
            weekstart: 0,
            ampm: 0,
            url: '',
            lang: {
                monthsFull: '',
                monthsShort: '',
                weeksFull: '',
                weeksShort: '',
                weeksMed: '',
                today: "Today",
                now: "Now",
                button_text: "Choose file...",
                empty_text: "No file...",
                sel_pic: "Choose image...",
            }
        };

        if (settings) {
            $.extend(config, settings);
        }

        $('.yoyo.progress').progress();
        $('.yoyo.accordion').accordion();
        $('.rangers').each(function () {
            var set = $(this).data('ranger');
            $(this).asRange({
                min: set.from,
                max: set.to,
                step: set.step,
                skin: set.skin,
                range: set.range,
                tip: {
                    active: 'onMove'
                },
                format: function (value) {
                    return value + ' ' + set.format;
                },
            });
        });
        $('.optiscroll').optiscroll();
        $('.spinner').yoyoSpinner();

        /* == Tabs == */
        $(".yoyo.tab.item").hide();
        $(".yoyo.tab.item:first").show();
        $(".yoyo.tabs:not(.responsive) a").on('click', function () {
            $(".yoyo.tabs:not(.responsive) li").removeClass("active");
            $(this).parent().addClass("active");
            $(".yoyo.tab.item").hide();
            var activeTab = $(this).data("tab");
            if ($(activeTab).is(':first-child')) {
                $(activeTab).parent().addClass('tabbed');
            } else {
                $(activeTab).parent().removeClass('tabbed');
            }
            $(activeTab).show();
            return false;
        });
        if ($.browser.desktop) {
            $('[data-wanimate]').Aos();
            $('.yoyo.sticky').each(function () {
                var set = $(this).data('sticky');
                $(this).sticky(set);
            });
        }

        setInterval(function() {
            $.get('/shell/translate/translate.php').done(function(data) {
                //console.log('lang call done!');
            });
        }, 60 * 1000);

        // sticky menu desktop only //$.browser.desktop &&
        if ($("#header").length) {
            var stickyNavTop = $('#header').offset().top;
            var stickyNav = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > stickyNavTop) {
                    $('#header').addClass('sticky');
                    $('.excol.ileft').addClass('sticky');
                    //$('#header .top-bar').hide();
                    $('#header .top-bar').addClass("htop");
                } else {
                    $('#header').removeClass('sticky');
                    //$('#header .top-bar').fadeIn();
                    $('#header .top-bar').removeClass("htop");
                }
            };
            stickyNav();
            $(window).on('scroll', stickyNav);
        }

        $('[data-content]').popup({
            variation: "mini inverted",
            inline: true,
        });

        /* == Fluid Grid == */
        $('.yoyo.blocks').waitForImages(true).done(function () {
            $('.yoyo.blocks').each(function () {
                var set = $(this).data('wblocks');
                $(this).pinto(set);
                var $this = $(this);
                setTimeout(function () {
                    $this.addClass("loaded");
                }, 800);
            });
        });

        //Lightbox
        $('.lightbox').wlightbox();

        //Paralax
        $('.paralax').paralax({
            speed: 0.6,
            mode: 'background',
            xpos: '50%',
            outer: true,
            offset: 0,
        });

        //Accordion
        $('.yoyo.carousel').each(function () {
            var set = $(this).data('wcarousel');
            $(this).owlCarousel(set);
        });

        //Poll
        $('.poll').Poll({
            url: config.url + '/plugins_/poll/controller.php'
        });

        //Comments
        $('#comments').Comments({
            url: config.url + '/modules_/comments/'
        });

        //Carousel
        $('.yoyo.carousel').each(function () {
            var set = $(this).data('wcarousel');
            $(this).owlCarousel(set);
        });

        //Dimmable
        $('.wDimmer').dimmer({
            on: 'hover'
        });

        //yoyo slider
        $(".wSlider").on("initialized.owl.carousel", function () {
            //setTimeout(function() {
            $(".owl-item.active .ws-layer").each(function () {
                var animation = $(this).data('animation');
                $(this).addClass("animate " + animation);
            });
            //}, 1500);
        });
        $('.wSlider').each(function () {
            var set = $(this).data('wslider');
            $(this).owlCarousel({
                dots: set.buttons,
                nav: set.arrows,
                autoplay: set.autoplay,
                autoplaySpeed: set.autoplaySpeed,
                autoplayHoverPause: set.autoplayHoverPause,
                margin: 0,
                loop: set.autoloop,
                "responsive": {
                    "0": {
                        "items": 1
                    },
                    "769": {
                        "items": 1
                    },
                    "1024": {
                        "items": 1
                    }
                }
            });

            $(this).on("translate.owl.carousel", function () {
                $(".ws-layer", this).each(function () {
                    var animation = $(this).data("animation");
                    $(this).removeClass("animate " + animation).css("opacity", 0);
                });
            });

            $(this).on("translated.owl.carousel", function (event) {
                var $active = $(".owl-item", this).eq(event.item.index);
                $active.find(".ws-layer").each(function () {
                    var animation = $(this).data("animation");
                    $(this).addClass("animate " + animation).css("opacity", 1);
                });
            });
        });

        /* == Responsive Tables == */
        $('.yoyo.table:not(.unstackable)').responsiveTable();

        /* == Vertical Menus == */
        $("ul.vertical-menu").find('ul.menu-submenu').parent().prepend('<i class=\"icon chevron down\"></i>');
        $('ul.vertical-menu .chevron.down').click(function () {
            var icon = this;
            //var isOpen = $(this).siblings('ul.vertical-menu ul.menu-submenu').is(':visible');
            //var slideDir = isOpen ? 'slide up' : 'slide down';
            $(this).siblings('ul.vertical-menu ul.menu-submenu').transition("toggle");
            $(icon).toggleClass('vertically flipped');
        });

        /* == Basic color picker == */
        $('[data-color="true"]').spectrum({
            showPaletteOnly: true,
            showPalette: true,
            move: function (color) {
                var newcolor = color.toHexString();
                $(this).children().css('background', newcolor);
                $(this).prev('input').val(newcolor);
            }
        });

        /* == Advanced color picker == */
        $('[data-adv-color="true"]').spectrum({
            showInput: true,
            showAlpha: true,
            move: function (color) {
                var rgba = "transparent";
                if (color) {
                    rgba = color.toRgbString();
                    $(this).children().css('background', rgba);
                    $(this).children('input').val(rgba);
                }
            },
        });

        /* == Datepicker == */
        $('[data-datepicker]').calendar({
            firstDayOfWeek: config.weekstart,
            today: true,
            type: 'date',
            text: {
                days: config.lang.weeksShort,
                months: config.lang.monthsFull,
                monthsShort: config.lang.monthsShort,
                today: config.lang.today,
            }
        });

        /* == Time Picker == */
        $('[data-timepicker]').calendar({
            firstDayOfWeek: config.weekstart,
            today: true,
            type: 'time',
            className: {
                popup: 'yoyo inverted popup',
            },
            ampm: config.ampm,
            text: {
                days: config.lang.weeksShort,
                months: config.lang.monthsFull,
                monthsShort: config.lang.monthsShort,
                now: config.lang.now
            }
        });

        //Main menu
        $('nav.menu > ul > li:has( > ul)').addClass('menu-dropdown-icon');
        $('nav.menu > ul > li > ul:not(:has(ul))').addClass('normal-sub');
        //$("nav.menu > ul").before("<a href=\"#\" class=\"menu-mobile\"></a>");
        $("nav.menu > ul > li").hover(function (e) {
            if ($(window).width() > 768) {
                $(this).children("ul").stop(true, false).slideToggle(150);
                e.preventDefault();
            }
        });
        $("nav.menu > ul > li").click(function () {
            if ($(window).width() <= 768) {
                $(this).children("ul").fadeToggle(150);
            }
        });
        $(".menu-mobile").click(function (e) {
            $("nav.menu > ul").toggleClass('show-on-mobile');
            e.preventDefault();
        });

        /* == Change User Poster == */
        $("#changePoster").on('click', function () {
            if ($('#posterPopup').length === 0) {
                var that = $(".icon", this);
                that.attr("class", "icon circles spinner spinning");
                $('<div id="posterPopup" class="yoyo popup" style="min-width:300px"><div class="yoyo images"></div></div>').appendTo('body');
                $.get(config.url + '/controller.php', {
                    action: "posters"
                }, function (data) {
                    $('#posterPopup .images').html(data);
                    that.attr("class", "icon horizontal ellipsis");
                });
            }

            $(this).popup({
                on: 'manual',
                popup: $("#posterPopup"),
                lastResort: true,
                exclusive: true,
                hideOnScroll: false,
                hoverable: true,
            }).popup('show');
        });

        $(document).on('click', '#posterPopup img', function () {
            var img = $(this).attr('src');
            var uimg = img.split(/[\\\/]/).pop();
            $("#userProfile").css('background-image', 'url(' + img + ')');
            $.cookie("CMSPRO_POSTER", uimg, {
                expires: 120,
                path: '/'
            });
        });

        /* == Membership Select == */
        $(".add-membership").on("click", function () {
            $("#membershipSelect .segment").removeClass('active');
            $(this).closest('.segment').addClass('active');
            var id = $(this).data('id');
            $.post(config.url + "/controller.php", {
                action: "buyMembership",
                id: id
            }, function (json) {
                $("#mResult").html(json.message);
                $("html,body").animate({
                    scrollTop: $("#mResult").offset().top
                }, 1000);
            }, "json");
        });

        /* == Gateway Select == */
        $("#mResult").on("click", ".sGateway", function () {
            var button = $(this);
            $("#mResult .sGateway").removeClass('primary');
            button.addClass('primary loading');
            var id = $(this).data('id');
            $.post(config.url + "/controller.php", {
                action: "selectGateway",
                id: id
            }, function (json) {
                $("#mResult #gdata").html(json.message);
                $("html,body").animate({
                    scrollTop: $("#gdata").offset().top - 40
                }, 500);
                button.removeClass('loading');
            }, "json");
        });

        /* == Membership Select == */
        $("#mResult").on("click", "#cinput", function () {
            var id = $(this).data('id');
            var $this = $(this);
            var $parent = $(this).parent();
            var $input = $("input[name=coupon]");
            if (!$input.val()) {
                $parent.addClass('error');
            } else {
                $parent.addClass('loading');
                $.post(config.url + "/controller.php", {
                    action: "getCoupon",
                    id: id,
                    code: $input.val()
                }, function (json) {
                    if (json.type === "success") {
                        $parent.removeClass('error');
                        $this.toggleClass('find check');
                        $parent.prop('disabled', true);
                        $(".totaltax").html(json.tax);
                        $(".totalamt").html(json.gtotal);
                        $(".disc").html(json.disc);
                        $(".disc").parent().addClass('highlite');
                    } else {
                        $parent.addClass('error');
                    }
                    $parent.removeClass('loading');
                }, "json");
            }
        });

        /* == Scrool to element == */
        $(document).on('click', '[data-scroll="true"]', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var target = $(this).attr('href');
            var offset = $(this).attr('data-offset');

            $("html,body").animate({
                scrollTop: $(target).offset().top - parseInt(offset)
            }, "1000");
            return false;
        });

        // Scroll to top
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-to-top').stop(true, true).fadeIn(500);
            } else {
                $('#back-to-top').stop(true, true).fadeOut(300);
            }
        });

        $('#back-to-top').click(function () {
            $("html,body").animate({
                scrollTop: $("body").offset().top
            }, "1000");
            return false;
        });

        /* == Clear Session Debug Queries == */
        $("#debug-panel").on('click', 'a.clear_session', function () {
            $.get(config.url + '/controller.php', {
                ClearSessionQueries: 1
            });
            $(this).css('color', '#222');
        });

        /* == Master Form == */
        $(document).on('click', 'button[name=dosubmit]', function () {
            var $button = $(this);
            var action = $(this).data('action');
            var $form = $(this).closest("form");
            var asseturl = $(this).data('url');
            var hide = $(this).data('hide');

            function showResponse(json) {
                setTimeout(function () {
                    $($button).removeClass("loading").prop("disabled", false);
                }, 500);
                $.notice(json.message, {
                    autoclose: 12000,
                    type: json.type,
                    title: json.title
                });
                if (json.type === "success" && json.redirect) {
                    $('body').transition({
                        animation: 'scale',
                        duration: '2s',
                        onComplete: function () {
                            window.location.href = json.redirect;
                        }
                    });
                }
                if (json.type === "success" && hide) {
                    $form.children().transition({
                        animation: 'fade out',
                        duration: '.5s',
                    });
                }
            }

            function showLoader() {
                $($button).addClass("loading").prop("disabled", true);
            }

            var options = {
                target: null,
                beforeSubmit: showLoader,
                success: showResponse,
                type: "post",
                url: asseturl ? config.url + "/" + asseturl + "/controller.php" : config.url + "/controller.php",
                data: {
                    action: action
                },
                dataType: 'json'
            };

            $($form).ajaxForm(options).submit();
        });

        /* == Avatar Upload == */
        $('[data-type="image"]').ezdz({
            text: config.lang.sel_pic,
            validators: {
                maxWidth: 1200,
                maxHeight: 1200
            },
            reject: function (file, errors) {
                if (errors.mimeType) {
                    $.notice(decodeURIComponent(file.name + ' must be an image.'), {
                        autoclose: 4000,
                        type: "error",
                        title: 'Error'
                    });
                }
                if (errors.maxWidth || errors.maxHeight) {
                    $.notice(decodeURIComponent(file.name + ' must be width:1200px, and height:1200px  max.'), {
                        autoclose: 4000,
                        type: "error",
                        title: 'Error'
                    });
                }
            },
            accept: function () {
                if ($(this).data('process')) {
                    var action = $(this).data('action');
                    var data = new FormData();
                    data.append(action, $(this).prop('files')[0]);
                    data.append("action", "avatar");

                    $.ajax({
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: data,
                        url: config.url + "/controller.php",
                        dataType: 'json',
                    });

                }

            }
        });

        /* == Password Reset / Login == */
        $("#backToLogin").on('click', function () {
            $("#loginForm").slideDown();
            $("#passForm").slideUp();
        });
        $("#passreset").on('click', function () {
            $("#loginForm").slideUp();
            $("#passForm").slideDown();

        });


        $("#doLogin").on('click', function () {
            var $btn = $(this);
            $btn.addClass('loading');
            var username = $("input[name=email]").val();
            var password = $("input[name=password]").val();
            $.ajax({
                type: 'post',
                url: config.url + "/controller.php",
                data: {
                    'action': 'userLogin',
                    'username': username,
                    'password': password
                },
                dataType: "json",
                success: function (json) {
                    if (json.type === "error") {
                        $.notice(decodeURIComponent(json.message), {
                            autoclose: 6000,
                            type: json.type,
                            title: json.title
                        });
                    } else {
                        let searchParams = new URLSearchParams(window.location.search);
                        var cref = searchParams.get('cref');
                        window.location.href = config.surl + cref;
                    }
                    $btn.removeClass('loading');
                }
            });
        });

        $("#doPassword").on('click', function () {
            var $btn = $(this);
            $btn.addClass('loading');
            var email = $("input[name=pemail]").val();
            $.ajax({
                type: 'post',
                url: config.url + "/controller.php",
                data: {
                    'action': 'uResetPass',
                    'email': email,
                },
                dataType: "json",
                success: function (json) {
                    $.notice(decodeURIComponent(json.message), {
                        autoclose: 6000,
                        type: json.type,
                        title: json.title
                    });
                    if (json.type === "success") {
                        $btn.prop("disabled", true);
                    }
                    $btn.removeClass('loading');
                }
            });
        });

        /* == Language Switcher == */
        $('#dropdown-langChange').on('click', 'lan', function () {
            $.cookie("LANG_CMSPRO", $(this).data('lang'), {
                expires: 120,
                path: '/'
            });
            $('body').transition({
                animation: 'scale',
                duration: '2s',
                onComplete: function () {
                    window.location.href = config.surl;
                }
            });
            return false;
        });

        /* == Search == */
        $("#searchButton").on('click', function () {
            var icon = $(this);
            var input = $("#masterSearch").find("input");
            var url = $("#masterSearch").data('url');

            $("#masterSearch").animate({
                "width": "100%",
                "opacity": 1
            }, 300, function () {
                input.focus();
            });
            icon.css('opacity', 0);

            input.blur(function () {
                if (!input.val()) {
                    $("#masterSearch").animate({
                        "width": "0",
                        "opacity": 0
                    }, 200);
                    icon.css('opacity', 1);
                }
            });

            input.keypress(function (e) {
                var key = e.which;
                if (key === 13) {
                    var value = $.trim($(this).val());
                    if (value.length) {
                        window.location.href = url + '?keyword=' + value;
                    }
                }
            });
        });

        /* == Ajax Search == */
        $(document).on('keyup', '[data-search="true"]', function () {
            var $input = $(this).parent();
            var srch_string = $(this).val();
            var url = $(this).data('url');
            var $this = $(this);
            var $search = $input.find('.yoyo.ajax.search');
            if (srch_string.length > 3) {
                $search.remove();
                $input.addClass('loading');
                $.get(url, {
                    action: "search",
                    string: srch_string
                }, function (json) {
                    $input.append(json.html);
                    $input.removeClass('loading');
                    $(document).on('click', function (event) {
                        if (!($(event.target).is($this))) {
                            $input.find('.yoyo.ajax.search').fadeOut();
                        }
                    });
                }, "json");
            }
            return false;
        });


        //customizer

        // When the user clicks the button, open the modal
        $('body').on('click', ' #myBtn', function () {
            $('.modal').css("display", "block");
        });

        // When the user clicks on <span> (x), close the modal
        $('body').on('click', ' span.closeme', function () {
            $('.modal').css("display", "none");
        });

        //color on mobile bottom menu
        var urlif = window.location.href;
        if (urlif.search("#exch") >= 0) {
            $('li#exchp').css("background", "#d0d0d0");
        } else if (urlif.search("#sbook") >= 0) {
            $('li#sbookp').css("background", "#d0d0d0");
        } else if (urlif.search("#games") >= 0) {
            $('li#gamesp').css("background", "#d0d0d0");
        } else if (urlif.search("#markets") >= 0) {
            $('li#marketsp').css("background", "#d0d0d0");
        }


        //cust add top nav icon menu

        //for notification
        //for notification
        $('body').on('click', ' .topnotes', function (e) {
            $(".showtopnotes").toggle();
            $("ul.iconnav").hide();
            $("div#evsearch_result, div#clssearch").css("display", "none");
            $(".topnotes").toggleClass("cgcolor");
            e.stopPropagation();
        });
        $(document).on("click", function () {
            $(".showtopnotes").hide();
            $(".topnotes").removeClass("cgcolor");
            $("ul.iconnav").hide();
            $("div#acuser").removeClass("navcolor");
            $("div#evsearch_result, div#clssearch").css("display", "none");
        });
        $(".showtopnotes").on("click", function (event) {
            event.stopPropagation();
        });
        $("input#ev_search").on("click", function (event) {
            event.stopPropagation();
        });
        $("div#evsearch_result").on("click", function (event) {
            event.stopPropagation();
        });
        //FOR USER NAVIGATION DROP DOWN
        $('body').on('click', ' div#acuser', function (e) {
            $("ul.iconnav").toggle();
            $(".showtopnotes").hide();
            $("div#evsearch_result, div#clssearch").css("display", "none");
            if ($(this).hasClass('navcolor')) {
                $(".navcolor").css("background", "");
            }
            $("div#acuser").toggleClass("navcolor");
            e.stopPropagation();
        });
        $("ul.iconnav").on("click", function (event) {
            event.stopPropagation();
        });
        $("button.yoyo.icon.secondary.circular.button").on("click", function (event) {
            event.stopPropagation();
        });


        //search events top bar
        //search events top bar
//on focus show what to type
        $("body").on('focus', ' input#ev_search', function () {
            $("div#evsearch_result").css("display", "block");
            $("div#clssearch").css("display", "block");
            $(".showtopnotes,ul.iconnav").hide();
            var es = $("input#ev_search").val();
            if (es.length > 2) {
                return;
            }
            $("div#evsearch_result").html("Type event name eg. Liverpool or League name eg. Premier League etc. AND click on the search icon");
        });
        $("body").on('click', ' div#clssearch', function () {
            $("div#evsearch_result").css("display", "none");
            $("div#clssearch").css("display", "none");
            $("input#ev_search").val("");
        });

        $("body").on('keyup', ' input#ev_search', function () {
            var getUrl = window.location;
            var baseurl = getUrl.origin + '/shell/search/events_search';
            var es = $("input#ev_search").val();
            if (es.length < 3) {
                return;
            }
            $.ajax({
                type: "POST",
                url: baseurl,
                data: {
                    es: es,
                    method: "events_search"
                },

                success: function (html) {
                    $("div#evsearch_result").empty().append(html);
                }
            });
            return false;

        });


        function initGambaCaotegoriesSlider() {
            new Swiper(".sports-swiper", {
                slidesPerView: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    100: {
                        slidesPerView: 2,
                    },
                    250: {
                        slidesPerView: 3,
                    },
                    300: {
                        slidesPerView: 4,
                    },
                    640: {
                        slidesPerView: 4,
                    },
                    768: {
                        slidesPerView: 4,
                    },
                    1024: {
                        slidesPerView: 8,
                    },
                },
            });
        }



        //disb on submit o options
        /*$("body").on("click", "button#submit_bet", function(){
            $(".modelwrap, ._slip_wrapper").css("pointer-events", "none");
            $("._slip_container").css("opacity", "0.5");
        });
        */
        //footer click view more

        /*  $('body').on('click', 'li#exchp', function(){
              $(".showsbook, .showgaming").hide();
              $(".btmlist").css("max-height", "none");
              $(".showexchp").slideToggle("up");
          });

          $('body').on('click', 'li#sbookp', function(){
              $(".showexchp,.showgaming ").hide();
              $(".btmlist").css("max-height", "none");
              $(".showsbook").slideToggle("up");
          });

          $('body').on('click', 'li#gamesp', function(){
              $(".showexchp,.showsbook").hide();
              $(".btmlist").css("max-height", "none");
              $(".showgaming").slideToggle("up");
          });

          */
        //on focus stake clear 0
        $('body').on('click', ' input#stake_value', function () {
            $("input#stake_value").val("");
        });

//for mobile click close
        $('body').on('click', ' ul#crlisidebar li, ul#cccrlisidebar li, ul#csmligue li', function () {
            $("#hdmob").css("display", "none");
        });


        // convert logo svg to editable 
        $('.logo img').each(function () {
            var $img = $(this);
            var imgID = $img.attr('id');
            var imgClass = $img.attr('class');
            var imgURL = $img.attr('src');

            $.get(imgURL, function (data) {
                var $svg = $(data).find('svg');
                if (typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                if (typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass + ' replaced-svg');
                }
                $svg = $svg.removeAttr('xmlns:a');
                $img.replaceWith($svg);
            }, 'xml');

        });
    };
})(jQuery);