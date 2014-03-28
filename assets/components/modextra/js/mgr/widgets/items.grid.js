modExtra.grid.Items = function(config) {
	config = config || {};

	Ext.applyIf(config, {
		id: 'modextra-grid-items',
		url: modExtra.config.connector_url,
		baseParams: {
			action: 'mgr/item/getlist'
		},
		fields: ['id', 'name', 'description', 'published'],
		autoHeight: true,
		paging: true,
		remoteSort: true,
        clicksToEdit: 'auto',
        save_action: 'mgr/item/updatefromgrid',
        autosave: true,
        copy: false,
		columns: [{
            header: _('id'),
            dataIndex: 'id',
            width: 30,
            align: 'center'
        },{
            header: _('name'),
            dataIndex: 'name',
            width: 150,
            editor: {
                xtype: 'textfield',
                allowBlank: false
            }
        },{
            header: _('description'),
            dataIndex: 'description',
            width: 300
        },{
            header: _('published'),
            dataIndex: 'published',
            width: 50,
            align: 'center',
            editor: {
                xtype: 'combo-boolean',
                renderer: 'boolean'
            }
        }],
		tbar: [{
			text: _('modextra_item_create'),
			handler: this.createItem,
			scope: this
		}],
		/*listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		}*/
	});

	modExtra.grid.Items.superclass.constructor.call(this, config);
};

Ext.extend(modExtra.grid.Items, MODx.grid.Grid, {
	windows: {},

	getMenu: function() {
		var m = [];
		m.push({
			text: _('modextra_item_update'),
			handler: this.updateItem
		});
		m.push('-');
		m.push({
			text: _('modextra_item_remove'),
			handler: this.removeItem
		});

		this.addContextMenuItem(m);
	},

	createItem: function(btn, e) {
        var createItem = MODx.load({
            xtype: 'modextra-window-item-create',
            listeners: {
                success: {
                    fn: function() {
                        this.refresh();
                    }, scope:this
                }
            }
        });

		createItem.fp.getForm().reset();
		createItem.show(e.target);
	},

	updateItem: function(btn, e, row) {
		if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }

		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: modExtra.config.connector_url,
			params: {
				action: 'mgr/item/get',
				id: id
			},
			listeners: {
				success: {
                    fn: function(r) {
                        var updateItem = MODx.load({
                            xtype: 'modextra-window-item-update',
                            record: r,
                            listeners: {
                                success: {
                                    fn: function() {
                                        this.refresh();
                                    }, scope:this
                                }
                            }
                        });

                        updateItem.fp.getForm().reset();
                        updateItem.fp.getForm().setValues(r.object);
                        updateItem.show(e.target);
                    }, scope:this
                }
			}
		});
	},

	removeItem: function(btn, e) {
		if (!this.menu.record) return false;

		MODx.msg.confirm({
			title: _('modextra_item_remove'),
			text: _('modextra_item_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'mgr/item/remove',
				id: this.menu.record.id
            },
			listeners: {
				success: {
                    fn: function(r) {
                        this.refresh();
                    }, scope:this
                }
			}
		});
	}
});

Ext.reg('modextra-grid-items', modExtra.grid.Items);


modExtra.window.CreateItem = function(config) {
	config = config || {};

	this.ident = config.ident || 'mecitem' + Ext.id();

	Ext.applyIf(config, {
		title: _('modextra_item_create'),
		id: this.ident,
		height: 200,
		width: 475,
		url: modExtra.config.connector_url,
		action: 'mgr/item/create',
		fields: [{
            xtype: 'textfield',
            fieldLabel: _('name'),
            name: 'name',
            id: 'modextra-' + this.ident + '-name',
            anchor: '100%',
            allowBlank: false
        },{
            xtype: 'textarea',
            fieldLabel: _('description'),
            name: 'description',
            id: 'modextra-' + this.ident + '-description',
            height: 150,
            anchor: '100%',
            allowBlank: false
        }],
		keys: [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: function() {
                this.submit()
            }, scope: this
        }]
	});

	modExtra.window.CreateItem.superclass.constructor.call(this, config);
};

Ext.extend(modExtra.window.CreateItem, MODx.Window);
Ext.reg('modextra-window-item-create', modExtra.window.CreateItem);


modExtra.window.UpdateItem = function(config) {
	config = config || {};

	this.ident = config.ident || 'meuitem' + Ext.id();

	Ext.applyIf(config, {
		title: _('modextra_item_update'),
		id: this.ident,
		height: 200,
		width: 475,
		url: modExtra.config.connector_url,
		action: 'mgr/item/update',
		fields: [{
            xtype: 'hidden',
            name: 'id',
            id: 'modextra-' + this.ident + '-id'
        },{
            xtype: 'textfield',
            fieldLabel: _('name'),
            name: 'name',
            id: 'modextra-' + this.ident + '-name',
            anchor: '99%'
        },{
            xtype: 'textarea',
            fieldLabel: _('description'),
            name: 'description',
            id: 'modextra-' + this.ident + '-description',
            height: 150,
            anchor: '99%'
        }],
		keys: [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: function() {
                this.submit()
            }, scope: this
        }]
	});

	modExtra.window.UpdateItem.superclass.constructor.call(this, config);
};

Ext.extend(modExtra.window.UpdateItem, MODx.Window);
Ext.reg('modextra-window-item-update', modExtra.window.UpdateItem);
