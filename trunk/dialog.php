<div id='confirm' title='Review' >Your post has been submited. Moderators will review this post and send you an email to let you know if your edit is confirmed. Thanks !</div>
<script>
jQuery(document).ready(function(){ 
	post_review = jQuery("#confirm").dialog({
		autoOpen: false,
		width: '100',
		height: '50',
		modal: true,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Submit': function() {
				jQuery(this).dialog('close');
			}
		}
	});
});	
</script>
