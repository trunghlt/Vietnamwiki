<div id="deleteConfirmDialog" title="Alert">
	Are you sure you want to delete this entry ?
</div>
<script language="javascript">
function submitDeletePost(){
	jQuery.post("requests/deletePost.php",{<?php
	if(isset($draf)) echo 'editionId:"'.$draf.'"';
	else if(isset($currentPostElement->id)) echo 'postId:"'.$currentPostElement->id.'"';
	?>},function(response){
		window.location = "index2.php";
	});
}

jQuery(document).ready(function(){ 
	deleteConfirmDialog = jQuery("#deleteConfirmDialog").dialog({
		autoOpen: false,
		height: 'auto',
		resizable: false,
		modal: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},		
		buttons: {
			'Ok': function() {
				submitDeletePost();
				jQuery(this).dialog("close");
			},
			Cancel: function(){ 
				jQuery(this).dialog("close");
			}
		}
	});
});
</script>