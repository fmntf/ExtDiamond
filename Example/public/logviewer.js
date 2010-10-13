Ext.onReady(function()
{

	var form = new Ext.FormPanel({
		title: 'View Logs',
		padding: 10,
		width: 330,
		items: [
			{
				xtype: 'datefield',
				fieldLabel: 'Date',
				ref: 'datepicker',
				format: 'Y-m-d',
				width: 200
			},
			{
				xtype: 'textarea',
				fieldLabel: 'Events',
				ref: 'eventlist',
				width: 200
			}
		],
		buttons: [
			{
				xtype: 'button',
				text: 'Destroy me',
				handler: function() {
					form.destroy();
				}
			}
		],

		loadEvents: function(picker, date)
		{
			var log = getDummyLogs(date);

			this.eventlist.setValue(log);
		}
	});

	form.render('renderhere');

	form.mon(form.datepicker, 'select', form.loadEvents, form);

});

function getDummyLogs(date)
{
	var logs  = date.format('Y-m-d') + ' - logon\n';
	    logs += date.format('Y-m-d') + ' - std activity\n';
	    logs += date.format('Y-m-d') + ' - logoff';

	return logs;
}
