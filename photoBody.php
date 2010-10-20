<script language="javascript">
function chkLoggedIn(){
    var isExist = 0;
    jQuery.ajax({
        async: false,
        type: "POST",
	url: "requests/chkLoggedIn.php",
	data: "",
	success: function(msg){
	     isExist = msg;
	}
    });
    if(isExist==1)
        return true;
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
