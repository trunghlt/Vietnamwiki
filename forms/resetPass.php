<div id="resetDialog" title="Reset Password">
	<form id = "resetForm">
		<p>To reset your password, type the full email address you use to sign in to your VietnamWiki Account.</p>
		<label for="email">Email: </label>
		<input class="field" name="user_email" id="user_email" type="text" style="width:250px" /><br />
	</form>
</div>
<script language="javascript">
jQuery(document).ready(function(){
	resetDialog = jQuery("#resetDialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 400,
		modal: true,
		resizable:false,
                closeOnEscape: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			"Retrieve": function() {
				submitreEmail('resetForm');
			},
			Cancel: function() {
                                signOut();
				jQuery(this).dialog('close');
			}
		}
	});

});
function submitreEmail(dom){
	jQuery.post('../requests/resetPass.php',jQuery("#"+dom).serialize(),
					function(data){
                                                data = jQuery.trim(data);
						if(data == 'format')
						{
                                                    jQuery("#dialog_notification").html("Wrong email");
                                                    dialog_notification.dialog('open');
						}
						else if(data == 'wrongemail'){
                                                    jQuery("#dialog_notification").html("Email doesn't not exist");
                                                    dialog_notification.dialog('open');
						}
                                                else if(data=='true'){
                                                    resetDialog.dialog('close');
                                                    jQuery("#dialog_notification").html("Your account detail have been sent to your email address "+jQuery("#user_email").val());
                                                    dialog_notification.dialog('open');
                                                }
				});
}
</script>