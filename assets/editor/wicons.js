(function($R)
{
    $R.add('plugin', 'wicons', {
        translations: {
            en: {
                "change": "Change",
                "wicons": "Wojo Icons",
                "wicons-select": "Please, select an icon"
            }
        },
        modals: {
            'wicons': ''
        },
        init: function(app)
        {
            this.app = app;
            this.lang = app.lang;
            this.opts = app.opts;
            this.toolbar = app.toolbar;
            this.component = app.component;
            this.insertion = app.insertion;
            this.inspector = app.inspector;
            this.selection = app.selection;
        },

        // messages
        onmodal: {
            wicons: {
                open: function($modal, $form)
                {
                    this._build($modal);
                }
            }
        },
        oncontextbar: function(e, contextbar)
        {
            var data = this.inspector.parse(e.target);
            if (data.isComponentType('wicons'))
            {
                var node = data.getComponent();
                var buttons = {
                    "change": {
                        title: this.lang.get('change'),
                        api: 'plugin.wicons.open',
                        args: node
                    },
                    "remove": {
                        title: this.lang.get('delete'),
                        api: 'plugin.wicons.remove',
                        args: node
                    }
                };

                contextbar.set(e, node, buttons, 'bottom');
            }


        },

        // public
        start: function()
        {
            var obj = {
                title: this.lang.get('wicons'),
                api: 'plugin.wicons.open'
            };

            var $button = this.toolbar.addButton('wicons', obj);
            $button.setIcon('<i class="icon smile"></i>');
        },
        open: function()
		{
            var options = {
                title: this.lang.get('wicons'),
                width: '600px',
                name: 'wicons'
            };

            this.$currentItem = this._getCurrent();
            this.app.api('module.modal.build', options);
		},
		insert: function($item)
		{
    		this.app.api('module.modal.close');

            var type = $item.attr('data-type');
			var code = $item.attr('data-code');
            var $wicons = this.component.create('wicons');
            $wicons.addClass("icon " + code);
			//$wicons.html(type);

            this.insertion.insertRaw($wicons);
		},
        remove: function(node)
        {
            this.component.remove(node);
        },

        // private
		_getCurrent: function()
		{
    		var current = this.selection.getCurrent();
    		var data = this.inspector.parse(current);
    		if (data.isComponentType('wicons'))
    		{
        		return this.component.build(data.getComponent());
    		}
		},
		_build: function($modal)
		{
            var $body = $modal.getBody();
            var $label = this._buildLabel();
            var $list = this._buildList();

            this._buildItems($list);

            $body.html('');
            $body.append($label);
            $body.append($list);

		},
		_buildLabel: function()
		{
            var $label = $R.dom('<label>');
            $label.html(this.lang.parse('## wicons-select ##:'));

    		return $label;
		},
		_buildList: function()
		{
    		var $list = $R.dom('<ul>');
            $list.addClass('redactor-wicons-list');

            return $list;
		},
		_buildItems: function($list, data)
		{

    		var selectedType = this._getCurrentType();
    		//var items = this.opts.iconManagerJson;
			
            $R.ajax.get({
                url: this.opts.iconManagerJson,
                data: data,
                success: function(data)
                {
    		for (var i = 0; i < data.iconset.length; i++)
            {
                var type = data.iconset[i].name;
				var code = data.iconset[i].code;
                var $li = $R.dom('<li>');
                var $item = $R.dom('<i class="icon '+data.iconset[i].code+'">');

                $item.attr('data-type', type);
				$item.attr('data-code', code);
                //$item.html(type);
                $item.on('click', this._toggle.bind(this));

                if (selectedType === type)
                {
                    $item.addClass('redactor-wicons-item-selected');
                }

                $li.append($item);
                $list.append($li);
            }	
					
					
                }.bind(this)
            });
			
			

			
		},
		_getCurrentType: function()
		{
    		if (this.$currentItem)
    		{
        		var wiconsData = this.$currentItem.getData();

        		return wiconsData.type;
            }

    		return false;
		},
		_toggle: function(e)
		{
            var $item = $R.dom(e.target);

            this.app.api('plugin.wicons.insert', $item);
		}
    });
})(Redactor);
(function($R)
{
    $R.add('class', 'wicons.component', {
        mixins: ['dom', 'component'],
        init: function(app, el)
        {
            this.app = app;
            this.utils = app.utils;

            // init
            return (el && el.cmnt !== undefined) ? el : this._init(el);
        },
        // public
        getData: function()
        {
            return {
                type: this._getType()
            };
        },

        // private
        _init: function(el)
        {
            el = el || '<i>';

            this.parse(el);
            this._initWrapper();
        },
        _getType: function()
        {
            var text = this.text().trim();

            return this.utils.removeInvisibleChars(text);
        },
        _initWrapper: function()
        {
            this.addClass('redactor-component');
            this.attr({
                'data-redactor-type': 'wicons',
                'tabindex': '-1',
                'contenteditable': false
            });
        }
    });
})(Redactor);