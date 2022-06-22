(function($)
{
    $.Redactor.prototype.video = function()
    {
        return {
            reUrlYoutube: /https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube\.com\S*[^\w\-\s])([\w\-]{11})(?=[^\w\-]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/ig,
            reUrlVimeo: /https?:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/,
            langs: {
                en: {
                    "video": "Video",
                    "video-html-code": "Video Embed Code or Youtube/Vimeo Link"
                }
            },
            getTemplate: function()
            {
                return String()
                + '<div class="modal-section" id="redactor-modal-video-insert">'
                + '<label>' + this.lang.get('video-html-code') + '</label>'
                + '<textarea id="redactor-insert-video-area" style="height: 160px;"></textarea>'
                + '</div>';
            },
            init: function()
            {
                var button = this.button.addAfter('image', 'video', this.lang.get('video'));
                this.button.addCallback(button, this.video.show);
            },
            show: function()
            {
                this.modal.addTemplate('video', this.video.getTemplate());

                this.modal.load('video', this.lang.get('video'), 700);
                this.modal.createCancelButton();

                // action button
                this.modal.createActionButton(this.lang.get('insert')).on('click', this.video.insert);

                this.modal.show();

                $('#redactor-insert-video-area').focus();

            },
            insert: function()
            {
                var data = $('#redactor-insert-video-area').val();

                if (!data.match(/<iframe|<video/gi))
                {
                    data = this.clean.stripTags(data);

                    // parse if it is link on youtube & vimeo
                    var iframeStart = '<iframe style="width: 500px; height: 281px;" src="',
                    iframeEnd = '" frameborder="0" allowfullscreen></iframe>';

                    if (data.match(this.video.reUrlYoutube))
                    {
                        data = data.replace(this.video.reUrlYoutube, iframeStart + '//www.youtube.com/embed/$1' + iframeEnd);
                    }
                    else if (data.match(this.video.reUrlVimeo))
                    {
                        data = data.replace(this.video.reUrlVimeo, iframeStart + '//player.vimeo.com/video/$2' + iframeEnd);
                    }
                }

                this.modal.close();
                this.placeholder.remove();

                // buffer
                this.buffer.set();

                // insert
                // this.air.collapsed();
                this.insert.html(data);

            }

        };
    };
})(jQuery);
(function($)
{
    $.Redactor.prototype.clips = function()
    {
        return {
            init: function()
            {
                var items = smileys;

                this.clips.template = $('<ul id="smileys-con">');

                for (var i = 0; i < items.length; i++)
                {
                    var li = $('<li class="smileys">');
                    var a = $('<a href="#" class="smiley">').html('<img src="'+items[i].src+'" alt="'+items[i].name+'" class="smiley-icon" />');

                    li.append(a);
                    this.clips.template.append(li);
                }

                this.modal.addTemplate('clips', '<div class="modal-section">' + this.utils.getOuterHtml(this.clips.template) + '<div class="clearfix"></div></div>');

                var button = this.button.add('clips', 'Smiley');

                this.button.addCallback(button, this.clips.show);

            },
            show: function()
            {
                this.modal.load('clips', 'Insert Smiley', 500);
                this.modal.createCancelButton();

                $('#smileys-con').find('.smiley').each($.proxy(this.clips.load, this));

                this.modal.show();
            },
            load: function(i,s)
            {

                $(s).on('click', $.proxy(function(e)
                {
                    e.preventDefault();
                    this.clips.insert($(s).html());
                }, this));
            },
            insert: function(html)
            {
                this.buffer.set();
                // this.air.collapsedEnd();
                this.insert.html(html);
                this.modal.close();
                this.observe.load();
            }
        };
    };
})(jQuery);

(function($)
{
    $.Redactor.prototype.tec = function()
    {
        return {
            init: function () {
                if (!this.opts.counterCallback) return;
                this.$editor.on('keyup.redactor-limiter', $.proxy(function(e)
                {
                    var words = 0, characters = 0, spaces = 0;
                    var html = this.code.get();
                    var text = html.replace(/<\/(.*?)>/gi, ' ');
                    text = text.replace(/<(.*?)>/gi, '');
                    text = text.replace(/\t/gi, '');
                    text = text.replace(/\n/gi, ' ');
                    text = text.replace(/\r/gi, ' ');
                    text = $.trim(text);
                    if (text !== '') {
                        var arrWords = text.split(/\s+/);
                        var arrSpaces = text.match(/\s/g);
                        if (arrWords) words = arrWords.length;
                        if (arrSpaces) spaces = arrSpaces.length;
                        characters = text.length;
                    }
                    this.core.setCallback('counter', { words: words, characters: characters, spaces: spaces });
                }, this));
            }
        };
    };
})(jQuery);