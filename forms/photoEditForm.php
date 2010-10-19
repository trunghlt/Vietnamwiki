 <div id="photoEditDialog" title="Edit image information">
 <div id="photoEditDialogContent">
 	<?php 
		$currentImageElement = new ImageElement();
		$imgPath = "";		
 		include("photoEditFormPainter.php")
	?>
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){ 
	photoEditDialog = jQuery("#photoEditDialog").dialog({
		autoOpen: false,
		width: '500',
		height: 'auto',
		modal: true,
		resizable: false,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Submit': function() {
				submitPhotoEditForm();
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});
function imageEditClick(ids) {
	if (chkLoggedIn()){
		jQuery.post("requests/updatePhotoEditForm.php", {id: ids}, function(data) {
			jQuery("#photoEditDialogContent").html(data);
		});	
		photoEditDialog.dialog("open");
	}
}
function submitPhotoEditForm(){
	jQuery.post("requests/postEdittedImage.php", {dest_id:<?=$dest_id?>, page:<?=$page?>}, function(data) {
		jQuery("#imageList").html(data);
	});
}	
</script>

