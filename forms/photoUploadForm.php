<script>
function upload_image_click() {
	if (chkLoggedIn()) { 
		photoUploadDialog.dialog("open");
	}
	else {
		loginDialog.dialog("open");
	}	
}
</script>


<div id="photoUploadDialog" title="Upload image">	
<form id="photoUploadForm">
 	<div style="margin-top:5px;">
	<?php include("upload2/index.php"); ?>
	</div>
	<div style="margin: 5px;">
	<label><b>Location</b></label><br/>
	<select id ="loc" name="loc">
		<?php
			$sql = "SELECT *
					FROM destinations
					ORDER BY ord";
			$q->query($sql);
			while ($row = mysql_fetch_array($q->re)) {
				?>
				<option value="<?php echo $row["id"];?>" <?php if ($_GET["dest_id"] == $row["id"]) { ?>selected="yes"<?php } ?>/><?php echo $row["EngName"];?></option>
		<?php } ?>
	</select><br/>
	<p><label><b>Description</b></label><br/>
	<input id="des" name="des" type="text" value="" size="70" /></p>
	<p><label><b>Tags</b></label><br/>
	<input id="tags" name="tags" type="text" value="" size="70"/></p>
	</div>
</form>
</div>	  

<script type="text/javascript">
function submitPhoto(){
	$("photoUploadForm").set("send", {url: "requests/uploadImage.php"});
	$("photoUploadForm").get("send").addEvent("onComplete", function(response){
		window.location.reload(true);
	});
	$("photoUploadForm").send();	
}

jQuery(document).ready(function(){ 
	photoUploadDialog = jQuery("#photoUploadDialog").dialog({
		autoOpen: false,
		width: '430',
		height: 'auto',
		modal: true,
		resizable: true,
		overlay: {
			backgroundColor: '#000',
			opacity: 0.5
		},
		buttons: {
			'Submit': function() {
				submitPhoto();
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		}
	});
});	
</script>
