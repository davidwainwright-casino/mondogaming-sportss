(function($, window, document, undefined) {
    "use strict";
    var pluginName = "Poll",
        defaults = {
            url: "value"
        };

    function Poll(element, options) {
        this.element = element;

        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    $.extend(Poll.prototype, {
        init: function() {
            this.bind();
        },
        bind: function() {
            var base = this;
            $(this.element).on('click', '.dovote', function() {
                var parent = $(this).parent();
                $(this).addClass('active');
                $(parent).find('.dovote').not(this).removeClass('active');
            });

            $(this.element).on('click', '.pollVote', function() {
                var active = $(base.element).find('.dovote.active').data('poll');
                var total = $(base.element).find('[data-total-id=' + active.oid + ']');
                if (typeof active !== "undefined") {
                    $(base.element).addClass('loading');
                    $.post(base.settings.url, {
                        action: "vote",
                        id: active.oid
                    }, function(json) {
                        if (json.type === "success") {
                            $(total).text(parseInt(active.total) + 1);
                            $(".pollDisplay", base.element).transition({
                                animation: 'scale out',
                                duration: '.5s',
                                onComplete: function() {
                                    $(".pollResult", base.element).transition({
                                        animation: 'scale in',
                                        duration: '.2s',
                                        onComplete: function() {
                                            $(".goFront, .goBack", base.element).hide();
                                        }
                                    });
                                }
                            });

                        }
                        $(base.element).removeClass('loading');
                    }, "json");
                }
            });

            $(this.element).on('click', '.pollView, .pollBack', function() {
                if ($(this).hasClass('pollView')) {
                    $(".pollDisplay", base.element).transition({
                        animation: 'scale out',
                        duration: '.5s',
                        onComplete: function() {
                            $(".pollResult", base.element).transition({
                                animation: 'scale in',
                                duration: '.2s',
                                onComplete: function() {
                                    $(".goFront", base.element).hide();
                                    $(".goBack", base.element).show();
                                }
                            });
                        }
                    });
                } else {
                    $(".pollResult", base.element).transition({
                        animation: 'scale out',
                        duration: '.5s',
                        onComplete: function() {
                            $(".pollDisplay", base.element).transition({
                                animation: 'scale in',
                                duration: '.2s',
                                onComplete: function() {
                                    $(".goFront", base.element).show();
                                    $(".goBack", base.element).hide();
                                }
                            });
                        }
                    });
                }

            });
        }
    });

    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, pluginName)) {
                $.data(this, pluginName, new Poll(this, options));
            }
        });
    };
})(jQuery, window, document);