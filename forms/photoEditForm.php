<script language="javascript">
function imageEditClick(id) {
	var request = new Request({url: "requests/updatePhotoEditForm.php"});
	request.send("id="+id);
	request.addEvent("onComplete", function(response){
		$("photoEditDialogContent").set("html", response);
	});	
	if (chkLoggedIn()) {
		photoEditDialog.dialog("open");
	}
}
</script>
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
function submitPhotoEditForm(){
	$("photoEditForm").set("send", {url: "requests/postEdittedImage.php?dest_id=<?php echo $dest_id?>&page=<?php echo $page?>", method: "get"});
	$("photoEditForm").send();
	$("photoEditForm").get("send").addEvent("onComplete", function(response){
		$("imageList").set("html", response);
	});
}

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
</script>

