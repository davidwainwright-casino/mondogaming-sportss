String.prototype.parseURL = function() {
    return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/g, function(url) {
        return url.link(url);
    });
};
String.prototype.trimString = function(length) {
    return this.substr(0, length) + (this.length > length ? '...' : '');
};

function Timeline(element, data, $, undefined) {
    if ($ === undefined) {
        $ = jQuery;
    }
    var SELF = this;
    this._container = null;
    this._spine = null;
    this._overlay = null;
    this._original_data = data ? $.extend(true, [], data) : [];
    this._data = [];
    this._responsive = false;
    this._options = {
        dateFormat: 'DD MMMM YYYY',
        first_separator: true,
        separator: 'year',
        columnMode: 'dual',
        order: 'desc',
        responsive_width: null,
        animation: true,
        max: null,
        loadmore: 0,
        facebookAppId: null,
        facebookAccessToken: null,
        facebookPageId: null,
        twitterSearchKey: null,
        onSearchSuccess: null,
        onSearchError: null,
        readmore_text: 'Read More',
        loadmore_text: 'Load More',
        month_translation: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    };
    this._years = [];
    this._months = [];
    this._spine_margin = 100;
    this._facebook_content_length = 300;
    this._facebook_description_length = 150;
    this._elements = [];
    this._separators = [];
    this._iframe_queue = [];
    this._use_css3 = (function() {
        var style = document.body.style;
        if (typeof style['transition'] == 'string') {
            return true;
        }
        var prefix = ['Webkit', 'Moz', 'Khtml', 'O', 'ms'];
        for (var i = 0; i < prefix.length; i++) {
            if (typeof(style[prefix[i] + 'Transition']) == 'string') {
                return true;
            }
        }
        return false;
    })();
    this._default_element_data = {
        type: 'blog_post',
        date: '2000-01-01',
        dateDisplay: '',
        dateFormat: null,
        title: null,
        images: [],
        content: [],
        readmore: null,
        speed: null,
        height: 300,
        url: null
    };
    this._transitionEnd = function(element, event_handler) {
        var support_transition = false;
        var transition_names = ['transition', 'WebkitTransition', 'MozTransition', 'msTransition'];
        var temp_element = document.createElement('div');
        $(transition_names).each(function(index, transition_name) {
            if (temp_element.style[transition_name] !== undefined) {
                support_transition = true;
                return false;
            }
        });
        if (support_transition) {
            element.one('webkitTransitionEnd oTransitionEnd MSTransitionEnd transitionend', event_handler);
        } else {
            setTimeout(event_handler, 0);
        }
        return element;
    }, this._prepareData = function() {
        $.each(SELF._original_data, function(index, data) {});
        SELF._sortData(SELF._original_data);
        if (SELF._options.max && SELF._options.max < SELF._original_data.length) {
            SELF._data = SELF._original_data.slice(0, SELF._options.max);
        } else {
            SELF._data = SELF._original_data;
        }
    };
    this._sortData = function(timeline_data) {
        if (timeline_data) {
            timeline_data.sort(function(a, b) {
                if (SELF._options.order === 'desc') {
                    return parseInt(b.date.replace(/[^0-9]/g, ''), 10) - parseInt(a.date.replace(/[^0-9]/g, ''), 10);
                } else {
                    return parseInt(a.date.replace(/[^0-9]/g, ''), 10) - parseInt(b.date.replace(/[^0-9]/g, ''), 10);
                }
            });
        }
        return timeline_data;
    };
    this._createElement = function(element_data, position) {
        element_data = $.extend({}, SELF._default_element_data, element_data);
        var element = $('<div>').addClass('timeline_element ' + element_data.type);
        if (position === 'left') {
            element.addClass('timeline_element_left');
        } else if (position === 'right') {
            element.addClass('timeline_element_right');
        }
        if (!SELF._options.animation) {
            element.addClass('animated');
        }
        var element_box = $('<div>').addClass('timeline_element_box').appendTo(element);
        if (element_data.title) {
            var title = $('<div>').addClass('timeline_title').html('<span class="timeline_title_label">' + element_data.title + '</span><span class="timeline_title_date">' + element_data.dateDisplay + '</span>').appendTo(element_box);
        } else {
            element.addClass('notitle');
        }
        switch (element_data.type) {
            case 'iframe':
                var content = $('<div>').addClass('wojo passive attached segment loading').height(element_data.height).appendTo(element_box);
                SELF._iframe_queue.push({
                    element: content,
                    url: element_data.url
                });
                break;
            case 'event':
                var box = $('<div>').addClass('content').html(element_data.content.body).appendTo(element_box);
                if (element_data.content.venue.length) {
                    $('<div class="wojo small divider"></div>').appendTo(box);
                    var list = '<div class="item"> <i class="icon marker"></i><div class="middle aligned content"> ' + element_data.content.venue + ' </div></div>';
                    list += '<div class="item"> <i class="icon phone"></i><div class="middle aligned content"> ' + element_data.content.phone + ' </div></div>';
                    list += '<div class="item"> <i class="icon microphone"></i><div class="middle aligned content"> ' + element_data.content.person + ' </div></div>';
                    list += '<div class="item"> <i class="icon email"></i><div class="middle aligned content"> ' + element_data.content.email + ' </div></div>';
                    $('<div>').addClass('wojo small horizontal divided list').html(list).appendTo(box).wrap('<div class="content-center"></div>');
                }
                break;
            case 'gallery':
                if (element_data.images.length) {
                    if (element_data.content) {
                        $('<div>').addClass('content').html(element_data.content).appendTo(element_box);
                    }
                    var image_html = '';
                    $(element_data.images).each(function(index, image_src) {
                        image_html += '<img src="' + image_src + '" />'
                    });
                    $('<div class="wojo carousel">' + image_html + '</div>').appendTo(element_box);
                    $('.wojo.carousel', element_box).owlCarousel({
                        center: true,
                        loop: true,
                        items: 2,
                        "dots": false,
                        "nav": false
                    });
                }
                break;
            case 'blog_post':
                if (!element_data.content && !element_data.readmore) {
                    element.addClass('nocontent');
                }
                var html = '';
                $(element_data.images).each(function(index, image_src) {
                    if (element_data.images.length === 1) {
                        html += '<div data-total="' + element_data.images.length + '" data-order="' + index + '" class="img_container' + (index === 0 ? ' active' : '') + '" style="display:' + (index === 0 ? 'block' : 'none') + ';">';
                        html += '' + '<div class="tmDimmer dimmable image">' + '<div class="wojo color dimmer">' + '<div class="content">' + '<div class="center"> <a href="' + image_src + '" class="tmLightbox inverted"><i class="icon large circular inverted url alt"></i></a> </div>' + '</div>' + '</div>' + '<img src="' + image_src + '"></div>';
                        html += '</div>';
                    } else {
                        html += '<img src="' + image_src + '">';
                    }
                });
                if (element_data.images.length > 1) {
                    var $container = $('<div>').addClass('wojo carousel').html(html).appendTo(element_box);
                    $container.owlCarousel({
                        dots: true,
                        nav: false,
                        items: 1
                    });
                } else {
                    element_box.append(html);
                }
                if (element_data.content) {
                    $('<div>').addClass('content').html(element_data.content).appendTo(element_box);
                }
                if (element_data.readmore) {
                    $('<div>').addClass('readmore').html('<a href="' + element_data.readmore + '">' + SELF._options.readmore_text + '</a>').appendTo(element_box);
                }
                break;
        }
        element.appendTo(SELF._container);
        SELF._elements.push(element);
        return element;
    };
    this._createSeparator = function(time) {
        var separator = $('<div>').addClass('date_separator').attr('id', 'timeline_date_separator_' + time).html('<span>' + time + '</span>').appendTo(SELF._container);
        if (!SELF._options.animation) {
            separator.addClass('animated');
        }
        SELF._separators.push(separator);
    };
    this._render = function(timeline_data, is_append) {
        var is_odd = true;
        $(timeline_data).each(function(index, data) {
            if (SELF._options.max && SELF._options.max <= index && !is_append) {
                return false;
            }
            if (is_append && index === 0) {
                var last_element = SELF._container.children(':last');
                if (last_element.length && last_element.hasClass('timeline_element_left')) {
                    is_odd = false;
                }
            }
            var year = parseInt(data.date.split('-')[0], 10);
            var month = parseInt(data.date.split('-')[1], 10);
            month = year + '-' + month;
            var start_new = false;
            if ($.inArray(year, SELF._years) === -1 && (SELF._options.separator === 'year' || SELF._options.separator === null)) {
                start_new = true;
                SELF._years.push(year);
            }
            if ($.inArray(month, SELF._months) === -1 && (SELF._options.separator === 'month' || SELF._options.separator === 'month_year')) {
                start_new = true;
                SELF._months.push(month);
            }
            if (start_new) {
                if (SELF._options.separator === 'year') {
                    if (SELF._years.length > 1 || SELF._options.first_separator) {
                        SELF._createSeparator(year);
                    }
                } else if (SELF._options.separator === 'month' || SELF._options.separator === 'month_year') {
                    if (SELF._months.length > 1 || SELF._options.first_separator) {
                        var month_display = SELF._options.month_translation[parseInt(month.split('-')[1], 10) - 1];
                        if (SELF._options.separator === 'month_year') {
                            month_display = month_display + ' ' + year;
                        }
                        SELF._createSeparator(month_display);
                    }
                }
                if (SELF._options.separator) {
                    is_odd = true;
                }
            }
            if (SELF._options.columnMode === 'dual') {
                SELF._createElement(data, is_odd ? 'left' : 'right');
            } else {
                SELF._createElement(data);
            }
            is_odd = is_odd ? false : true;
        });
    };
    this._startAnimation = function(callback) {
        $(window).width();
        if (SELF._use_css3) {
            SELF._spine.addClass('animated');
        } else {
            SELF._spine.animate({
                bottom: '0%'
            }, 500, function() {
                SELF._spine.addClass('animated');
            });
        }
        if (SELF._options.separator === 'year' || SELF._options.separator === 'month' || SELF._options.separator === 'month_year') {
            setTimeout(function() {
                $(SELF._separators).each(function(index, separator) {
                    if (SELF._use_css3) {
                        separator.addClass('animated');
                    } else {
                        separator.children('span').animate({
                            opacity: 1,
                            top: '50%'
                        }, 300, function() {
                            separator.addClass('animated');
                        });
                    }
                });
            }, 500);
        }
        var count = 0;
        $(SELF._elements).each(function(index, element) {
            if (!element.hasClass('animated')) {
                count++;
                setTimeout(function(count) {
                    if (SELF._use_css3) {
                        element.addClass('animated');
                    } else {
                        element.hide().addClass('animated').fadeIn();
                    }
                    if (index === SELF._elements.length - 1) {
                        setTimeout(callback, 200);
                    }
                }, (SELF._options.separator === 'year' || SELF._options.separator === 'month' || SELF._options.separator === 'month_year' ? 1000 : 500) + count * 100);
            }
        });
        return true;
    };
    this._loadMore = function() {
        if (SELF._loadmore.hasClass('loading')) {
            return;
        }
        SELF._loadmore.addClass('loading');
        setTimeout(function() {
            SELF._loadmore.removeClass('loading');
            var new_data = SELF._original_data.slice(SELF._data.length, SELF._data.length + SELF._options.loadmore);
            SELF.appendData(new_data);
            if (SELF._data.length >= SELF._original_data.length) {
                SELF._loadmore.remove();
            }
        }, 1000);
    };
    this._display = function() {
        SELF._container = $('<div>').addClass('timeline timeline_' + SELF._options.columnMode);
        if (!$.support.opacity) {
            SELF._container.addClass('opacityFilter');
        }
        if (!SELF._use_css3) {
            SELF._container.addClass('noneCSS3');
        }
        SELF._spine = $('<div>').addClass('spine').appendTo(SELF._container);
        if (!SELF._options.animation) {
            SELF._spine.addClass('animated');
        }
        SELF._render(SELF._data);
        SELF._container.data('loaded', true).appendTo(element);
        if (SELF._options.loadmore && SELF._options.max && SELF._original_data.length && SELF._original_data.length > this._options.max) {
            SELF._loadmore = $('<div>').addClass('wojo button timeline_loadmore').text(SELF._options.loadmore_text).appendTo(element);
            SELF._loadmore.wrap('<div class="tmload"></div>');
        }
        if (SELF._options.animation) {
            setTimeout(function() {
                SELF._startAnimation(SELF._processIframeQueue);
            }, 200);
        } else {
            SELF._processIframeQueue();
        }
        if (SELF._options.responsive_width) {
            $(window).on('resize', SELF._windowResize);
        }
        if (SELF._options.responsive_width) {
            $(window).resize();
        }
        $(document).on('click', SELF._handleClick);
        $(document).on('keydown', SELF._handleKeyDown);
        $('.tmLightbox').wlightbox();
        $('.tmDimmer').dimmer({
            on: 'hover'
        });
        return true;
    };
    this._makeResponsive = function(responsive) {
        if (responsive) {
            if (!this._responsive) {
                this._responsive = true;
                SELF._container.removeClass('timeline_left timeline_right timeline_dual');
                SELF._container.addClass('timeline_center');
            }
        } else {
            if (this._responsive) {
                this._responsive = false;
                SELF._container.removeClass('timeline_center');
                SELF._container.addClass('timeline_' + SELF._options.columnMode);
            }
        }
    };
    this._processIframeQueue = function() {
        $(SELF._iframe_queue).each(function(index, queue) {
            queue.element.removeClass('loading').html('<iframe frameborder="0" src="' + queue.url + '"></iframe>');
        });
    };
    this._windowResize = function(e) {
        if ($(window).width() < SELF._options.responsive_width) {
            SELF._makeResponsive(true);
        } else {
            SELF._makeResponsive(false);
        }
    };
    this._handleClick = function(e) {
        var element = $(e.target);
        if (element.hasClass('timeline_loadmore')) {
            SELF._loadMore();
        }
        return true;
    };
    this._handleKeyDown = function(e) {
        switch (parseInt(e.which, 10)) {
            case 27:
                if (SELF._overlay.hasClass('open')) {
                    SELF._closeLightBox(e);
                }
                break;
            case 37:
                if (SELF._lightbox.hasClass('loaded') && SELF._lightbox.find('.navigation span.prev').is(':visible')) {
                    SELF._lightbox.find('.navigation span.prev').click();
                    return false;
                }
                break;
            case 39:
                if (SELF._lightbox.hasClass('loaded') && SELF._lightbox.find('.navigation span.next').is(':visible')) {
                    SELF._lightbox.find('.navigation span.next').click();
                    return false;
                }
                break;
        }
    };
    this._loadFacebook = function(callback) {
        var api_url = '/' + SELF._options.facebookPageId + '/feed';
        var api_option = {
            'access_token': SELF._options.facebookAccessToken
        };
        var picture_in_loading = 0;
        var _done = function() {
            if (callback !== undefined) {
                callback();
            }
            if (SELF._options.onSearchSuccess) {
                SELF._options.onSearchSuccess(SELF._original_data);
            }
        };
        var _initContent = function(facebook_data) {
            var content = '<div class="facebook_type_' + facebook_data.type + '">' + '<div class="facebook_left_column"><img class="facebook_profile" src="https://graph.facebook.com/' + facebook_data.from.id + '/picture?type=square" /></div>' + '<div class="facebook_right_column">';
            if (facebook_data.message) {
                content += '<div class="facebook_content">' + facebook_data.message.trimString(SELF._facebook_message_length).parseURL() + '</div>';
            }
            content += '</div>' + '<div style="clear:both;"></div>';
            return content;
        };
        var _pushData = function(facebook_data, content) {
            SELF._original_data.push({
                type: 'blog_post',
                date: facebook_data.updated_time,
                title: facebook_data.from.name,
                content: content
            });
        };
        FB.init({
            appId: SELF._options.facebookAppId,
            version: 'v2.0'
        });
        FB.api(api_url, api_option, function(response) {
            if (!response || !response.data || !response.data.length) {
                if (SELF._options.onSearchError) {
                    SELF._options.onSearchError(response);
                }
                return;
            }
            $(response.data).each(function(index, facebook_data) {
                if (facebook_data.from.id) {
                    if (facebook_data.type === 'photo') {
                        picture_in_loading++;
                        FB.api('/' + facebook_data.object_id, api_option, function(response) {
                            var content = _initContent(facebook_data);
                            if (response.source) {
                                content += '<div class="facebook_post">' + '<a href="' + facebook_data.link + '" style="display:inline;"><img class="facebook_picture" align="left" src="' + response.source + '" /></a>' + '</div>';
                            }
                            content += '</div>';
                            _pushData(facebook_data, content);
                            picture_in_loading--;
                            if (picture_in_loading === 0) {
                                _done();
                            }
                        });
                    } else if (facebook_data.message) {
                        var content = _initContent(facebook_data);
                        if (facebook_data.picture) {
                            content += '<div class="facebook_post">' + '<a href="' + facebook_data.link + '" style="display:inline;"><img class="facebook_picture" align="left" src="' + facebook_data.picture + '" /></a>' + '<div class="description_container">' + (facebook_data.name ? '<a href="' + facebook_data.link + '">' + facebook_data.name + '</a>' : '') + (facebook_data.caption ? '<div class="facebook_caption">' + facebook_data.caption + '</div>' : '') + (facebook_data.description ? '<div class="facebook_description">' + facebook_data.description.trimString(SELF._facebook_description_length).parseURL() + '</div>' : '') + '</div>' + '</div>';
                        }
                        content += '</div>';
                        _pushData(facebook_data, content);
                    }
                }
            });
            if (picture_in_loading === 0) {
                _done();
            }
        });
    };
    this._loadTwitter = function(callback) {
        $.getJSON('https://www.melonhtml5.com/api/?action=twittersearch&q=' + SELF._options.twitterSearchKey + '&callback=?', function(response) {
            if (!response.statuses.length) {
                if (SELF._options.onSearchError) {
                    SELF._options.onSearchError(response);
                }
                return;
            }
            $(response.statuses).each(function(index, tweet_data) {
                SELF._original_data.push({
                    type: 'blog_post',
                    date: tweet_data.created_at,
                    title: '<a href="http://www.twitter.com/' + tweet_data.user.screen_name + '" target="_blank" style="text-decoration:none;color:#AAAAAA;">' + tweet_data.user.screen_name + '</a>',
                    content: '<div><img class="twitter_profile" align="left" src="' + tweet_data.user.profile_image_url + '" /></div>' + tweet_data.text.parseURL().parseHashtag()
                });
            });
            if (callback !== undefined) {
                callback();
            }
            if (SELF._options.onSearchSuccess) {
                SELF._options.onSearchSuccess(response);
            }
        });
    };
    this.setOptions = function(opts) {
        SELF._options = $.extend(SELF._options, opts);
        return SELF._options;
    };
    this.display = function() {
        var _go = function() {
            SELF._prepareData();
            SELF._display();
        };
        var has_twitter = SELF._options.twitterSearchKey;
        var has_facebook = SELF._options.facebookAppId && SELF._options.facebookAccessToken && SELF._options.facebookPageId;
        if (SELF._original_data && SELF._original_data.length) {
            _go();
        } else if (has_twitter && has_facebook) {
            var loaded = 0;
            var _onLoad = function() {
                loaded++;
                if (loaded == 2) {
                    _go();
                }
            };
            SELF._loadTwitter(_onLoad);
            SELF._loadFacebook(_onLoad);
        } else if (has_twitter) {
            SELF._loadTwitter(_go);
        } else if (has_facebook) {
            if (FB) {
                SELF._loadFacebook(_go);
            }
        }
    };
    this.appendData = function(timeline_data) {
        var end_date = parseInt(SELF._data[SELF._data.length - 1].date.replace(/-/g, ''), 10);
        var new_data = [];
        if (SELF._options.order === 'desc') {
            $(timeline_data).each(function(index, data) {
                if (parseInt(data.date.replace(/-/g, ''), 10) <= end_date) {
                    new_data.push(data);
                }
            });
        } else {
            $(timeline_data).each(function(index, data) {
                if (parseInt(data.date.replace(/-/g, ''), 10) >= end_date) {
                    new_data.push(data);
                }
            });
        }
        SELF._data = SELF._data.concat(new_data);
        SELF._render(new_data, true);
        if (SELF._options.animation) {
            SELF._startAnimation(SELF._processIframeQueue);
        } else {
            SELF._processIframeQueue();
        }
    };
}