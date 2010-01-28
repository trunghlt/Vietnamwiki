<?php
error_reporting(E_ALL);

function error($msg) {
	?>
	<script>
	alert("<?php echo $msg?>");
	</script>
	<?php
}

if(isset($_POST['image'])){
	
	$str = '/(http|https|ftp)\:\/\/[a-zA-Z0-9._-]+\.[a-zA-Z]{2,}[a-zA-Z0-9[:punct:]]*\.(jpg|jpge)/';
	$link_url = $_POST['image'];
	$link_url = substr_replace($link_url,strtolower(substr($link_url,-3,3)),-3,3);
	if(preg_match($str,$link_url)){
		$size = array_change_key_case(get_headers($link_url,1));
		if($size['content-length'] > 2*(1024*1024))
			error("Image size should not be bigger than 2MB");
		else{
			$image = pathinfo($link_url);
			
			$ftmp = 'temp_'.$image['basename'];
			$oname = $image['basename'];
					
			$t = file_get_contents($link_url);
			$fp = fopen("upload/$ftmp",'wb');
			
			if(fwrite($fp,$t)){
				
				$fname = $ftmp;
				$pname = "upload/". $fname;
				fclose($fp);
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
					image_new.src = "upload2/resize.php?pic=<?php echo $pname?>";
					image_new.className = 'loaded';
					imgdiv.appendChild(image_new);
					</script></head>
					</html>
<?php
			}
			else{
				error('not upload');
				fclose($fp);
				unlink("upload/$ftmp");
			}
			
		}
	}
	else{
		error('not url or only image uploads are allowed');
		exit();
	}
}
?><html><head>
<script language="javascript">
function upload(){
	var par = window.parent.document;

	// hide old iframe
	var container = par.getElementById("iframe_container1");
	var iframes = container.getElementsByTagName('iframe');
	var iframe = iframes[iframes.length - 1];
	iframe.className = "hidden";

	// create new iframe
	var new_iframe = par.createElement('iframe');
	new_iframe.src = 'upload2/upload_url.php';
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
	document.i2form.imgnum1.value = imgnum;
	setTimeout(document.i2form.submit(),5000);
}
</script>
<style>
#file {
	width: 350px;
}
</style>
<head><body><center>
<form name="i2form" action="" method="post" >
<input id="image" type="input" name="image" onChange="upload()" />
<input type="hidden" name="imgnum1" />
</form>
</center></html>