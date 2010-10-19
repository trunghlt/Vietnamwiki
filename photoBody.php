<script language="javascript">
var loggedIn;
function chkLoggedIn(){
	jQuery.post("requests/chkLoggedIn.php", function(data) {
		loggedIn = parseInt(data);
	});
	if (loggedIn == 1) return true;
	return false;
}
</script>
<div id="imageList">
	<?php include("imageListPainter.php");?>
</div>
<?php
	include("forms/photoEditForm.php");
	include("forms/photoUploadForm.php");
?>
