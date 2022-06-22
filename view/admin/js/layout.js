(function($) {
    "use strict";
    $.Layout = function(settings) {
        var config = {
            url: "",
            lurl: "",
            page_id: 0,
            mod_id: 0,
            type: '',
            lang: {
                edit: "Edit",
                delete: "Delete",
            }
        };
        if (settings) {
            $.extend(config, settings);
        }

        document.ontouchmove = function() {
            return true;
        };

        $(".sortable.celled").sortables({
            ghostClass: "ghost",
            group: "name",
            handle: ".handle",
            animation: 600,
            onStart: function(ui) {
                $(ui.item).css({
                    "width": "auto"
                });
            },
            onUpdate: function(ui) {
                var items = this.toArray();
                var position = $(ui.item).parent().attr('data-position');

                $.post(config.url + "/helper.php", {
                    simpleAction: 1,
                    action: "sortLayout",
                    position: position,
                    items: items,
                    type: config.type,
                    page: config.page_id,
                    mod: config.mod_id
                });

            },
            onAdd: function(ui) {
                var position = $(ui.item).parent().data('position');
                var items = [];
                $('[data-position=' + position + ']').children().each(function() {
                    items.push($(this).data("id"));
                });

                $.post(config.url + "/helper.php", {
                    simpleAction: 1,
                    action: "sortLayout",
                    position: position,
                    items: items,
                    type: config.type,
                    page: config.page_id,
                    mod: config.mod_id
                });
            }
        });

        // change page
        $("select[name=page_id]").on('change', function() {
            var id = $(this).val();
            var page = (id === "0") ? '' : '?page_id=' + id;
            window.location.href = config.lurl + page;
        });

        // change module
        $("select[name=mod_id]").on('change', function() {
            var id = $(this).val();
            var page = (id === "0") ? '' : '?mod_id=' + id;
            window.location.href = config.lurl + page;

        });

        // Add plugins
        $(".pAdd").on('click', function() {
            var $this = $(this);
            $this.addClass('loader');

            var idin = [];
            $('.sortable.celled li').each(function() {
                idin.push($(this).attr("data-id"));
            });

            var data = {
                doAction: 1,
                page: "getFreePlugins",
                section: $this.data('section'),
                ids: idin,
            };

            $.get(config.url + "/helper.php", data, function(json) {
                $this.popup({
                    on: 'manual',
                    lastResort: true,
                    exclusive: true,
                    hideOnScroll: false,
                    hoverable: false,
                    html: json.html
                }).popup('setting', 'variation', 'very wide').popup('show');
                $this.removeClass('loader');

                $(".button.cancel").on('click', function() {
                    $this.popup('hide');
                });

                $(".button.insert").on('click', function() {
                    var items = '';
                    var allitems = [];
                    var section = $('.popplug .yoyo.list').data('section');
                    $('.popplug .yoyo.list div.active').each(function() {
                        var id = $(this).data('id');
                        var text = $(this).text();
                        allitems.push($(this).data("id"));
                        items +=

                            '<li class="item" data-id="' + id + '" id="item_' + id + '">' +
                            '<div class="handle"><i class="icon reorder"></i></div>' +
                            '<div class="content">' + text + '</div>' +
                            '<a class="actions"><i class="icon negative trash"></i></a>' +
                            '</li>';
                    });
                    if (items) {
                        $('ol[data-position="' + section + '"]').append(items);
                        $.post(config.url + "/helper.php", {
                            simpleAction: 1,
                            action: "insertLayout",
                            position: section,
                            items: allitems,
                            type: config.type,
                            page: config.page_id,
                            mod: config.mod_id
                        });
                    }
                    $('.popplug .yoyo.list div.active').remove();
                });
                $(".popplug .yoyo.list").on('click', 'div', function() {
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    } else {
                        $(this).addClass('active');
                    }
                });
            }, 'json');
        });

        // Edit plugin spaces
        $(".pEdit").on('click', function() {
            var $this = $(this);
            var section = $this.data('section');
            $this.addClass('loader');

            var idin = [];
            $('ol[data-position=' + section + ']').children().each(function() {
                idin.push($(this).attr("data-id"));
            });

            var data = {
                doAction: 1,
                page: "getPluginLayout",
                section: section,
                ids: idin,
            };
            if (idin.length > 0) {
                $.get(config.url + "/helper.php", data, function(json) {
                    $this.popup({
                        on: 'manual',
                        lastResort: true,
                        exclusive: true,
                        hideOnScroll: false,
                        hoverable: false,
                        html: json.html
                    }).popup('setting', 'variation', 'very wide').popup('show');

                    $('.rangeslider').asRange({
                        min: 1,
                        max: 10,
                        step: 1,
                        range: false,
                        tip: {
                            active: 'onMove'
                        },
                        format: function(value) {
                            return value + ' space';
                        },
                    });


                    $this.removeClass('loader');

                    $(".button.update").on('click', function() {
                        var items = $('.layform').serializeArray();
                        $.post(config.url + "/helper.php", {
                            simpleAction: 1,
                            action: "updateLayout",
                            position: section,
                            items: items,
                            type: config.type,
                            page: config.page_id,
                            mod: config.mod_id
                        });
                    });
                    $(".button.cancel").on('click', function() {
                        $this.popup('hide');
                    });
                }, 'json');
            } else {
                $this.removeClass('loader');
            }
        });

        $(".sortable.celled.simple").on('click', '.actions', function() {
            var parent = $(this).parent('li');
            var data = {
                simpleAction: 1,
                action: "deleteLayout",
                id: $(this).parent().data('id'),

                type: config.type,
                page: config.page_id,
                mod: config.mod_id
            };

            $.post(config.url + "/helper.php", data, function(json) {
                if (json.type === "success") {
                    $(parent).remove();
                }
            }, 'json');

        });
    };
})(jQuery);