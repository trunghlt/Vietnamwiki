<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");

function filterFtmp($ftmp){
	return $ftmp;
}

function filterLoc($loc){
	return $loc;
}

function filterDes($des){
	return $des;
}

function filterTags($tags){
	return $tags;
}

$ftmp = filterFtmp($_POST["tmp_file_name"]);
$loc = filterLoc($_POST["loc"]);
$des = filterDes($_POST["des"]);
$tags = filterTags($_POST["tags"]);
upload_image($ftmp, $loc, $des, $tags);

//Copy temporary file to image folder and create database for that
function upload_image($ftmp, $loc, $des, $tags) {
	if (!logged_in()) {
		die_to_index();
	}
	
	$des = HtmlSpecialChars($des);
	
	require_once("../upload2/class.upload.php");
	require_once("../upload2/class.img.php");
	
	global $q;
	global $user_info;
	
	$pftmp = "../upload2/upload/". $ftmp;
	$handle = new Upload($pftmp);
	//if uploaded file is presented on server
	if ($handle->uploaded) {

		$original_path = "../".UPLOAD_FOLDER;
		$medium_path = "../".MEDIUM_UPLOAD_FOLDER;
		$small_path = "../".SMALL_UPLOAD_FOLDER;
		
		//move to upload folder
		$handle->process($original_path);
		
		if ($handle->processed) {
			$uploaded_at = time();	
			$file_name = $handle->file_dst_name;
			
			//create medium size
			$img = new img($original_path."/".$file_name);
			$img->resize(300, 180, true);
			$img->store($medium_path."/".$file_name);

			//create small size
			$img = new img($original_path."/".$file_name);
			$img->resize(100, 75, true);
			$img->store($small_path."/".$file_name);	
			
			$sql = "INSERT INTO images 
					(dest_id, des, tags, uploaded_at, user_id, filename)
					VALUE ('".$loc."','".$des."','".$tags."','".$uploaded_at."','".myUser_id(myip())."','".$file_name."')";
			$q->query($sql);
			$handle->clean();
		}
	}
}
?>
