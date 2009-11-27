<script language="javascript">
var loggedIn;
function chkLoggedIn(){
	var request = new Request({url: "requests/chkLoggedIn.php", async: false});
	request.addEvent("onComplete", function(response) {
		loggedIn = parseInt(response);
	});
	request.send();
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