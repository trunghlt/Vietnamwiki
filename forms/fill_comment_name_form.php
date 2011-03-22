<div id="FillName_CommentDialog" title="Add name">
	<form id = "FillName_Comment">
		<input class="field" name="name_comment" id="name_comment" type="text" style="width:250px" value=""/><br />
	</form>
</div>
<script language="javascript">
jQuery(document).ready(function(){
	FillNameComment = jQuery("#FillName_CommentDialog").dialog({
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
				submit_name_comment('FillName_Comment');
			},
			Cancel: function() {
				if(document.getElementById('name_guess').value != ''){
					document.getElementById('name_guess').value='';
				}
				jQuery(this).dialog('close');
			}
		}		
	});
});
function submit_name_comment(dom){
		jQuery.post("../requests/changevalue.php", jQuery("#"+dom).serialize(),function(data){
			if(data=='1'){
				document.getElementById('name_guess').value = document.getElementById('name_comment').value;
				FillNameComment.dialog('close');
				fill.dialog('close');
			}else{ 
				alert('Wrong');
			}
		});
}
</script>