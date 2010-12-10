<div id="FillEmailDialog" title="Add email">
	<form id = "FillEmail">
		<p>In order for us to notify you of changes in topics which you have created or editted, please register your Email address with the system.(<span style="color: red">Note:</span>We loathe spams, hence, we will never spam)</p>
		<label for="email">Email: </label>
		<input class="field" name="email" id="email" type="text" style="width:250px" /><br />
		<input class="field" name="id_user" id="id_user" type="hidden" />
                <input class="field" name="name_user" id="name_user" type="hidden" />
		<input class="field" name="editpost" id="editpost" value='login' type="hidden" />
	</form>
</div>
<script language="javascript">
jQuery(document).ready(function(){
	Fill_EmailDialog = jQuery("#FillEmailDialog").dialog({
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
			Fill: function() {
				submitEmail('FillEmail');
			},
			Cancel: function() {
                                signOut();
				jQuery(this).dialog('close');
			}
		}		
	});

});
function submitEmail(dom){
	jQuery.post('../requests/update_email.php',jQuery("#"+dom).serialize(),
					function(data){
						if(data == 'ok')
						{
							alert('Add Email Success');
							Fill_EmailDialog.dialog('close');
							if(document.getElementById('editpost').value == 'editpost'){
									document.getElementById('editpost').value = 'value';
									
									jQuery('#editDialog').css('visibility','visible').dialog('open');
							}
                                                        else if(document.getElementById('editpost').value == 'login'){
                                                            window.location="feed.php?username="+jQuery("#name_user").val();
                                                        }
                                                        set_value();
						}
						else if(data != 'null'){
							alert(data);
						}
				});
}
</script>