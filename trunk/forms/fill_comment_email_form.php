<div id="FillEmail_CommentDialog" title="Add email">
	<form id = "FillEmail_Comment">
		<input class="field" name="email_comment" id="email_comment" type="text" style="width:250px" value=""/><br />
	</form>
</div>
<script language="javascript">
jQuery(document).ready(function(){
	FillEmailComment = jQuery("#FillEmail_CommentDialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 400,
		modal: true,
		resizable:false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},		
		buttons: {
			Fill: function() {
				submit_email_comment('FillEmail_Comment');
			},
			Cancel: function() {
				if(document.getElementById('email_guess').value != ''){
					document.getElementById('email_guess').value='';
				}
				jQuery(this).dialog('close');
			}
		}		
	});

});
function submit_email_comment(dom){
		jQuery.post("../requests/changevalue.php", jQuery("#"+dom).serialize(),function(data){
			if(data=='1'){
				document.getElementById('email_guess').value = document.getElementById('email_comment').value;
				FillEmailComment.dialog('close');
			}else{ 
				alert('Email Invalid');
			}
		});
}
</script>