(function($) {
    if ($('head script[src="https://www.youtube.com/iframe_api"]').length) {
        return
    } else {
        var H = document.createElement('script');
        H.src = "https://www.youtube.com/iframe_api";
        var I = document.getElementsByTagName('script')[0];
        I.parentNode.insertBefore(H, I)
    }
    $.wojo_tube = function(el, options) {
        var base = this;
        base.$el = $(el);
        base.el = el;
        base.$el.data("wojo_tube", base);
        base.init = function() {
            base.options = $.extend({}, $.wojo_tube.defaultOptions, options);
            base.options_copy = $.extend({}, $.wojo_tube.defaultOptions, options);
            base.api_key = base.options.api_key;
            base.$el.addClass('ytplayer');
            base.type = false;
            if (base.options.playlist !== false) {
                base.id = 'yt_player_' + base.options.playlist.replace(/[^a-z0-9]/ig, '');
                base.type = 'playlist';
            } else if (base.options.channel !== false) {
                base.id = 'yt_player_' + base.options.channel.replace(/[^a-z0-9]/ig, '');
                base.type = 'channel';
            } else if (base.options.user !== false) {
                base.id = 'yt_player_' + base.options.user.replace(/[^a-z0-9]/ig, '');
                base.type = 'user';
            } else if (base.options.videos !== false) {
                if (typeof(base.options.videos) === 'string') {
                    base.options.videos = [base.options.videos];
                }
                base.id = 'yt_player_' + base.options.videos[0].replace(/[^a-z0-9]/ig, '');
                base.type = 'videos';
            } else {
                base.display_error('No playlist/channel/user/videos entered. Set at least 1.', true);
                return;
            }
            if (typeof base.$el.attr('id') !== typeof undefined && base.$el.attr('id') !== false) {
                base.id = base.$el.attr('id');
            } else {
                base.$el.attr('id', base.id);
            }
            if (base.options.max_results > 50) {
                base.options.max_results = 50;
            }
            base.$controls = {
				'play' : "play",
				'time' : "time",
				'time_bar' : "time_bar",
				'time_bar_buffer' : "time_bar_buffer",
				'time_bar_time' : "time_bar_time",
				'volume' : "volume",
				'volume_icon' : "volume_icon",
				'volume_bar' : "volume_bar",
				'volume_amount' : "volume_amount",
				'share' : "share",
				'youtube' : "youtube",
				'forward' : "forward",
				'backward' : "backward",
				'playlist_toggle' : "playlist_toggle",
				'fullscreen' : "fullscreen",
				};
            base.$title = null;
            base.$container = base.$el.find('.wojo-container');
            base.youtube = null;
            base.playlist_items = [];
            base.playlist_count = 0;
            base.info = {
                'width': 0,
                'height': 0,
                'duration': 0,
                'current_time': 0,
                'previous_time': 0,
                'volume': base.options.volume,
                'time_drag': false,
                'volume_drag': false,
                'ie': base.detect_ie(),
                'ie_previous_time': 0,
                'touch': base.detect_touch(),
                'youtube_loaded': false,
                'ios': (navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false),
                'mobile': (navigator.userAgent.match(/(Android|webOS|iPad|iPhone|iPod|BlackBerry|Windows Phone)/g) ? true : false),
                'state': false,
                'index': 0,
                'hover': true,
                'fullscreen': false,
                'idle_time': 0,
                'idle_controls_hidden': false,
                'playlist_shown': true,
                'horizontal_playlist_shown': true,
                'playlist_width': 200,
                'playlist_animating': false,
                'first_play': false,
                'current_video_url': '',
                'next_page_token': false,
                'playlist_i': 0,
                'alternative_api_ready_check': false,
            };
            if (base.info.ios) {
                base.$el.addClass('wojo-ios');
                base.options.volume_control = false;
                base.options_copy.volume_control = false;
            }
            if (base.info.mobile) {
                base.options.show_controls_on_load = true;
                base.options.show_controls_on_pause = true;
                base.options.show_controls_on_play = true;
                base.$el.addClass('wojo-mobile');
            }
            if (base.info.ie) {
                base.$el.addClass('wojo-ie');
            }
            if (!base.$el[0].requestFullScreen && !base.$el[0].mozRequestFullScreen && !base.$el[0].webkitRequestFullScreen) {
                base.options.fullscreen_control = false;
            }
            base.create_player_element();
            base.init_playlist();
            base.create_controls();
            base.create_title();
            base.create_overlays();
            base.show_controls();
            base.bind_controls();
            $(window).on('resize', base.resize);
            base.resize();
            base.init_time_slider();
            base.init_volume_slider();
            base.set_style();
            if (base.options.width !== false) {
                base.$el.css('width', base.options.width);
                base.resize();
            }
            if (!base.options.show_controls_on_load) {
                base.hide_controls();
            }
            if (base.options.playlist_type === 'horizontal') {
                base.hide_playlist(true);
                if (!base.options.show_playlist) {
                    base.hide_horizontal_playlist();
                } else {
                    base.show_horizontal_playlist();
                }
            } else {
                base.hide_horizontal_playlist();
                if (!base.options.show_playlist) {
                    base.hide_playlist(true);
                }
            }
            document.addEventListener("fullscreenchange", function() {
                if (!document.fullscreen) {
                    base.exit_fullscreen();
                }
            }, false);
            document.addEventListener("mozfullscreenchange", function() {
                if (!document.mozFullScreen) {
                    base.exit_fullscreen();
                }
            }, false);
            document.addEventListener("webkitfullscreenchange", function() {
                if (!document.webkitIsFullScreen) {
                    base.exit_fullscreen();
                }
            }, false);
            document.addEventListener("msfullscreenchange", function() {
                if (!document.msFullscreenElement) {
                    base.exit_fullscreen();
                }
            }, false);
            setInterval(function() {
                if (base.info.mobile) {
                    return;
                }
                base.info.idle_time += 500;
                if (base.info.fullscreen && base.info.idle_time > 2000) {
                    base.info.idle_controls_hidden = true;
                    base.hide_controls(true);
                }
            }, 500);
            base.$el.mousemove(function(e) {
                base.info.idle_time = 0;
                if (base.info.idle_controls_hidden && base.info.fullscreen) {
                    base.info.idle_controls_hidden = false;
                    base.show_controls();
                }
            });
            base.$el.keypress(function(e) {
                base.info.idle_time = 0;
                if (base.info.idle_controls_hidden && base.info.fullscreen) {
                    base.info.idle_controls_hidden = false;
                    base.show_controls();
                }
            });
            if (base.info.touch) {
                base.$el.addClass('wojo-touch');
            }
            setTimeout(function() {
                base.info.alternative_api_ready_check = true;
            }, 1000);
        };
        base.display_error = function(message, remove_player) {
            var $error = base.$el.find('.wojo-error').html('<i class="icon warning sign"></i>' + message).slideDown();
            if ($error.length === 0) {
                alert(message);
            }
            if (remove_player === true) {
                base.$el.find('.wojo-video').remove();
                base.$el.find('.wojo-container, .wojo-hp');
            }
        };
        base.remove_next_page = function() {
            base.info.next_page_token = false;
            base.$el.find('.wojo-next-page').remove();
            base.$el.find('.wojo-hp-next-page').remove();
            base.$el.find('.wojo-hp-videos').css('width', (base.playlist_count) * 160);
        };
        base.get_playlist_next = function() {
            if (base.info.next_page_token === false) {
                base.remove_next_page();
                return;
            }
            base.$el.find('.wojo-next-page').html('<i class="icon spinner circles"></i>');
            base.get_playlist(base.info.next_page_token, base.options.playlist);
        };
        base.get_playlist = function(pageToken, playlist) {
            if (typeof(pageToken) === typeof(undefined) || pageToken === false) {
                pageToken = false;
                through_pagination = false;
            } else {
                through_pagination = true;
            }
            var url = '//googleapis.com/youtube/v3/playlistItems?part=snippet,status&maxResults=' + base.options.max_results + '&playlistId=' + playlist + '&key=' + base.options.api_key;
            if (through_pagination === true) {
                url += '&pageToken=' + pageToken;
            }
            var r = $.getJSON(url, function(yt) {
                if (typeof(yt.items) !== 'undefined') {
                    if (yt.items.length === 0) {
                        base.display_error('This playlist is empty.', true);
                    }
                    var filtered_items = base.create_playlist(through_pagination, yt.items, yt.items.length);
                    base.playlist_items = base.playlist_items.concat(filtered_items.items);
                    base.playlist_count += filtered_items.count;
                    if (base.options.pagination === true) {
                        if (typeof(yt.nextPageToken) === typeof(undefined)) {
                            base.remove_next_page();
                        } else {
                            base.info.next_page_token = yt.nextPageToken;
                            base.$el.find('.wojo-next-page').html('<i class="icon plus"></i>' + base.options.load_more_text).show();
                        }
                    } else {
                        base.info.next_page_token = false;
                    }
                    if (base.playlist_count < 2 && !through_pagination && base.info.next_page_token === false) {
                        base.hide_playlist(true);
                        base.options.show_playlist = false;
                        base.options.playlist_toggle_control = false;
                        base.$controls.playlist_toggle.hide();
                        base.options.fwd_bck_control = false;
                        base.options_copy.fwd_bck_control = false;
                        base.$controls.forward.hide();
                        base.$controls.backward.hide();
                        base.resize();
                        if (base.playlist_count === 0) {
                            base.display_error('This playlist is empty.', true);
                        }
                    }
                } else {
                    base.display_error('An error occured while retrieving the playlist.', true);
                }
            });
            r.fail(function(data) {
                var error = 'An error occured while retrieving the playlist.';
                if (typeof(data.responseText) !== typeof(undefined)) {
                    var message = $.parseJSON(data.responseText);
                    if (message.error.code === '404') {
                        error = 'The playlist was not found.';
                    } else if (message.error.code === '403') {
                        error = message.error.message;
                    } else if (message.error.code === '400') {
                        error = 'The API key you have entered is invalid.';
                    } else {
                        error = 'An error occured while retrieving the playlist.<br /><em>' + message.error.message + '</em>';
                    }
                }
                base.display_error(error, true);
                base.hide_playlist(true);
            });
        };
        base.get_channel = function(type, source) {
            var url = '';
            if (type === 'user') {
                url = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&maxResults=' + base.options.max_results + '&forUsername=' + encodeURIComponent(source) + '&key=' + base.api_key;
            } else {
                url = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&maxResults=' + base.options.max_results + '&id=' + source + '&key=' + base.api_key;
            }
            $.getJSON(url, function(yt) {
                if (typeof(yt.items) !== undefined && yt.items.length === 1) {
                    var upload_playlist = yt.items[0].contentDetails.relatedPlaylists.uploads;
                    base.options.playlist = upload_playlist;
                    base.get_playlist(false, base.options.playlist);
                } else {
                    base.display_error('An error occured while retrieving the channel/user.', true);
                }
            });
        };
        base.get_videos = function(videos) {
            var vid_list = '',
                l = videos.length;
            for (var i = 0; i < l; i++) {
                if (i !== l - 1) {
                    vid_list += videos[i] + ',';
                } else {
                    vid_list += videos[i];
                }
            }
            var url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,status&maxResults=' + base.options.max_results + '&id=' + vid_list + '&key=' + base.api_key;
            $.getJSON(url, function(yt) {
                if (typeof(yt.items) !== 'undefined') {
                    for (var i = 0; i < yt.items.length; i++) {
                        yt.items[i].snippet.resourceId = {
                            videoId: yt.items[i].id
                        };
                    }
                    var filtered_items = base.create_playlist(false, yt.items, yt.items.length);
                    base.playlist_items = base.playlist_items.concat(filtered_items.items);
                    base.playlist_count += filtered_items.count;
                    if (base.playlist_count < 2) {
                        base.hide_playlist(true);
                        base.options.show_playlist = false;
                        base.options.playlist_toggle_control = false;
                        base.$controls.playlist_toggle.hide();
                        base.options.fwd_bck_control = false;
                        base.options_copy.fwd_bck_control = false;
                        base.$controls.forward.hide();
                        base.$controls.backward.hide();
                        base.resize();
                        if (base.playlist_count === 0) {
                            base.display_error('This playlist is empty, or the video\'s were not found.', true);
                        }
                    }
                } else {
                    base.display_error('An error occured while retrieving the video(s).', true);
                }
            });
            return;
        };
        base.init_playlist = function() {
            if (base.type === 'playlist') {
                base.get_playlist(false, base.options.playlist);
                return;
            }
            if (base.type === 'channel') {
                base.get_channel('channel', base.options.channel);
                return;
            }
            if (base.type === 'user') {
                base.get_channel('user', base.options.user);
                return;
            }
            if (base.type === 'videos') {
                base.get_videos(base.options.videos);
                return;
            }
        };
        base.create_playlist = function(through_pagination, items, count) {
            if (!through_pagination) {
                base.create_youtube_element();
            }
            var i = 0;
            while (typeof(items[i]) !== 'undefined') {
                if (items[i].status.privacyStatus === 'private') {
                    items.splice(i, 1);
                    count--;
                    continue;
                }
                if (typeof(items[i].snippet.thumbnails) === typeof(undefined)) {
                    items.splice(i, 1);
                    count--;
                    continue;
                }
                i++;
            }
            base.options.on_done_loading(items);
            for (base.info.playlist_i; base.info.playlist_i < base.playlist_count + count; base.info.playlist_i++) {
                var video = items[base.info.playlist_i - base.playlist_count],
                    img_src = '';
                if (typeof(video.snippet.thumbnails.medium) !== 'undefined' && video.snippet.thumbnails.medium.width / video.snippet.thumbnails.medium.height == 16 / 9) {
                    img_src = video.snippet.thumbnails.medium.url;
                } else if (typeof(video.snippet.thumbnails.medium) !== 'undefined') {
                    img_src = video.snippet.thumbnails.medium.url;
                } else if (typeof(video.snippet.thumbnails.high) !== 'undefined') {
                    img_src = video.snippet.thumbnails.high.url;
                } else if (typeof(video.snippet.thumbnails.default) !== 'undefined') {
                    img_src = video.snippet.thumbnails.default.url;
                }
                var title = video.snippet.title;
                if (video.snippet.title.length > 85) {
                    video.snippet.title = video.snippet.title.substr(0, 85) + '...';
                }
                if (video.snippet.channelTitle.length > 20) {
                    video.snippet.channelTitle = video.snippet.channelTitle.substr(0, 20) + '...';
                }
                var $video = $('<div class="wojo-playlist-video" data-playing="0" data-index="' + base.info.playlist_i + '"><img src="' + img_src + '" width="200" /><div class="wojo-playlist-overlay"><div class="wojo-playlist-title">' + video.snippet.title + '</div><div class="wojo-playlist-channel">' + video.snippet.channelTitle + '</div></div><div class="wojo-playlist-current"><i class="icon small play"></i><span>' + base.options.now_playing_text + '</span></div></div>');
                $video.click(function(e) {
                    e.preventDefault();
                    if (!base.options.show_controls_on_play) {
                        base.hide_controls();
                    }
                    base.play_video(parseFloat($(this).attr('data-index')));
                });
                if (base.options.show_channel_in_playlist === false) {
                    $video.find('.wojo-playlist-channel').remove();
                }
                $video.insertBefore(base.$el.find('.wojo-playlist .wojo-next-page'));
                base.$el.find('.wojo-playlist, .wojo-hp').css('background-image', 'none');
                var video_title = video.snippet.title;
                if (video_title.length > 45) {
                    video_title = video.snippet.title.substring(0, 45) + '...';
                }
                var $video_hp = $('<div class="wojo-hp-video" data-playing="0" data-index="' + base.info.playlist_i + '"><img src="' + img_src + '" width="200" /><div class="wojo-hp-overlay"><div class="wojo-hp-title">' + video_title + '</div><div class="wojo-hp-channel">' + video.snippet.channelTitle + '</div></div><div class="wojo-hp-current"><i class="icon play"></i><span>' + base.options.now_playing_text + '</span></div></div>');
                $video_hp.click(function(e) {
                    e.preventDefault();
                    if (!base.options.show_controls_on_play) {
                        base.hide_controls();
                    }
                    base.play_video(parseFloat($(this).attr('data-index')));
                });
                if (base.options.show_channel_in_playlist === false) {
                    $video_hp.find('.wojo-hp-channel').remove();
                }
                $video_hp.insertBefore(base.$el.find('.wojo-hp .wojo-hp-next-page'));
            }
            base.$el.find('.wojo-hp-videos').css('width', (base.info.playlist_i) * 160 + 50);
            if (through_pagination === false) {

                //base.$el.find('.wojo-playlist').customScroll();
                //base.$el.find('.wojo-hp').customScroll();
                base.resize(false, true);
            }
            if (through_pagination === true) {
                setTimeout(function() {
                    base.update_scroll_position(false, Math.floor(base.info.playlist_width / 16 * 9) * (base.playlist_count - count));
                }, 10);
            }
            return {
                'items': items,
                'count': count,
            };
        };
        base.check_youtube_api_ready = function() {
            if (!base.info.alternative_api_ready_check) {
                if (!$('body').hasClass('wojo-youtube-iframe-ready')) {
                    return false;
                }
            } else {
                if (typeof(YT) !== typeof({})) {
                    return false;
                }
                if (YT.loaded === 0) {
                    return false;
                }
            }
            base.$el.find('.wojo-container').removeClass('loading');
            return true;
        };
        base.create_youtube_element = function() {
            if (!base.check_youtube_api_ready()) {
                setTimeout(base.create_youtube_element, 10);
                return;
            }
            if (base.info.youtube_loaded) {
                return;
            }
            base.info.youtube_loaded = true;
            var vars = {
                'controls': 0,
                'showinfo': 0,
                'fullscreen': 0,
                'iv_load_policy': base.options.show_annotations ? 1 : 3,
                'fs': 0,
                'wmode': 'opaque'
            };
            if (base.options.force_hd) {
                vars.vq = 'hd720';
            }
            vars.modestbranding = 1;
            for (var i in base.options.player_vars) {
				if(base.options.player_vars.hasOwnProperty(i)) {
                   vars[i] = base.options.player_vars[i];
				}
            }
            window.YTConfig = {
                'host': 'https://www.youtube.com'
            };
            base.youtube = new YT.Player(base.id + '_yt', {
                playerVars: vars,
                events: {
                    'onReady': base.youtube_ready,
                    'onStateChange': base.youtube_state_change
                }
            });
        };
        base.youtube_ready = function() {
            setInterval(base.youtube_player_updates, 500);
            if (base.playlist_count === 0) {
                return;
            }
            base.play_video(base.options.first_video, !base.options.autoplay, true);
            if (base.options.volume !== false) {
                base.update_volume(0, base.options.volume);
            }
            base.$el.find('.wojo-container').hover(function() {
                base.info.hover = true;
                base.show_controls();
            }, function() {
                base.info.hover = false;
                var s = base.youtube.getPlayerState();
                if (base.options.show_controls_on_pause && (s == -1 || s == 0 || s == 2 || s == 5)) {} else if (base.options.show_controls_on_play) {} else {
                    base.hide_controls();
                }
                base.hide_share();
            });
        };
        base.youtube_player_updates = function() {
            base.info.current_time = base.youtube.getCurrentTime();
            if (!base.youtube.getCurrentTime()) {
                base.info.current_time = 0;
            }
            base.info.duration = base.youtube.getDuration();
            if (!base.info.duration) {
                return;
            }
            if (base.info.current_time == base.info.previous_time) {
                return;
            }
            base.info.previous_time = base.info.current_time;
            if (base.options.time_incator == 'full') {
                base.$controls.time.html(base.format_time(base.info.current_time) + ' / ' + base.format_time(base.info.duration));
            } else {
                base.$controls.time.html(base.format_time(base.info.current_time));
            }
            var s = Math.round(base.info.current_time);
            if (s == 0) {
                base.$controls.youtube.attr('href', base.$controls.youtube.attr('data-href'));
            } else {
                base.$controls.youtube.attr('href', base.$controls.youtube.attr('data-href') + '#t=' + s);
            }
            base.info.current_video_url = base.$controls.youtube.attr('data-href');
            var perc = 100 * base.info.current_time / base.info.duration;
            base.$controls.time_bar_time.css('width', perc + '%');
            base.$controls.time_bar_buffer.css('width', base.youtube.getVideoLoadedFraction() * 100 + '%');
            base.options.on_time_update(base.info.current_time);
        };
        base.youtube_state_change = function(e) {
            var state = e.data;
            if (state === 0) {
                if (base.options.continuous) {
                    base.forward();
                } else {
                    base.play_video(base.info.index, true);
                    base.$controls.play.removeClass('play').removeClass('pause').addClass('undo');
                    base.show_controls();
                }
            } else if (state === 1 || state === 3) {
                base.$controls.play.removeClass('play').addClass('pause').removeClass('undo');
            } else if (state === 2) {
                base.$controls.play.addClass('play').removeClass('pause').removeClass('undo');
            }
            if (!base.info.first_play && state !== -1 && state !== 5) {
                base.info.first_play = true;
            }
            base.youtube_player_updates();
            base.options.on_state_change(state);
        };
        base.create_player_element = function() {
            base.$el.css('width', '100%').addClass('ytplayer').html('<div class="wojo-container loading"><div class="wojo-autoposter"><div class="wojo-autoposter-icon"></div></div><div class="wojo-video-container"><div class="wojo-video" id="' + base.id + '_yt"></div><div class="wojo-error"></div></div></div><div class="wojo-playlist"><div class="wojo-next-page"><i class="wojo-icon-plus"></i>Load More</div></div><div class="wojo-hp"><div class="wojo-hp-videos"><div class="wojo-hp-next-page"><i class="icon plus"></i></div></div></div>');
            base.$el.find('.wojo-video-container').click(function(e) {
                base.play_pause();
            });
            if (base.options.playlist_type === "horizontal") {
                base.$el.find('.wojo-playlist').remove();
            }
            base.$el.find('.wojo-next-page, .wojo-hp-next-page').click(function(e) {
                base.get_playlist_next();
            });
            base.$el.find('.wojo-autoposter').click(function(e) {
                e.preventDefault();
                base.play();
            });
        };
        base.create_controls = function() {
            var $controls = $('<div class="wojo-controls"></div>');
            $controls.html('<div class="wojo-controls-wrapper"><a href="#" class="wojo-play"><i class="icon play"></i></a><div class="wojo-time">00:00 / 00:00</div><div class="wojo-bar"><div class="wojo-bar-buffer"></div><div class="wojo-bar-time"></div></div><div class="wojo-volume"><a href="#" class="wojo-volume-icon" title="Toggle Mute"><i class="icon volume"></i></a><div class="wojo-volume-bar"><div class="wojo-volume-amount"></div></div></div><a href="#" class="wojo-share" title="Share"><i class="icon share"></i></a><a href="#" target="_blank" class="wojo-youtube" title="Open in YouTube"><i class="icon youtube"></i></a><a href="#" class="wojo-backward" title="Previous Video"><i class="icon backward"></i></a><a href="#" class="wojo-forward" title="Forward Video"><i class="icon forward"></i></a><a href="#" class="wojo-playlist-toggle" title="Toggle Playlist"><i class="icon align justify"></i></a><a href="#" class="wojo-fullscreen" title="Toggle Fullscreen"><i class="icon expand"></i></a></div>');
            base.$controls.play = $controls.find('.wojo-play').children();
            base.$controls.time = $controls.find('.wojo-time');
            base.$controls.time_bar = $controls.find('.wojo-bar');
            base.$controls.time_bar_buffer = $controls.find('.wojo-bar-buffer');
            base.$controls.time_bar_time = $controls.find('.wojo-bar-time');
            base.$controls.volume = $controls.find('.wojo-volume');
            base.$controls.volume_icon = $controls.find('.wojo-volume-icon').children();
            base.$controls.volume_bar = $controls.find('.wojo-volume-bar');
            base.$controls.volume_amount = $controls.find('.wojo-volume-amount');
            base.$controls.share = $controls.find('.wojo-share');
            base.$controls.youtube = $controls.find('.wojo-youtube');
            base.$controls.forward = $controls.find('.wojo-forward');
            base.$controls.backward = $controls.find('.wojo-backward');
            base.$controls.playlist_toggle = $controls.find('.wojo-playlist-toggle').children();
            base.$controls.fullscreen = $controls.find('.wojo-fullscreen');
            if (!base.options.play_control) {
                base.$controls.play.hide();
            }
            if (!base.options.time_indicator) {
                base.$controls.time.hide();
            } else if (base.options.time_indicator === 'full') {
                base.$controls.time.addClass('wojo-full-time');
            }
            if (!base.options.volume_control) {
                base.$controls.volume.hide();
            }
            if (!base.options.share_control) {
                base.$controls.share.hide();
            }
            if (!base.options.youtube_link_control) {
                base.$controls.youtube.hide();
            }
            if (!base.options.fwd_bck_control) {
                base.$controls.backward.hide();
                base.$controls.forward.hide();
            }
            if (!base.options.fullscreen_control) {
                base.$controls.fullscreen.hide();
            }
            if (!base.options.playlist_toggle_control) {
                base.$controls.playlist_toggle.hide();
            }
            $controls.appendTo(this.$el.find('.wojo-container'));
        };
        base.create_title = function() {
            base.$title = $('<div class="wojo-title"></div>');
            base.$title.html('<div class="wojo-title-wrapper"></div>');
            base.$title.appendTo(base.$el.find('.wojo-container'));
        };
        base.update_title = function(title, channel, channel_link) {
            if (base.options.show_channel_in_title) {
                base.$title.find('div.wojo-title-wrapper').html('<a href="' + channel_link + '" target="_blank" class="wojo-subtitle">' + channel + '</a>' + title);
            } else {
                base.$title.find('div.wojo-title-wrapper').html(title);
            }
        };
        base.create_overlays = function() {
            base.$social = $('<div class="wojo-social" data-show="0"><a href="#" class="wojo-social-button wojo-social-google"><i class="icon google plus"></i></a><a href="#" class="wojo-social-button wojo-social-twitter"><i class="icon twitter"></i></a><a href="#" class="wojo-social-button wojo-social-facebook"><i class="icon facebook"></i></a></div>').appendTo(base.$el.find('.wojo-container'));
            base.$social.find('.wojo-social-facebook').click(function(e) {
                e.preventDefault();
                base.share_facebook();
            });
            base.$social.find('.wojo-social-twitter').click(function(e) {
                e.preventDefault();
                base.share_twitter();
            });
            base.$social.find('.wojo-social-google').click(function(e) {
                e.preventDefault();
                base.share_google();
            });
        };
        base.share_link = function() {}, base.share_facebook = function() {
            window.open('//facebook.com/sharer/sharer.php?u=' + base.share_url(), 'Share on Facebook', "height=300,width=600");
        }, base.share_twitter = function() {
            window.open('//twitter.com/home?status=' + base.share_url(), 'Share on Twitter', "height=300,width=600");
        }, base.share_google = function() {
            window.open('//plus.google.com/share?url=' + base.share_url(), 'Share on Google+', "height=300,width=600");
        }, base.bind_controls = function() {
            base.$controls.play.click(function(e) {
                e.preventDefault();
                base.play_pause();
            });
            base.$controls.volume_icon.click(function(e) {
                e.preventDefault();
                if (base.youtube.isMuted()) {
                    if (base.info.volume === 0) {
                        base.info.volume = base.options.volume;
                    }
                    base.update_volume(0, base.info.volume);
                } else {
                    var previous_vol = base.youtube.getVolume() / 100;
                    base.update_volume(0, 0);
                    base.info.volume = previous_vol;
                }
            });
            base.$controls.share.click(function(e) {
                e.preventDefault();
                base.toggle_share();
            });
            base.$controls.youtube.click(function(e) {
                base.pause();
            });
            base.$controls.backward.click(function(e) {
                e.preventDefault();
                base.backward();
            });
            base.$controls.forward.click(function(e) {
                e.preventDefault();
                base.forward();
            });
            base.$controls.fullscreen.click(function(e) {
                e.preventDefault();
                if (base.info.fullscreen) {
                    base.exit_fullscreen(true);
                } else {
                    base.enter_fullscreen();
                }
            });
            base.$controls.playlist_toggle.click(function(e) {
                e.preventDefault();
                base.toggle_playlist();
            });
        };
        base.show_controls = function() {
            base.$title.stop().animate({
                'opacity': 1
            }, 250);
            base.$el.find('.wojo-controls').stop().animate({
                'bottom': 0,
                'opacity': 1,
            }, 250);
        };
        base.hide_controls = function(opacity) {
            if (typeof(opacity) !== 'undefined' && opacity == true) {
                base.$el.find('.wojo-controls').stop().animate({
                    'bottom': 0,
                    'opacity': 0,
                }, 250)
            } else {
                base.$el.find('.wojo-controls').stop().animate({
                    'bottom': -50
                }, 250);
            }
            if (base.info.ios) {
                return;
            }
            base.$title.stop().animate({
                'opacity': 0
            }, 250);
        };
        base.play_pause = function() {
            var state = parseInt(base.youtube.getPlayerState());
            if (state === 2) {
                base.play();
            } else if (state === 0) {
                base.youtube.seekTo(0);
                base.play();
            } else if (state === 5) {
                base.play();
            } else {
                base.pause();
            }
        };
        base.play = function() {
            base.youtube.playVideo();
            base.$el.find('.wojo-autoposter').hide();
            base.$controls.play.removeClass('play').addClass('pause').removeClass('undo');
        };
        base.pause = function() {
            base.youtube.pauseVideo();
            base.$controls.play.addClass('play').removeClass('pause').removeClass('undo');
        };
        base.stop = function() {
            base.pause();
            base.youtube.stopVideo();
        };
        base.forward = function() {
            base.info.index++;
            if (base.info.index >= base.playlist_count) {
                base.info.index = 0;
            }
            base.play_video(base.info.index);
        };
        base.backward = function() {
            base.info.index--;
            if (base.info.index < 0) {
                base.info.index = base.playlist_count - 1;
            }
            base.play_video(base.info.index);
        };
        base.play_video = function(index, cue, fast_scroll) {
            var video = base.playlist_items[index];
            if (video == undefined) {
                return;
            }
            if (typeof(fast_scroll) === typeof(undefined)) {
                fast_scroll = false;
            }
            if (base.info.mobile && !base.info.first_play) {
                cue = true;
            }
            var title = video.snippet.title,
                channel = video.snippet.channelTitle,
                channel_link = '//youtube.com/channel/' + video.snippet.channelId,
                video_id = video.snippet.resourceId.videoId,
                video_link = '//youtube.com/watch?v=' + video_id;
            base.update_title(title, channel, channel_link);
            if (typeof(cue) == 'undefined' || cue == false) {
                base.youtube.loadVideoById(video_id);
            } else {
                base.youtube.cueVideoById(video_id);
            }
            base.$controls.youtube.attr('href', video_link).attr('data-href', video_link);
            base.info.current_video_url = video_link;
            base.$el.find('.wojo-playlist-video').attr('data-playing', '0');
            base.$el.find('.wojo-playlist-video[data-index=' + index + ']').attr('data-playing', '1');
            base.$el.find('.wojo-hp-video').attr('data-playing', '0');
            base.$el.find('.wojo-hp-video[data-index=' + index + ']').attr('data-playing', '1');
            if (base.options.time_indicator == 'full') {
                base.$controls.time.html('00:00 / 00:00');
            } else {
                base.$controls.time.html('00:00');
            }
            base.$controls.time_bar_time.css('width', 0);
            base.$controls.time_bar_buffer.css('width', 0);
            base.info.index = index;
            base.update_scroll_position(fast_scroll);
            if (cue === true && !base.info.mobile) {
                var img_src = false;
                if (typeof(video.snippet.thumbnails.maxres) !== 'undefined') {
                    img_src = video.snippet.thumbnails.maxres.url;
                } else if (typeof(video.snippet.thumbnails.high) !== 'undefined') {
                    img_src = video.snippet.thumbnails.high.url;
                } else if (typeof(video.snippet.thumbnails.medium) !== 'undefined') {
                    img_src = video.snippet.thumbnails.medium.url;
                } else if (typeof(video.snippet.thumbnails.standard) !== 'undefined') {
                    img_src = video.snippet.thumbnails.standard.url;
                } else if (typeof(video.snippet.thumbnails.default) !== 'undefined') {
                    img_src = video.snippet.thumbnails.default.url;
                }
                if (img_src !== false) {
                    base.$el.find('.wojo-autoposter').css('background-image', 'url("' + img_src + '")').show();
                }
            } else {
                base.$el.find('.wojo-autoposter').hide();
            }
            base.options.on_load(video.snippet);
        };
        base.update_scroll_position = function(fast, force_scroll) {
            if (base.options.playlist_type === 'horizontal') {
                var scroll_to = 160 * base.info.index;
                if (typeof(force_scroll) !== typeof(undefined)) {
                    scroll_to = force_scroll;
                }
                if (fast == true) {
                    base.$el.find('.wojo-hp').scrollLeft(scroll_to);
                } else {
                    base.$el.find('.wojo-hp').stop().animate({
                        scrollLeft: scroll_to
                    }, 500, function() {});
                }
                return;
            }
            var scroll_to = Math.floor(base.info.playlist_width / 16 * 9) * base.info.index;
            if (typeof(force_scroll) !== typeof(undefined)) {
                scroll_to = force_scroll;
            }
            if (scroll_to < 0) {
                scroll_to = 0;
            }
            var playlist_height = base.$el.find('.wojo-playlist').innerHeight(),
                item_heights = Math.floor(base.info.playlist_width / 16 * 9) * base.playlist_count;
            var max_scroll = item_heights - playlist_height;
            if (base.info.next_page_token) {
                max_scroll += 50;
            }
            if (scroll_to > max_scroll) {
                scroll_to = max_scroll;
            }
            if (fast == true) {
                base.$el.find('.wojo-playlist').scrollTop(scroll_to);
            } else {
                base.$el.find('.wojo-playlist').stop().animate({
                    scrollTop: scroll_to
                }, 500, function() {});
            }
        };
        base.toggle_fullscreen = function() {
            if (base.info.fullscreen) {
                base.exit_fullscreen(true);
            } else {
                base.enter_fullscreen();
            }
        };
        base.enter_fullscreen = function() {
            if (base.info.mobile) {}
            var requestFullScreen = base.$el.find('.wojo-container')[0].webkitRequestFullScreen || base.$el.find('.wojo-container')[0].requestFullScreen || base.$el.find('.wojo-container')[0].mozRequestFullScreen;
            if (!requestFullScreen) {
                return;
            }
            var w = $(window).width(),
                h = $(window).height();
            base.info.fullscreen = true;
            base.$el.find('.wojo-container, .wojo-container iframe').css({
                'width': '100%',
                'height': '100%'
            });
            base.youtube.setSize(w, h);
            requestFullScreen.bind(base.$el.find('.wojo-container')[0])();
        };
        base.exit_fullscreen = function(exit) {
            if (typeof(exit) !== 'undefined' && exit) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
            base.info.fullscreen = false;
            base.resize();
        };
        base.toggle_playlist = function() {
            if (base.options.playlist_type === 'horizontal') {
                if (base.info.horizontal_playlist_shown) {
                    base.hide_horizontal_playlist()
                } else {
                    base.show_horizontal_playlist()
                }
            } else {
                if (base.info.playlist_shown) {
                    base.hide_playlist(false)
                } else {
                    base.show_playlist(false)
                }
                if (base.options.show_playlist !== 'auto') {
                    base.resize()
                }
            }
        };
        base.show_playlist = function(fast, resize) {
            if (base.options.playlist_type == 'horizontal') {
                return
            }
            if (typeof(resize) == typeof(undefined)) {
                resize = true
            }
            fast = true;
            if (base.info.playlist_animating) {
                return
            }
            base.info.playlist_animating = true;
            var $p = base.$el.find('.wojo-playlist'),
                w = 0;
            base.$el.find('.wojo-icon-list').removeClass('right').addClass('justify');
            if (fast) {
                $p.css('width', base.info.playlist_width);
                base.info.playlist_shown = true;
                base.info.playlist_animating = false;
                if (resize) {
                    base.resize(true)
                }
                return
            }
        };
        base.hide_playlist = function(fast, resize) {
            if (typeof(resize) == typeof(undefined)) {
                resize = true
            }
            fast = true;
            if (base.info.playlist_animating) {
                return
            }
            base.info.playlist_animating = true;
            var $p = base.$el.find('.wojo-playlist');
            if (fast) {
                $p.css('width', 0);
                base.info.playlist_shown = false;
                base.info.playlist_animating = false;
                if (resize) {
                    base.resize(true)
                }
                return
            }
        };
        base.show_horizontal_playlist = function() {
            base.info.horizontal_playlist_shown = true;
            base.$el.find('.wojo-hp').show()
        };
        base.hide_horizontal_playlist = function() {
            base.info.horizontal_playlist_shown = false;
            base.$el.find('.wojo-hp').hide()
        };
        base.set_playlist_width = function(width) {
            if (base.info.playlist_shown) {
                base.info.playlist_width = width
            }
            var height = Math.floor(width / 16 * 9);
            base.$el.find('.wojo-playlist').css({
                'width': width
            }).find('.wojo-playlist-video').css({
                'width': width,
                'height': height
            });
            base.$el.find('.wojo-playlist .wojo-playlist-current').css('width', width - 20);
            if (width <= 100) {
                base.$el.find('.wojo-playlist').addClass('wojo-playlist-simple');
                base.$el.find('.wojo-playlist .wojo-playlist-current').css('width', 10)
            } else {
                base.$el.find('.wojo-playlist').removeClass('wojo-playlist-simple')
            }
        };
        base.resize = function(avoid_playlist, force_update) {
            if (typeof(avoid_playlist) == typeof(undefined) || typeof(avoid_playlist) == typeof({})) {
                avoid_playlist = false
            }
            var width = base.$el.innerWidth();
            if (base.options.show_playlist == 'auto' && avoid_playlist == false) {
                if (width < 660 && (force_update == true || base.info.playlist_width == 200)) {
                    base.set_playlist_width(100);
                    base.update_scroll_position(true)
                }
                if (width < 500 && (force_update == true || base.info.playlist_shown == true)) {
                    base.hide_playlist(false, false);
                    base.update_scroll_position(true)
                }
                if (width >= 500 && (force_update == true || base.info.playlist_shown == false)) {
                    base.show_playlist(false, false);
                    base.update_scroll_position(true)
                }
                if (width >= 660 && (force_update == true || base.info.playlist_width == 100)) {
                    base.set_playlist_width(200);
                    base.update_scroll_position(true)
                }
            } else if (avoid_playlist == false) {
                force_update = true;
                if (width < 660 && (force_update == true || base.info.playlist_width == 200)) {
                    base.set_playlist_width(100);
                    base.update_scroll_position(true)
                }
                if (width >= 660 && (force_update == true || base.info.playlist_width == 100)) {
                    base.set_playlist_width(200);
                    base.update_scroll_position(true)
                }
                if (base.info.playlist_shown == false) {
                    base.hide_playlist(true, false)
                }
            }
            var controls_width = width - (base.info.playlist_shown ? base.info.playlist_width : 0),
                height = controls_width / 16 * 9;
            if (base.info.fullscreen) {
                width = $(window).width();
                controls_width = width;
                height = $(window).height()
            }
            base.$el.find('.wojo-container, .wojo-playlist, .wojo-video').css('height', height);
            base.$el.find('.wojo-container, .wojo-video').css('width', controls_width);
            base.info.width = controls_width;
            base.info.height = height;
            var bar_width = controls_width - 20;
            if (controls_width < 600) {
                if (base.options.time_indicator == 'full') {
                    base.options.time_indicator = true;
                    base.$controls.time.html(base.format_time(base.info.current_time));
                    base.$controls.time.removeClass('wojo-full-time')
                }
            }
            if (controls_width < 530) {
                base.options.fwd_bck_control = false;
                base.options.youtube_link_control = false;
                base.$controls.forward.hide();
                base.$controls.backward.hide();
                base.$controls.youtube.hide()
            }
            if (controls_width < 400) {
                base.options.volume_control = false;
                base.$controls.volume.hide()
            }
            if (controls_width < 300) {
                base.options.time_indicator = false;
                base.$controls.time.hide();
                base.options.share_control = false;
                base.$controls.share.hide()
            }
            if (controls_width >= 300 && (base.options_copy.time_indicator == true || base.options_copy.time_indicator == 'full')) {
                base.options.time_indicator = true;
                base.$controls.time.show()
            }
            if (controls_width >= 300 && base.options_copy.share_control == true) {
                base.options.share_control = true;
                base.$controls.share.show()
            }
            if (controls_width >= 400 && base.options_copy.volume_control == true) {
                base.options.volume_control = true;
                base.$controls.volume.show()
            }
            if (controls_width >= 530 && base.options_copy.fwd_bck_control == true) {
                base.options.fwd_bck_control = true;
                base.$controls.forward.show();
                base.$controls.backward.show()
            }
            if (controls_width >= 530 && base.options_copy.youtube_link_control == true) {
                base.options.youtube_link_control = true;
                base.$controls.youtube.show()
            }
            if (controls_width >= 600 && base.options_copy.time_indicator == 'full') {
                base.options.time_indicator = 'full';
                base.$controls.time.html(base.format_time(base.info.current_time) + ' / ' + base.format_time(base.info.duration));
                base.$controls.time.addClass('wojo-full-time')
            }
            if (base.options.play_control) {
                bar_width -= 30
            }
            if (base.options.time_indicator) {
                bar_width -= 58
            }
            if (base.options.time_indicator == 'full') {
                bar_width -= 40
            }
            if (base.options.volume_control) {
                bar_width -= 110
            }
            if (base.options.share_control) {
                bar_width -= 30
            }
            if (base.options.youtube_link_control) {
                bar_width -= 30
            }
            if (base.options.fwd_bck_control) {
                bar_width -= 60
            }
            if (base.options.fullscreen_control) {
                bar_width -= 30
            }
            if (base.options.playlist_toggle_control) {
                bar_width -= 30
            }
            bar_width -= 18;
            base.$controls.time_bar.css('width', bar_width)
        };
        base.init_time_slider = function() {
            base.$controls.time_bar.on('mousedown', function(e) {
                base.info.time_drag = true;
                base.update_time_slider(e.pageX)
            });
            $(document).on('mouseup', function(e) {
                if (base.info.time_drag) {
                    base.info.time_drag = false;
                    base.update_time_slider(e.pageX)
                }
            });
            $(document).on('mousemove', function(e) {
                if (base.info.time_drag) {
                    base.update_time_slider(e.pageX)
                }
            })
        };
        base.update_time_slider = function(x) {
            if (base.info.duration == 0) {
                return
            }
            var maxduration = base.info.duration;
            var position = x - base.$controls.time_bar.offset().left;
            var percentage = 100 * position / base.$controls.time_bar.width();
            if (percentage > 100) {
                percentage = 100
            }
            if (percentage < 0) {
                percentage = 0
            }
            base.$controls.time_bar_time.css('width', percentage + '%');
            base.youtube.seekTo(maxduration * percentage / 100);
            base.options.on_seek(maxduration * percentage / 100)
        };
        base.init_volume_slider = function() {
            base.$controls.volume_bar.on('mousedown', function(e) {
                base.info.volume_drag = true;
                base.$controls.volume_icon.removeClass('mute').addClass('volume');
                base.update_volume(e.pageX)
            });
            $(document).on('mouseup', function(e) {
                if (base.info.volume_drag) {
                    base.info.volume_drag = false;
                    base.update_volume(e.pageX)
                }
            });
            $(document).on('mousemove', function(e) {
                if (base.info.volume_drag) {
                    base.update_volume(e.pageX)
                }
            })
        };
        base.update_volume = function(x, vol) {
            var percentage;
            if (vol) {
                percentage = vol * 100
            } else {
                var position = x - base.$controls.volume_bar.offset().left;
                percentage = 100 * position / base.$controls.volume_bar.width()
            }
            if (percentage > 100) {
                percentage = 100
            }
            if (percentage < 0) {
                percentage = 0
            }
            base.$controls.volume_amount.css('width', percentage + '%');
            base.youtube.setVolume(percentage);
            if (percentage == 0) {
                base.youtube.mute()
            } else if (base.youtube.isMuted()) {
                base.youtube.unMute()
            }
            if (percentage == 0) {
                base.$controls.volume_icon.addClass('mute').removeClass('volume')
            } else {
                base.$controls.volume_icon.removeClass('mute').addClass('volume')
            }
            base.options.on_volume(percentage / 100)
        };
        base.toggle_share = function() {
            if (base.$social.attr('show') == '1') {
                base.hide_share()
            } else {
                base.show_share()
            }
        };
        base.show_share = function() {
            base.$social.attr('show', '1').stop().animate({
                right: 10
            }, 200)
        };
        base.hide_share = function() {
            base.$social.attr('show', '0').stop().animate({
                right: -140
            }, 200)
        };
        base.set_style = function() {
            var $s = $('<style />');
            var default_colors = {
                controls_bg: 'rgba(0,0,0,.75)',
                buttons: 'rgba(255,255,255,.5)',
                buttons_hover: 'rgba(255,255,255,1)',
                buttons_active: 'rgba(255,255,255,1)',
                time_text: '#FFFFFF',
                bar_bg: 'rgba(255,255,255,.5)',
                buffer: 'rgba(255,255,255,.25)',
                fill: '#FFFFFF',
                video_title: '#FFFFFF',
                video_channel: '#DFF76D',
                playlist_overlay: 'rgba(0,0,0,.5)',
                playlist_title: '#FFFFFF',
                playlist_channel: '#DFF76D',
                scrollbar: '#FFFFFF',
                scrollbar_bg: 'rgba(255,255,255,.50)',
            };
            for (key in base.options.colors) {
                default_colors[key] = base.options.colors[key]
            }
            $s.html('#' + base.id + '.ytplayer .wojo-controls{background:' + default_colors.controls_bg + '!important}#' + base.id + '.ytplayer .wojo-controls a{color:' + default_colors.buttons + '!important}#' + base.id + '.ytplayer .wojo-controls a:hover{color:' + default_colors.buttons_hover + '!important}#' + base.id + '.ytplayer .wojo-controls a:active{color:' + default_colors.buttons_active + '!important}#' + base.id + '.ytplayer .wojo-time{color:' + default_colors.time_text + '!important}#' + base.id + '.ytplayer .wojo-bar,#' + base.id + '.ytplayer .wojo-volume .wojo-volume-bar{background:' + default_colors.bar_bg + '!important}#' + base.id + '.ytplayer .wojo-bar .wojo-bar-buffer{background:' + default_colors.buffer + '!important}#' + base.id + '.ytplayer .wojo-bar .wojo-bar-time,#' + base.id + '.ytplayer .wojo-volume .wojo-volume-bar .wojo-volume-amount{background:' + default_colors.fill + '!important}#' + base.id + '.ytplayer .wojo-title-wrapper{color:' + default_colors.video_title + '!important}#' + base.id + '.ytplayer .wojo-title a.wojo-subtitle{border-color:' + default_colors.video_title + '!important}#' + base.id + '.ytplayer .wojo-title-wrapper a{color:' + default_colors.video_channel + '!important}#' + base.id + '.ytplayer .wojo-playlist-overlay,#' + base.id + '.ytplayer .wojo-hp-overlay,#' + base.id + '.ytplayer .wojo-playlist-current,#' + base.id + '.ytplayer .wojo-hp-current{background: ' + default_colors.playlist_overlay + ' !important;}#' + base.id + '.ytplayer .wojo-playlist-overlay .wojo-playlist-title,#' + base.id + '.ytplayer .wojo-hp-overlay .wojo-hp-title,#' + base.id + '.ytplayer .wojo-playlist-current,#' + base.id + '.ytplayer .wojo-hp-current{color: ' + default_colors.playlist_title + ' !important;}#' + base.id + '.ytplayer .wojo-playlist-overlay .wojo-playlist-channel,#' + base.id + '.ytplayer .wojo-hp-overlay .wojo-hp-channel {color: ' + default_colors.playlist_channel + ' !important;}#' + base.id + '.ytplayer .scrolltrack{background: ' + default_colors.scrollbar_bg + ' !important;width:6px;margin:0;}#' + base.id + '.ytplayer .scrollhandle{background: ' + default_colors.scrollbar + ';width:6px;}');
            $s.appendTo('body')
        };
        base.format_time = function(seconds) {
            var m = Math.floor(seconds / 60) < 10 ? "0" + Math.floor(seconds / 60) : Math.floor(seconds / 60),
                s = Math.floor(seconds - (m * 60)) < 10 ? "0" + Math.floor(seconds - (m * 60)) : Math.floor(seconds - (m * 60));
            return m + ":" + s
        };
        base.cut_text = function(n) {
            return function textCutter(i, text) {
                var short = text.substr(0, n);
                if (/^\S/.test(text.substr(n))) {
                    return short.replace(/\s+\S*$/, "")
                }
                return short
            }
        };
        base.share_url = function() {
            return base.info.current_video_url
        };
        base.detect_ie = function() {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf('MSIE ');
            var trident = ua.indexOf('Trident/');
            if (msie > 0) {
                return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10)
            }
            if (trident > 0) {
                var rv = ua.indexOf('rv:');
                return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10)
            }
            return false
        };
        base.detect_touch = function() {
            return !!('ontouchstart' in window) || !!('onmsgesturechange' in window)
        };
        base.init()
    };
    $.wojo_tube.defaultOptions = {
        playlist: false,
        channel: false,
        user: false,
        videos: false,
        api_key: '',
        max_results: 50,
        pagination: true,
        continuous: true,
        first_video: 0,
        show_playlist: 'auto',
        playlist_type: 'vertical',
        show_channel_in_playlist: true,
        show_channel_in_title: true,
        width: false,
        show_annotations: false,
        now_playing_text: 'Now Playing',
        load_more_text: 'Load More',
        force_hd: false,
        autoplay: false,
        play_control: true,
        time_indicator: 'full',
        volume_control: true,
        share_control: true,
        fwd_bck_control: true,
        youtube_link_control: true,
        fullscreen_control: true,
        playlist_toggle_control: true,
        volume: false,
        show_controls_on_load: true,
        show_controls_on_pause: true,
        show_controls_on_play: false,
        player_vars: {},
        colors: {},
        on_load: function(snippet) {},
        on_done_loading: function(videos) {},
        on_state_change: function(state) {},
        on_seek: function(seconds) {},
        on_volume: function(volume) {},
        on_time_update: function(seconds) {},
    };
    $.fn.wojo_tube = function(options) {
        return this.each(function() {
            (new $.wojo_tube(this, options))
        })
    };
    $.fn.wojo_tube_play = function() {
        return this.each(function() {
            (new $.wojo_tube_play(this))
        })
    };
    $.wojo_tube_play = function(el) {
        var $el = $(el),
            base = $el.data("wojo_tube");
        base.play()
    };
    $.fn.wojo_tube_pause = function() {
        return this.each(function() {
            (new $.wojo_tube_pause(this))
        })
    };
    $.wojo_tube_pause = function(el) {
        var $el = $(el),
            base = $el.data("wojo_tube");
        base.pause()
    };
    $.fn.wojo_tube_stop = function() {
        return this.each(function() {
            (new $.wojo_tube_stop(this))
        })
    };
    $.wojo_tube_stop = function(el) {
        var $el = $(el),
            base = $el.data("wojo_tube");
        base.stop()
    };
    $.fn.wojo_tube_seek = function(t) {
        return this.each(function() {
            (new $.wojo_tube_seek(this, t))
        })
    };
    $.wojo_tube_seek = function(el, seconds) {
        var $el = $(el),
            base = $el.data("wojo_tube");
        var maxduration = base.info.duration,
            percentage = seconds / maxduration * 100;
        base.$controls.time_bar_time.css('width', percentage + '%');
        base.youtube.seekTo(maxduration * percentage / 100);
        base.options.on_seek(seconds)
    };
    $.fn.wojo_tube_load = function(index) {
        return this.each(function() {
            (new $.wojo_tube_load(this, index))
        })
    };
    $.wojo_tube_load = function(el, index) {
        var $el = $(el),
            base = $el.data("wojo_tube");
        base.play_video(index)
    };
    $.fn.wojo_tube_volume = function(volume) {
        return this.each(function() {
            (new $.wojo_tube_volume(this, volume))
        })
    };
    $.wojo_tube_volume = function(el, volume) {
        var $el = $(el),
            base = $el.data("wojo_tube");
        base.update_volume(0, volume)
    };
    $.fn.youtube_show_controls = function() {
        return this.each(function() {
            (new $.youtube_show_controls(this))
        })
    };
    $.youtube_show_controls = function(el) {
        var $el = $(el),
            base = $el.data("wojo_tube");
        base.show_controls()
    };
})(jQuery);