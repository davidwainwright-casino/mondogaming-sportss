toastr.options = {
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
    closeMethod: 'fadeOut',
    closeButton: true,
    progressBar: true,
    showDuration: '300',
    hideDuration: '1000',
    timeOut: '5000',
    extendedTimeOut: '5000',
    showEasing: 'swing',
    hideEasing: 'swing',
    onclick: null,
};

$(document).ready(function() {
    $('.tip').tooltip();
    $('#dob').datetimepicker({ format: 'YYYY-MM-DD', viewMode: 'years' });
    $('#date').datetimepicker({ format: 'YYYY-MM-DD' });
    $('.topic-box, .post-box').fitVids();
    $('[data-toggle="ajax-modal"]').click(function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        $.get(link).done(function(data) {
            $('#myModal')
                .html(data)
                .modal({ backdrop: 'static' });
        });
        return false;
    });
    $('.copy_api_key').click(function(e) {
        $(this).select();
        $('.help_copy_api_key').text('');
        try {
            var successful = document.execCommand('copy');
            var status = successful ? 'Key Copied' : 'Please press ctrl+c or right click to copy the api key';
        } catch (err) {
            var status = 'Oops, unable to copy, please press ctrl+c or right click to copy the api key';
        }
        $(this)
            .parents('.list-box')
            .find('.help_copy_api_key')
            .text(status);
    });
    $('.msg_link td:not(:first-child, :last-child)')
        .css('cursor', 'pointer')
        .click(function(e) {
            var page = $(this)
                .parent('tr')
                .attr('data-page');
            window.location.href =
                site_url +
                'messages/conversation/' +
                $(this)
                    .parent('tr')
                    .attr('data-id') +
                (page > 0 ? '?page=' + page : '');
        });
    $(document).on('click', '.po-del', function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        var btntext = $(this).attr('data-button-title');
        $(this)
            .popover({
                container: 'body',
                content:
                    '<p>' +
                    lang.action_x_undo +
                    '</p><p class="text-center"><a href="' +
                    link +
                    '" class="btn btn-danger">' +
                    (btntext ? btntext : lang.delete) +
                    '</a> <button class="btn btn-default po-no">' +
                    lang.close +
                    '</button></p>',
                title: lang.r_u_sure,
                html: true,
                placement: 'top',
                trigger: 'click',
            })
            .popover('toggle');
    });
    $(document).on('click', '.po-no', function(e) {
        $('.po-del').popover('hide');
    });
    $('.select2').select2({
        minimumResultsForSearch: 6,
    });

    $('.vote').click(function() {
        var self = $(this);
        var action = self.data('action');
        var parent = self.parent().parent('.vote-con');
        var postid = parent.data('postid');
        var cast = parent.data('cast');

        $.ajax({
            url: site_url + 'ajax_calls/vote',
            type: 'POST',
            cache: 'false',
            data: { postid: postid, action: action },
            success: function(data) {
                if (action == 'up') {
                    var score = parseInt(parent.find('.total-up').text());
                    parent.find('.total-up').html(score + 1);
                    if (cast == 1) {
                        parent.find('.total-down').text(parseInt(parent.find('.total-down').text() - 1));
                    }
                    parent.find('.vote-down').attr('disabled', false);
                } else if (action == 'down') {
                    var score = parseInt(parent.find('.total-down').text());
                    parent.find('.total-down').html(score + 1);
                    if (cast == 1) {
                        parent.find('.total-up').text(parseInt(parent.find('.total-up').text() - 1));
                    }
                    parent.find('.vote-up').attr('disabled', false);
                }
                self.addClass('.active');
                if (Settings.change_vote == 1) {
                    self.attr('disabled', true);
                } else {
                    $('.vote').attr('disabled', true);
                }
                toastr.success(data, 'Success!');
            },
            error: function(data) {
                toastr.error(data, 'Error!');
            },
        });
    });

    $('#rating').rating({
        min: 0,
        max: 5,
        step: 1,
        size: 'sm',
        showCaption: false,
        rtl: Settings.rtl == 1 ? true : false,
    });
    $('#rating').change(function() {
        var self = $(this);
        var my_stars = parseInt(self.val());
        var parent = self.parents('.stars-con');
        var postid = parent.data('postid');
        var thread_stars = parseFloat(parent.data('stars'));
        var my_old_stars = parseInt(parent.data('my-stars'));
        var cast = parseInt(parent.data('cast'));
        var total_votes = parseInt(parent.data('votes'));
        $.ajax({
            url: site_url + 'ajax_calls/vote',
            type: 'POST',
            cache: 'false',
            data: { postid: postid, stars: my_stars },
            success: function(data) {
                var main =
                    cast == 1
                        ? thread_stars - my_old_stars / total_votes + my_stars / total_votes
                        : (thread_stars * total_votes + my_stars) / (total_votes + 1);
                parent.find('.thread-rating').rating('update', main);
                self.attr('data-disabled', true);
                $('.total_votes').text(total_votes + (cast == 1 ? 0 : 1));
                toastr.success(data, 'Success!');
            },
            error: function(data) {
                toastr.error(data, 'Error!');
            },
        });
    });
    $('.thread-rating').rating({
        showClear: false,
        min: 0,
        max: 5,
        step: 0.01,
        size: 'lg',
        disabled: true,
        showCaption: false,
        rtl: Settings.rtl == 1 ? true : false,
    });
    $('.index-threads-rating').rating({
        showClear: false,
        min: 0,
        max: 5,
        step: 0.01,
        size: 'xs',
        disabled: true,
        showCaption: false,
        rtl: Settings.rtl == 1 ? true : false,
    });

    $(document).on('click', '#send-message', function(e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        var action = $form.attr('action');
        $form
            .formValidation({
                framework: 'bootstrap',
                excluded: ':disabled',
            })
            .on('success.form.fv', function(e) {
                e.preventDefault();
            });
        $form.data('formValidation').validate();
        if ($form.data('formValidation').isValid()) {
            var action = $form.attr('action');
            $.post(action, $form.serialize())
                .done(function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.msg, 'Success!');
                        $('#myModal').modal('hide');
                    } else {
                        toastr.error(data.msg, 'Error!');
                    }
                })
                .fail(function() {
                    toastr.error('Error occurred! please try again.', 'Error!');
                });
        }
    });
    $(document).on('click', '#mread', function(e) {
        $('#msg-action').append('<input name="action" value="mread">');
        $('#msg-actions').submit();
    });
    $(document).on('click', '#munread', function(e) {
        $('#msg-action').append('<input name="action" value="munread">');
        $('#msg-actions').submit();
    });
    $(document).on('click', '#mimportant', function(e) {
        $('#msg-action').append('<input name="action" value="mimportant">');
        $('#msg-actions').submit();
    });
    $(document).on('click', '#munimportant', function(e) {
        $('#msg-action').append('<input name="action" value="munimportant">');
        $('#msg-actions').submit();
    });
    $(document).on('click', '#mdelete', function(e) {
        $('#msg-action').append('<input name="action" value="mdelete">');
        $('#msg-actions').submit();
    });

    $('.file').change(function() {
        $('#subfile').val($(this).val());
    });
    $('#browse, #subfile').click(function() {
        $('.file').click();
    });
    if (Settings.editor == 'redactor') {
        $('#editor').trumbowyg({
            lang: Settings.rtl == 1 ? 'ar' : 'en',
            btnsDef: {
                formtext: {
                    dropdown: ['p', 'h3', 'h4', 'blockquote'],
                    ico: 'p',
                },
                align: {
                    dropdown: ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ico: 'justifyLeft',
                },
                image: {
                    dropdown: ['insertImage', 'noembed', 'upload', 'uploadFile'],
                    ico: 'insertImage',
                },
            },
            btns: [
                ['viewHTML'],
                ['formtext'],
                ['align'],
                ['strong', 'em'],
                ['link'],
                ['image'],
                ['unorderedList', 'orderedList'],
                ['preformatted'],
                ['emoji'],
                ['fullscreen'],
            ],
            removeformatPasted: true,
            svgPath: base_url + 'themes/default/assets/components/trumbowyg/ui/icons.svg',
            plugins: {
                upload: {
                    serverPath: site_url + 'upload/image',
                    fileFieldName: 'file',
                    error: function(error) {
                        alert(error.responseJSON.msg);
                    },
                },
                uploadFile: {
                    serverPath: site_url + 'upload/file',
                    fileFieldName: 'file',
                    error: function(error) {
                        alert(error.responseJSON.msg);
                    },
                },
            },
        });
        handleEmoji();
        $('#editor').bind('input propertychange', function() {
            handleEmoji();
        });
        $('#editor').on('tbwchange', function() {
            handleEmoji();
        });

        $(document).on('click', '.quote-reply', function(e) {
            var reply_id = $(this).attr('id');
            var author = $('#reply' + reply_id)
                .parents('.post-box')
                .find('.author')
                .text();
            var quote_html = $('#reply' + reply_id).html();
            $('#editor').focus();
            $('#editor').trumbowyg(
                'html',
                '<blockquote>' + quote_html.trim() + ' <span class="quoting">' + author + '</span></blockquote><br>'
            );
        });
    } else if (Settings.editor == 'simpledme') {
        var simplemde = new SimpleMDE({
            element: document.getElementById('editor'),
            toolbar: [
                'bold',
                'italic',
                'heading',
                'unordered-list',
                'ordered-list',
                'image',
                'link',
                'code',
                'quote',
                '|',
                'preview',
                'guide',
            ],
            forceSync: true,
            spellChecker: false,
            status: false,
        });
        $(document).on('click', '.quote-reply', function(e) {
            var reply_id = $(this).attr('id');
            var author = $('#reply' + reply_id)
                .parents('.post-box')
                .find('.author')
                .text();
            var quote_html = $('#mde' + reply_id).text();
            simplemde.value('> __' + lang.quoting + ' ' + author + '__ ' + quote_html.trim() + ' ');
            simplemde.codemirror.focus();
            simplemde.codemirror.setCursor(simplemde.codemirror.lineCount(), 0);
        });
    } else if (Settings.editor == 'sceditor') {
        $('#editor').sceditor({
            plugins: 'bbcode',
            toolbar:
                'bold,italic,underline,left,center,right,justify,size,bulletlist,orderedlist,image,link,youtube,emoticon,code,quote,removeformat,source',
            height: 200,
            emoticonsRoot: base_url + 'themes/default/assets/smileys/',
            style: 'minified/jquery.sceditor.default.min.css',
        });
        $(document).on('click', '.quote-reply', function(e) {
            var reply_id = $(this).attr('id');
            var author = $('#reply' + reply_id)
                .parents('.post-box')
                .find('.author')
                .text();
            var quote_html = $('#mde' + reply_id).text();
            var sce = $('#editor').sceditor('instance');
            sce.focus();
            sce.insert('[quote][b]' + lang.quoting + ' ' + author + '[/b] ' + quote_html.trim() + '[/quote] ');
        });
    }

    $('.select_all').change(function() {
        $('.select_msg').prop('checked', $(this).prop('checked'));
        $('.select_all').prop('checked', $(this).prop('checked'));
    });
    $('.select_msg').change(function() {
        if (!this.checked) {
            $('.select_all').prop('checked', false);
        }
    });

    var parent_fields = {},
        child_fields = {};
    $(document).on('change', '.parent_category', function(e) {
        if ($(this).val()) {
            var id = $(this).val();
            $.getJSON(site_url + 'topics/get_child_categories/' + id).done(function(res) {
                if (res.categories !== false) {
                    $('#child_category')
                        .empty()
                        .append('<option value="0">' + lang.select_child + '</option>');
                    $.each(res.categories, function() {
                        $('#child_category').append('<option value="' + this.id + '">' + this.name + '</option>');
                    });
                    $('#child_category').select2('val', 0);
                } else {
                    $('#child_category')
                        .empty()
                        .append('<option value="0">' + lang.no_child + '</option>');
                    $('#child_category').select2('val', 0);
                }
                if (res.fields !== false) {
                    $('#category_fields')
                        .empty()
                        .html(res.fields.html);
                    $('.select2').select2({ minimumResultsForSearch: 6 });
                    parent_fields = res.fields.names;
                    for (var field in parent_fields) {
                        var fs = $('#' + parent_fields[field]);
                        $('#addTopicForm').formValidation('addField', fs);
                    }
                } else {
                    for (var field in parent_fields) {
                        var fs = $('#' + parent_fields[field]);
                        $('#addTopicForm').formValidation('removeField', fs);
                    }
                    $('#category_fields').empty();
                }
            });
        } else {
            $('#child_category')
                .empty()
                .append('<option value="0">' + lang.select_parent_first + '</option>');
            $('#child_category').select2('val', 0);
            for (var field in parent_fields) {
                var fs = $('#' + parent_fields[field]);
                $('#addTopicForm').formValidation('removeField', fs);
            }
            $('#category_fields').empty();
        }
    });

    $(document).on('change', '#child_category', function(e) {
        if ($(this).val() && $(this).val() != 0) {
            var id = $(this).val();
            $.getJSON(site_url + 'topics/get_child_fields/' + id).done(function(res) {
                if (res.fields !== false) {
                    $('#child_category_fields')
                        .empty()
                        .html(res.fields.html);
                    $('.select2').select2({ minimumResultsForSearch: 6 });
                    child_fields = res.fields.names;
                    for (var field in child_fields) {
                        var fs = $('#' + child_fields[field]);
                        $('#addTopicForm').formValidation('addField', fs);
                    }
                } else {
                    for (var field in child_fields) {
                        var fs = $('#' + child_fields[field]);
                        $('#addTopicForm').formValidation('removeField', fs);
                    }
                    $('#child_category_fields').empty();
                }
            });
        } else {
            for (var field in child_fields) {
                var fs = $('#' + child_fields[field]);
                $('#addTopicForm').formValidation('removeField', fs);
            }
            $('#child_category_fields').empty();
        }
    });

    $(document).on('click', '.user', function(e) {
        e.preventDefault();
        $('.user-info').hide();
        var _this = $(this);
        var uid = _this.attr('data-id');
        _this.next('.user-info').toggle();
        if (_this.next('.user-info').hasClass('load')) {
            if (uid) {
                $.get(site_url + 'ajax_calls/get_user_details/' + uid).done(function(data) {
                    $('.user_' + uid)
                        .next('.user-info')
                        .find('.hidden-content-box-left')
                        .html(data);
                    $('.user_' + uid)
                        .next('.user-info')
                        .removeClass('load');
                });
            } else {
                $('.user_' + uid)
                    .next('.user-info')
                    .find('.hidden-content-box-left')
                    .html('<h3>' + lang.user_x_found + '</h3>');
                $('.user_' + uid)
                    .next('.user-info')
                    .removeClass('load');
            }
        }
        return false;
    });
    $(document).on('click', '.hidden-content-box-left', function(e) {
        if (!$(e.target).hasClass('skip')) {
            e.preventDefault();
            return false;
        }
    });
    $(document).on('click', function(e) {
        $('.user-info').hide();
    });
    $('#go-to-page').click(function() {
        var page = $('#page-num').val();
        if (page_url.indexOf('?') > -1) {
            window.location.href = page_url + '&page=' + page;
        } else {
            window.location.href = page_url + '?page=' + page;
        }
    });
    $('#page-num').keypress(function(e) {
        if (e.keyCode == 13) {
            $('#go-to-page').click();
        }
    });
    $('#addPost').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
    });
    $('#userForm, #avatarForm, #pwForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
    });
    $('.post-box:last').addClass('last');
    if ($('#protocol').val() == 'smtp') {
        $('#smtp_config').slideDown();
    } else if ($('#protocol').val() == 'sendmail') {
        $('#sendmail_config').slideDown();
    }
    $('#protocol').change(function() {
        if ($(this).val() == 'smtp') {
            $('#sendmail_config').slideUp();
            $('#smtp_config').slideDown();
        } else if ($(this).val() == 'sendmail') {
            $('#smtp_config').slideUp();
            $('#sendmail_config').slideDown();
        } else {
            $('#smtp_config').slideUp();
            $('#sendmail_config').slideUp();
        }
    });
    if ($('#wp_login').val() == '1') {
        $('#wordpress_config').slideDown();
    }
    $('#wp_login').change(function() {
        if ($(this).val() == '1') {
            $('#wordpress_config').slideDown();
        } else {
            $('#wordpress_config').slideUp();
        }
    });
    $('.detach_bagde').click(function(e) {
        e.preventDefault();
        var self = $(this);
        var id = self.attr('data-id');
        $.get(site_url + 'users/detach_badge/' + id, function(data) {
            toastr.success(data, 'Success!');
            self.parent('li.list-group-item').remove();
        });
        return false;
    });
    $('.reload-captcha').click(function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            success: function(data) {
                $('.captcha-image').html(data);
            },
        });
    });
    $('.approve').click(function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        var id = $(this).attr('data-id');
        $.get(link, function(data) {
            toastr.success(data, 'Success!');
            $('#' + id).remove();
        });
        return false;
    });
    $('.delete_review').click(function(e) {
        e.preventDefault();
        var link = $(this).attr('href');
        var id = $(this).attr('data-id');
        $.ajax({
            url: link,
            dataType: 'json',
            success: function(data) {
                if (data.error == 1) {
                    toastr.error(data.msg, 'Error!');
                } else {
                    toastr.success(data.msg, 'Success!');
                    $('#' + id).remove();
                }
            },
            error: function(data) {
                toastr.error('Action Failed', 'Error!');
            },
        });
        return false;
    });
});
$(window).load(function() {
    var avatars = $('.avatar');
    $.each(avatars, function() {
        var chr = $(this)
            .data('name')
            .toString()
            .charAt(0)
            .toUpperCase();
        $(this)
            .css({
                'background-color': randomColor({ luminosity: 'dark' }),
                color: '#FFF',
                'font-size': '180%',
                display: 'block',
                'border-radius': '45px',
                height: '45px',
                width: '45px',
                display: 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                'text-align': 'center',
            })
            .text(chr);
    });
    var tavatars = $('.tavatar');
    $.each(tavatars, function() {
        var chr = $(this)
            .data('name')
            .toString()
            .charAt(0)
            .toUpperCase();
        $(this)
            .css({
                'background-color': randomColor({ luminosity: 'dark' }),
                color: '#FFF',
                'font-size': '230%',
                display: 'block',
                'border-radius': '60px',
                height: '60px',
                width: '60px',
                display: 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                'text-align': 'center',
                margin: '0 auto',
            })
            .text(chr);
    });
});
$('#myModal').on('shown.bs.modal', function(e) {
    if (Settings.editor == 'simpledme') {
        var simplemde = new SimpleMDE({
            element: document.getElementById('body'),
            toolbar: [
                'bold',
                'italic',
                'heading',
                'unordered-list',
                'ordered-list',
                'image',
                'link',
                'code',
                'quote',
                '|',
                'preview',
                'guide',
            ],
            forceSync: true,
            spellChecker: false,
            status: false,
        });
        simplemde.render();
    }
});
$('#myModal').on('show.bs.modal', function(e) {
    $('.select2').select2({
        minimumResultsForSearch: 6,
    });
    $('#date').datetimepicker({ format: 'YYYY-MM-DD' });

    $('#title').change(function(e) {
        var title = $(this).val();
        var slug_url = site_url + 'get_slug?title=' + title;
        $.get(slug_url, function(slug) {
            $('#slug')
                .val(slug)
                .change();
        });
    });

    if (Settings.editor == 'redactor') {
        $('#body').trumbowyg({
            lang: Settings.rtl == 1 ? 'ar' : 'en',
            btnsDef: {
                formtext: {
                    dropdown: ['p', 'h3', 'h4', 'blockquote'],
                    ico: 'p',
                },
                align: {
                    dropdown: ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ico: 'justifyLeft',
                },
                image: {
                    dropdown: ['insertImage', 'noembed', 'upload', 'uploadFile'],
                    ico: 'insertImage',
                },
            },
            btns: [
                ['viewHTML'],
                ['formtext'],
                ['align'],
                ['strong', 'em'],
                ['link'],
                ['image'],
                ['unorderedList', 'orderedList'],
                ['preformatted'],
                ['emoji'],
                ['fullscreen'],
            ],
            removeformatPasted: true,
            svgPath: base_url + 'themes/default/assets/components/trumbowyg/ui/icons.svg',
            plugins: {
                upload: {
                    serverPath: site_url + 'upload/image',
                    fileFieldName: 'file',
                    error: function(error) {
                        alert(error.responseJSON.msg);
                    },
                },
                uploadFile: {
                    serverPath: site_url + 'upload/file',
                    fileFieldName: 'file',
                    error: function(error) {
                        alert(error.responseJSON.msg);
                    },
                },
            },
        });
        handleEmoji();
        $('#body').bind('input propertychange', function() {
            handleEmoji();
        });
        $('#body').on('tbwchange', function() {
            handleEmoji();
        });
    } else if (Settings.editor == 'sceditor') {
        $('#body').sceditor({
            plugins: 'bbcode',
            toolbar:
                'bold,italic,underline,left,center,right,justify,size,bulletlist,orderedlist,image,link,youtube,emoticon,code,quote,removeformat,source',
            height: 200,
            emoticonsRoot: base_url + 'themes/default/assets/smileys/',
            style: 'minified/jquery.sceditor.default.min.css',
        });
    }
    $('.reload-captcha').click(function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            success: function(data) {
                $('.captcha-image').html(data);
            },
        });
    });
    $('#editPost, #categoryForm, #pageForm, #badgeForm, #fieldForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
    });
    $('#addTopicForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        err: {
            container: '#errors',
        },
        fields: {
            protected: {
                validators: {
                    notEmpty: {
                        message: lang.who_can_see_required,
                    },
                },
            },
        },
    });
});

$('#status, #uquery').change(function(e) {
    window.location.href = site_url + 'users?status=' + $('#status').val() + '&uquery=' + $('#uquery').val();
});

function handleEmoji() {
    emojify.setConfig({
        img_dir: base_url + 'themes/default/assets/components/trumbowyg/emojify/images/basic',
    });
    emojify.run();
}

$(document)
    .ajaxStart(function() {
        $('#ajaxCall').show();
    })
    .ajaxStop(function() {
        $('#ajaxCall').hide();
    });
$.ajaxSetup({ cache: false });
