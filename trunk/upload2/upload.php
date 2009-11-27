<?php
error_reporting(E_ALL);

function error($msg) {
	?>
	<script>
	alert("<?php echo $msg?>");
	</script>
	<?php
}

if (isset($_FILES['image'])) {
	

	
	//see class.upload for detail
	include('class.upload.php');	
	
	$handle = new Upload($_FILES['image']);
	
	if ($handle->uploaded) {
		$ftmp = $_FILES['image']['tmp_name'];
		$oname = $_FILES['image']['name'];

		//image format check
		if (!getimagesize($ftmp)) {
			error('only image uploads are allowed');
			exit();
		}
		
		$handle->Process("upload");

		if ($handle->processed) {
			$fname = $handle->file_dst_name;
			$pname = "upload/". $fname;
			$handle->clean();
			?>
			<html><head><script>
			var par = window.parent.document;
			var tmp_file_name = par.getElementById("tmp_file_name");
			tmp_file_name.value = "<?php echo $fname?>";
			
			var images = par.getElementById('images_container');
			var imgdiv = images.getElementsByTagName('div')[0];
			var image = imgdiv.getElementsByTagName('img')[0];
			imgdiv.removeChild(image);
			var image_new = par.createElement('img');
			image_new.src = 'upload2/resize.php?pic=<?php echo $pname?>';
			image_new.className = 'loaded';
			imgdiv.appendChild(image_new);
			</script></head>
			</html>
			<?php
		}
		else {
			error($handle->error);
		}
	}
	else {
		error("Image size should not be bigger than 2MB");
	}
	exit();
}
?><html><head>
<script language="javascript">
function upload(){
	var par = window.parent.document;

	// hide old iframe
	var container = par.getElementById("iframe_container");
	var iframes = container.getElementsByTagName('iframe');
	var iframe = iframes[iframes.length - 1];
	iframe.className = "hidden";

	// create new iframe
	var new_iframe = par.createElement('iframe');
	new_iframe.src = 'upload2/upload.php';
	new_iframe.frameBorder = '0';
	container.appendChild(new_iframe);

	// add image progress
	var images = par.getElementById('images_container');

	
	var new_div = par.getElementById('image');
	//var new_div = par.createElement('div');
	var old_img = new_div.getElementsByTagName('img')[0];
	if (old_img) {
		new_div.removeChild(old_img);
	}
	
	var new_img = par.createElement('img');
	new_img.src = 'upload2/indicator.gif';
	new_img.className = 'load';
	new_div.appendChild(new_img);
	//images.appendChild(new_div);
	
	// send
	var imgnum = images.getElementsByTagName('div').length - 1;
	document.iform.imgnum.value = imgnum;
	setTimeout(document.iform.submit(),5000);
}
</script>
<style>
#file {
	width: 350px;
}
</style>
<head><body><center>
<form name="iform" action="" method="post" enctype="multipart/form-data">
<input id="file" type="file" name="image" onChange="upload()" />
<input type="hidden" name="imgnum" />
</form>
</center></html>