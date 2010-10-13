Ext.onReady(function()
{

	FirewallGrid = Ext.extend(Ext.grid.GridPanel, {

		accept: function(record)
		{
			alert('Accept: ' + record.get('name'));
		},

		deny: function(record)
		{
			alert('Deny: ' + record.get('name'));
		}

	});

	var grid = new FirewallGrid(
	{
		height: 200,
		width: 500,
		autoExpandColumn: 'name',
		store: new Ext.data.ArrayStore({
			fields: [
			   {name: 'name'},
			   {name: 'description'}
			]
			,
			data: [
				['wupdate.exe', 'Windows Update client'],
				['svchost.dll', 'Service host'],
				['firefox.exe', 'Firefox Browser'],
				['soffice.bin', 'OpenOffice.org'],
				['iexplore.exe', 'Internet Explorer Browser (?)'],
				['a928dds.exe', 'I promise, I\'m not a virus!']
			],
			autoLoad: true
		}),
		columns: [
			{
				id: 'name',
				header : 'Application',
				dataIndex: 'name'
			},
			{
				id: 'description',
				header : 'Description',
				dataIndex: 'description',
				width: 250
			},
			{
				header: 'Action',
				xtype: 'actioncolumn',
				width: 50,
				items: [
					{
						icon: 'resources/fam/cross.gif',
						iconCls: 'firewall-deny',
						tooltip: 'Deny',
						handler: function(firewall, rowIndex, colIndex) {
							var record = firewall.getStore().getAt(rowIndex);
							firewall.deny(record);
						}
					},
					{
						icon: 'resources/fam/accept.png',
						iconCls: 'firewall-accept',
						tooltip: 'Accept',
						handler: function(firewall, rowIndex, colIndex) {
							var record = firewall.getStore().getAt(rowIndex);
							firewall.accept(record);
						}
					}
				]
			}
		]
	});

	var window = new Ext.Window({
		cls: 'firewall-window',
		title: 'My Firewall',
		y: 150,
		items: [
			grid
		]
	});

	window.show();

});