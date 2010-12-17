<div id="resetDialog" title="Reset Password">
	<form id = "resetForm">
		<p>Please fill your email to which your information will be sent</p>
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
			"Retrieve pass": function() {
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
                                                    alert("Wrong email");
						}
						else if(data == 'wrongemail'){
							alert("Email not exist");
						}
                                                else if(data=='true'){
                                                    alert("Your information have been sent");
                                                    resetDialog.dialog('close');
                                                }
                                                alert(data);
				});
}
</script>