var sanitize = function (value) {
    return value
        .replace(/&/g, '&amp;')
        .replace(/>/g, '&gt;')
        .replace(/</g, '&lt;')
        .replace(/"/g, '&quot;');
};
var _ = function (key, def = '') {
    return typeof lang !== undefined && typeof lang[key] !== undefined ? lang[key] : def;
};
var columns = [[
    {
        field: 'pagetitle',
        title: _('pagetitle')
    },
    {
        field: 'review',
        title: _('review'),
        sortable: true,
        width: 200,
        formatter: sanitize
    },
    {
        field: 'name',
        title: _('name'),
        sortable: true,
        width: 80,
        formatter: sanitize
    },
    {
        field: 'email',
        title: 'E-mail',
        sortable: true,
        width: 80
    },
    {
        field: 'createdon',
        title: _('createdon'),
        sortable: true,
        fixed: true,
        width: 120
    },
    {
        field: 'rate',
        width: 30,
        fixed: true,
        align: 'center',
        title: '<span class="fa fa-lg fa-star"></span>',
        sortable: true
    },
    {
        field: 'active',
        width: 30,
        fixed: true,
        align: 'center',
        title: '<span class="fa fa-lg fa-power-off"></span>',
        sortable: true,
        formatter: function (value) {
            return '<span class="fa fa-lg fa-power-off" style="color:' + (value == 0 ? 'red' : 'green') + ';"></span>';
        }
    },
    {
        field: 'action',
        width: 40,
        title: '',
        align: 'center',
        fixed: true,
        formatter: function (value, row) {
            return '<a class="action delete" href="javascript:void(0)" onclick="GridHelper.delete(' + row.id + ')" title="' + _('delete') + '"><i class="fa fa-trash fa-lg"></i></a>';
        }
    }
]];
var GridHelper = {
    edit: function (row) {
        var tpl = $('#editFormTpl').html();
        $.post(Config.url,
            {
                mode: 'getItem',
                id: row.id
            },
            function (response) {
                if (response.success) {
                    var data = response.row;
                    data.publish = data.active === '1' ? ' checked' : '';
                    for (var key in data) {
                        var value = data[key];
                        if (typeof value === 'string') value = value
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;');
                        tpl = tpl.replace(new RegExp('\{%' + key + '%\}', 'g'),value);
                    }
                    var form = $(tpl);
                    form.dialog({
                        modal: true,
                        title: _('edit'),
                        collapsible: false,
                        minimizable: false,
                        maximizable: false,
                        resizable: true,
                        width: 500,
                        buttons: [
                            {
                                text: _('save'),
                                iconCls: 'btn-green fa fa-check fa-lg',
                                handler: function () {
                                    $('.has-error', form).removeClass('has-error');
                                    $('div.help-block', form).remove();
                                    $.post(Config.url,
                                        $('form', form).serialize(),
                                        function (response) {
                                            if (response.status) {
                                                $('#reviews').datagrid('reload');
                                                form.dialog('close', true);
                                            } else {
                                                if (typeof response.errors !== 'undefined' && Object.keys(response.errors).length > 0) {
                                                    for (var field in response.errors) {
                                                        var $field = $('[data-field="' + field + '"]', form).addClass('has-error');
                                                        var errors = response.errors[field];
                                                        for (var error in errors) {
                                                            $field.append($('<div class="help-block">' + errors[error] + '</div>'));
                                                        }
                                                    }
                                                }
                                                if (typeof response.messages !== 'undefined' && response.messages.length > 0) {
                                                    $.messager.alert(_('error'), response.messages.join('<br>'), 'error');
                                                }
                                            }
                                        }, 'json'
                                    ).fail(GridHelper.handleAjaxError);
                                }
                            }, {
                                text: _('close'),
                                iconCls: 'btn-red fa fa-ban fa-lg',
                                handler: function () {
                                    form.dialog('close', true);
                                }
                            }
                        ],
                        onOpen: function () {
                            $('#viewImage').click(function (e) {
                                e.preventDefault();
                                var image = $('<img/>');
                                image.on('load', function () {
                                    var nWidth = this.naturalWidth,
                                        nHeight = this.naturalHeight;
                                    var wWidth = $(window).width() - 200,
                                        wHeight = $(window).height() - 200;
                                    var img = $(this);
                                    var minRatio = Math.min(1, wWidth / nWidth, wHeight / nHeight);
                                    var width = Math.floor(minRatio * nWidth);
                                    var height = Math.floor(minRatio * nHeight);
                                    img.css({
                                        width: width,
                                        height: height
                                    }).wrap('<div/>').parent().window({
                                        title: _('image'),
                                        modal: true,
                                        collapsible: false,
                                        minimizable: false,
                                        maximizable: false,
                                        resizable: false
                                    }).window('open');
                                });
                                var src = $('input[name="image"]').val();
                                if (src != '') {
                                    src = Config.site_url + src;
                                    image.attr('src', src);
                                }
                            });
                            $('#browseImage').click(function (e) {
                                var width = screen.width * 0.5;
                                var height = screen.height * 0.5;
                                var iLeft = (screen.width - width) / 2;
                                var iTop = (screen.height - height) / 2;
                                var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes';
                                var url = Config.kcfinder_url + '&opener=Reviews';
                                sOptions += ',width=' + width;
                                sOptions += ',height=' + height;
                                sOptions += ',left=' + iLeft;
                                sOptions += ',top=' + iTop;
                                window.KCFinder = {};
                                window.KCFinder = {
                                    callBack: function (url) {
                                        window.KCFinder = null;
                                        $('input[name="image"]').val(url);
                                    }
                                };
                                var oWindow = window.open(url, 'ImageBrowser', sOptions);
                            });
                        },
                        onClose: function () {
                            GridHelper.destroyWindow(form);
                        }
                    });
                }
            }, 'json'
        ).fail(GridHelper.handleAjaxError);
    },
    delete: function (id) {
        $.messager.confirm(_('delete'), _('sure_to_delete'), function (r) {
            if (r) {
                $.post(
                    Config.url,
                    {
                        mode: 'delete',
                        id: id
                    },
                    function (data) {
                        $('#reviews').datagrid('reload');
                    }, 'json'
                ).fail(GridHelper.handleAjaxError);
            }
        });
    },
    initGrid: function () {
        $('#reviews').datagrid({
            url: Config.url,
            fitColumns: true,
            pagination: true,
            pageSize: 50,
            pageList: [50, 100, 150, 200],
            idField: 'id',
            singleSelect: true,
            striped: true,
            checkOnSelect: true,
            selectOnCheck: false,
            sortName: 'createdon',
            sortOrder: 'desc',
            columns: columns,
            onSelect: function (index) {
                $(this).datagrid('unselectRow', index);
            },
            onDblClickRow: function (index, row) {
                GridHelper.edit(row);
            }
        });
    },
    destroyWindow: function (wnd) {
        var mask = $('.window-mask');
        wnd.window('destroy', true);
        $('.window-shadow,.window-mask').remove();
        $('body').css('overflow', 'auto');
        $('body').append(mask);
    },
    handleAjaxError: function (xhr) {
        var message = xhr.status === 200 ? _('response_error') : _('server_error') + ' ' + xhr.status + ' ' + xhr.statusText;
        $.messager.alert(_('error'), message, 'error');
    },
};

