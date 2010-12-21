<div id='dialog_notification' title='Alert' ><!-- --></div>
<script>
jQuery(document).ready(function(){
	dialog_notification = jQuery("#dialog_notification").dialog({
		autoOpen: false,
		width: '200',
		height: 'auto',
		modal: true,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Ok': function() {
				jQuery(this).dialog('close');
			}
		}
	});
});
</script>
