(function ($) {
    'use strict';

    var defaultOptions = {
        serverPath: '',
        fileFieldName: 'fileToUpload',
        data: [],                       // Additional data for ajax [{name: 'key', value: 'value'}]
        headers: {},                    // Additional headers
        xhrFields: {},                  // Additional fields
        urlPropertyName: 'file',        // How to get url from the json response (for instance 'url' for {url: ....})
        statusPropertyName: 'success',  // How to get status from the json response
        success: undefined,             // Success callback: function (data, trumbowyg, $modal, values) {}
        error: undefined,               // Error callback: function () {}
    };

    function getDeep(object, propertyParts) {
        var mainProperty = propertyParts.shift(),
            otherProperties = propertyParts;

        if (object !== null) {
            if (otherProperties.length === 0) {
                return object[mainProperty];
            }

            if (typeof object === 'object') {
                return getDeep(object[mainProperty], otherProperties);
            }
        }
        return object;
    }

    addXhrProgressEvent();

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                uploadFile: 'Upload File',
                file: 'File',
                uploadError: 'Error'
            },
            sk: {
                uploadFile: 'Nahrať Súbor',
                file: 'Súbor',
                uploadError: 'Chyba'
            },
            fr: {
                uploadFile: 'Envoi Fichier',
                file: 'Fichier',
                uploadError: 'Erreur'
            },
            cs: {
                uploadFile: 'Nahrát obrázek Soubor',
                file: ' ',
                uploadError: 'Chyba'
            },
            zh_cn: {
                uploadFile: '上传 文件',
                file: '文件',
                uploadError: '错误'
            },
            zh_tw: {
                uploadFile: '上傳 文件',
                file: '文件',
                uploadError: '錯誤'
            },
            ru: {
                uploadFile: 'Загрузка Файл',
                file: 'Файл',
                uploadError: 'Ошибка'
            },
            ja: {
                uploadFile: 'アップロード ファイル',
                file: 'ファイル',
                uploadError: 'エラー'
            },
            pt_br: {
                uploadFile: 'Enviar do local Arquivo',
                file: 'Arquivo',
                uploadError: 'Erro'
            },
            tr: {
                uploadFile: 'Yükle Dosya',
                file: 'Dosya',
                uploadError: 'Hata'
            }
        },
        // jshint camelcase:true

        plugins: {
            uploadFile: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.uploadFile = $.extend(true, {}, defaultOptions, trumbowyg.o.plugins.uploadFile || {});
                    var btnDef = {
                        ico: 'upload',
                        fn: function () {
                            trumbowyg.saveRange();

                            var file,
                                prefix = trumbowyg.o.prefix;

                            var fields = {
                                file: {
                                    type: 'file',
                                    required: true,
                                    attributes: {
                                        accept: '.zip,.psd,.ai,.rar,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt'
                                    }
                                },
                                name: {
                                    label: 'Name',
                                    required: true,
                                    value: trumbowyg.getRangeText()
                                }
                            };

                            var $modal = trumbowyg.openModalInsert(
                                // Title
                                trumbowyg.lang.uploadFile,

                                // Fields
                                fields,

                                // Callback
                                function (values) {
                                    var data = new FormData();
                                    data.append(trumbowyg.o.plugins.uploadFile.fileFieldName, file);

                                    trumbowyg.o.plugins.uploadFile.data.map(function (cur) {
                                        data.append(cur.name, cur.value);
                                    });

                                    $.map(values, function (curr, key) {
                                        if (key !== 'file') {
                                            data.append(key, curr);
                                        }
                                    });

                                    if ($('.' + prefix + 'progress', $modal).length === 0) {
                                        $('.' + prefix + 'modal-title', $modal)
                                            .after(
                                                $('<div/>', {
                                                    'class': prefix + 'progress'
                                                }).append(
                                                    $('<div/>', {
                                                        'class': prefix + 'progress-bar'
                                                    })
                                                )
                                            );
                                    }

                                    $.ajax({
                                        url: trumbowyg.o.plugins.uploadFile.serverPath,
                                        headers: trumbowyg.o.plugins.uploadFile.headers,
                                        xhrFields: trumbowyg.o.plugins.uploadFile.xhrFields,
                                        type: 'POST',
                                        data: data,
                                        cache: false,
                                        dataType: 'json',
                                        processData: false,
                                        contentType: false,

                                        progressUpload: function (e) {
                                            $('.' + prefix + 'progress-bar').css('width', Math.round(e.loaded * 100 / e.total) + '%');
                                        },

                                        success: function (data) {
                                            if (trumbowyg.o.plugins.uploadFile.success) {
                                                trumbowyg.o.plugins.uploadFile.success(data, trumbowyg, $modal, values);
                                            } else {
                                                if (!!getDeep(data, trumbowyg.o.plugins.uploadFile.statusPropertyName.split('.'))) {
                                                    var url = getDeep(data, trumbowyg.o.plugins.uploadFile.urlPropertyName.split('.'));
                                                    var name = url.split('/');
                                                    trumbowyg.execCmd('insertHTML', '<a href="'+data.file+'" target="_blank">'+(data.name ? data.name : name[name.length-1])+'</a>');
                                                    setTimeout(function () {
                                                        trumbowyg.closeModal();
                                                    }, 250);
                                                    trumbowyg.$c.trigger('tbwuploadsuccess', [trumbowyg, data, url]);
                                                } else {
                                                    trumbowyg.addErrorOnModalField(
                                                        $('input[type=file]', $modal),
                                                        trumbowyg.lang[data.message]
                                                    );
                                                    trumbowyg.$c.trigger('tbwuploaderror', [trumbowyg, data]);
                                                }
                                            }
                                        },

                                        error: trumbowyg.o.plugins.uploadFile.error || function () {
                                            trumbowyg.addErrorOnModalField(
                                                $('input[type=file]', $modal),
                                                trumbowyg.lang.uploadError
                                            );
                                            trumbowyg.$c.trigger('tbwuploaderror', [trumbowyg]);
                                        }
                                    });
                                }
                            );

                            $('input[type=file]').on('change', function (e) {
                                try {
                                    // If multiple files allowed, we just get the first.
                                    file = e.target.files[0];
                                } catch (err) {
                                    // In IE8, multiple files not allowed
                                    file = e.target.value;
                                }
                            });
                        }
                    };

                    trumbowyg.addBtnDef('uploadFile', btnDef);
                }
            }
        }
    });

    function addXhrProgressEvent() {
        if (!$.trumbowyg.addedXhrProgressEvent) {   // Avoid adding progress event multiple times
            var originalXhr = $.ajaxSettings.xhr;
            $.ajaxSetup({
                xhr: function () {
                    var req = originalXhr(),
                        that = this;
                    if (req && typeof req.upload === 'object' && that.progressUpload !== undefined) {
                        req.upload.addEventListener('progress', function (e) {
                            that.progressUpload(e);
                        }, false);
                    }

                    return req;
                }
            });
            $.trumbowyg.addedXhrProgressEvent = true;
        }
    }
})(jQuery);
