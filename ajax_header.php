<?php
/*
//for ask & answer
define('IN_PHPBB', true);
$phpbb_root_path = 'forum/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);*/

//session management
session_start();

$session_id = session_id();
$ip = $_SERVER['REMOTE_ADDR'];
process($session_id, $ip);
$user_info->update();

/*
//-------------------------------------------------------------------------------------------------------------------
function clean_tag($tag) {
	$t = preg_replace("/\s/", " ", $tag);
}

//-------------------------------------------------------------------------------------------------------------------
function update_image($id, $loc, $des, $tags) {
	if (!logged_in()) {
		die_to_index();
	}	
	
	//sql injection check
	if (!chk_sql_injection($id)) die_to_index();
	if (!chk_sql_injection($tags)) die_to_index();
	if (!chk_sql_injection($loc)) die_to_index();
	
	$des = HtmlSpecialChars($des);
	
	global $q;
	
	$sql = "UPDATE images
			SET dest_id = $loc, des = '$des', tags = '$tags'
			WHERE id = $id";
	$q->query($sql);		
}

//upload avatar-------------------------------------------------------------------------------------------------------
function upload_user_info($ftmp, $fn, $ln, $dd, $mm, $yyyy, $country) { 
	if (!logged_in()) {
		die_to_index();
	}
	
//	$des = HtmlSpecialChars($des);
//	if (!chk_sql_injection($loc)) die_to_index();
	if (!chk_sql_injection($fn)) die_to_index();
	if (!chk_sql_injection($ln)) die_to_index();
	if (!chk_sql_injection($dd)) die_to_index();
	if (!chk_sql_injection($mm)) die_to_index();
	if (!chk_sql_injection($yyyy)) die_to_index();
	if (!chk_sql_injection($country)) die_to_index();
	
	include("includes/class.upload.php");
	include("upload2/class.img.php");
	
	global $q;
	global $user_info;
	
	if (!checkdate($mm, $dd, $yyyy)) die_to_index();
	$dob = mktime(0,0,0,$mm,$dd,$yyyy);
	
	$pftmp = "upload/upload/". $ftmp;
	$handle = new Upload($pftmp);
	//if uploaded file is presented on server
	if ($handle->uploaded) {
		
		$original_path = "images/avatars/original";
		$medium_path = "images/avatars/medium";
		$tiny_path = "images/avatars/tiny";
		
		//move to upload folder
		$handle->process($original_path);		
		
		if ($handle->processed) {
			
			$file_name = $handle->file_dst_name;
			
			//delete old avatar
			$sql = "SELECT avatar
					FROM users
					WHERE id='".$user_info->user_id."'";			
			$q->query($sql);
			$row = mysql_fetch_array($q->re);
			$old_name = $row["avatar"];
			unlink($original_path."/".$old_name);			
			unlink($medium_path."/".$old_name);			
			unlink($tiny_path."/".$old_name);			
			
			//create medium size
			$img = new img($original_path."/".$file_name);
			$img->resize(100, 100, true);
			$img->store($medium_path."/".$file_name);

			//create tiny size
			$img = new img($original_path."/".$file_name);
			$img->resize(25, 25, true);
			$img->store($tiny_path."/".$file_name);	
			
			//update database
			$uploaded_at = time();	
			$sql = "UPDATE users 
					SET firstname='".$fn."', 
						familyname='".$ln."', 
						dob='".$dob."',
						location='".$country."',
						avatar='".$file_name."'
					WHERE id='".$user_info->user_id."'";
			$q->query($sql);
			$handle->clean();
		}
		else {
			?>
			<script>
			alert("can not copy from temporary folder to upload folder");
			</script>
			<?php			
		}
	}
}

//upload photos-------------------------------------------------------------------------------------------------------
function upload_image($ftmp, $loc, $des, $tags) { //Copy temporary file to image folder and create database for that
	if (!logged_in()) {
		die_to_index();
	}
	
	$des = HtmlSpecialChars($des);
	if (!chk_sql_injection($loc)) die_to_index();
	
	include("includes/class.upload.php");
	include("upload/class.img.php");
	
	global $q;
	global $user_info;
	
	$pftmp = "upload2/upload/". $ftmp;
	$handle = new Upload($pftmp);
	//if uploaded file is presented on server
	if ($handle->uploaded) {

		$original_path = UPLOAD_FOLDER;
		$medium_path = MEDIUM_UPLOAD_FOLDER;
		$small_path = SMALL_UPLOAD_FOLDER;
		
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
					VALUE ('".$loc."','".$des."','".$tags."','".$uploaded_at."','".$user_info->user_id."','".$file_name."')";
			$q->query($sql);
			$handle->clean();
		}
		else {
			?>
			<script>
			alert("can not copy from temporary folder to upload folder");
			</script>
			<?php			
		}
	}
}

//-------------------------------------------------------------------------------------------------------------------
function rate($x) {
	$post_id = get_session_page(myip());
	$sql = "SELECT rate, rate_count
			FROM posts
			WHERE post_id = '".$post_id."'";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	$average = $row["rate"];
	$count = $row["rate_count"];
	$new_value = ($average * $count + $x) / ($count + 1);
	$count++;
	$sql = "UPDATE posts
			SET rate='".$new_value."', rate_count = '".$count."'
			WHERE post_id = '".$post_id."'";
	mysql_query($sql) or die(mysql_error());
	$s = '<div class="rating" id="rating_1">'.round($new_value, 2).'</div>';
	$s.= '<div id="statistic">';
	
	$x = $count;
	$x .= ($count > 1)? " readers":" reader";
	
	$s.= "<span class='style5'>Average: ".round($new_value, 2). " - ".$x." rated</span>";
	$s.= "</div>";
	return $s;
}

//-------------------------------------------------------------------------------------------------------------------
function instant_check_image_code($c) {
      if ($c != image_code()) return "<img src='images/notified_icon.jpg'/>";
      return "  <img src='images/ok_icon.jpg'/>";
}

//-------------------------------------------------------------------------------------------------------------------
function instant_check_cp($s, $x) {
      if (check_confirm_password($s, $x) != "ok") {
            return "<img src='images/notified_icon.jpg'/>";
      }
      else return "  <img src='images/ok_icon.jpg'/>"; 
}

//-------------------------------------------------------------------------------------------------------------------
function instant_check_pw($s) {
      $x = check_pw($s);
      if ($x == 1) return "<span class='hint'>   this should not be too long or too short</span>";
      if ($x == 2) return "<img src='images/notified_icon.jpg'/><span class='error'>  this should include alphabeta and numberic characters only</span>";
      if ($x == 0) return "  <img src='images/ok_icon.jpg'/>";
}

//-------------------------------------------------------------------------------------------------------------------
function instant_check_un($s) {
      $x = check_un($s);
      if ($x == 1) return "<span class='hint'>   this should not be too long or too short</span>";
      if ($x == 2) return "<img src='images/notified_icon.jpg'/><span class='error'>  taken</span>";
      if ($x == 3) return "<img src='images/notified_icon.jpg'/><span class='error'>  this should include alphabeta and numberic characters only</span>";
      if ($x == 0) return "  <img src='images/ok_icon.jpg'/>";
}

//-------------------------------------------------------------------------------------------------------------------
function sign_out() {
	$ip = $_SERVER['REMOTE_ADDR'];
	db_connect();
	$sql = "UPDATE sessions
			SET user_id = '' , logged_in = '0'
			WHERE ip = '".$ip."'";
	$re = mysql_query($sql) or die(mysql_error());	
}

//-------------------------------------------------------------------------------------------------------------------
function get_page() {
	return get_session_page(myip()); 
}

//-------------------------------------------------------------------------------------------------------------------
function get_content() {
	$page = get_session_page(myip());
	$sql = "SELECT post_text
			FROM posts_texts
			WHERE post_id = '".$page."'";
	db_connect();
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$s = $row["post_text"];
	return $s;
}

//-------------------------------------------------------------------------------------------------------------------
function get_summary() {
	$page = get_session_page(myip());
	$sql = "SELECT post_summary
			FROM posts_texts
			WHERE post_id = '".$page."'";
	db_connect();
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$s = HtmlSpecialChars($row["post_summary"]);
	return($s);
}

//-------------------------------------------------------------------------------------------------------------------
function get_title(){
	$page = get_session_page(myip());
	$sql = "SELECT post_subject
			FROM posts_texts
			WHERE post_id = '".$page."'";
	db_connect();
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$title = $row["post_subject"];
	return HtmlSpecialChars($title);
}

//-------------------------------------------------------------------------------------------------------------------
function get_location(){
	$page = get_session_page(myip());
	$sql = "SELECT dest_id
			FROM posts
			WHERE post_id = '".$page."'";
	db_connect();
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$s = $row["dest_id"];
	return $s;
}

//-------------------------------------------------------------------------------------------------------------------
function get_index(){
	$page = get_session_page(myip());
	$sql = "SELECT index_id
			FROM posts
			WHERE post_id = '".$page."'";
	db_connect();
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$s = $row["index_id"];
	return $s;
}

//-------------------------------------------------------------------------------------------------------------------
function set_editting() {
	if (!logged_in()) die();
	$sql = "UPDATE sessions
			SET editting = '1'
			WHERE ip = '".myip()."'";
	db_connect();
	mysql_query($sql) or die(mysql_error());
}

//-------------------------------------------------------------------------------------------------------------------
function set_uneditting() {
	if (!writer()) die();
	$sql = "UPDATE sessions
			SET editting = '0'
			WHERE ip = '".myip()."'";
	db_connect();
	mysql_query($sql) or die(mysql_error());
}

//-------------------------------------------------------------------------------------------------------------------
function delete_topic() {
			$page = get_session_page(myip()); 			
			db_connect();
			
			if (!writer()) die();
			//delete from post_texts
			$sql ="DELETE FROM posts_texts
					WHERE post_id = '".$page."'";
			mysql_query($sql) or die(mysql_error());
			
			//delete from posts
			$sql ="DELETE FROM posts
					WHERE post_id = '".$page."'";
			mysql_query($sql) or die(mysql_error());
			
			//delete from comments
			$sql = "DELETE FROM comments
					WHERE post_id = '".$page."'";
			mysql_query($sql) or die(mysql_error());
			
			//delete from acts
			$sql = "DELETE FROM acts
					WHERE target = '".$page."'";
			mysql_query($sql) or die(mysql_error());
					
			return "oh yeh";
}


require("sajax.php");
sajax_init();
sajax_export("rate");
sajax_export("writer");
sajax_export("instant_check_image_code");
sajax_export("instant_check_cp");
sajax_export("instant_check_un");
sajax_export("instant_check_pw");
sajax_export("sign_out");
sajax_export("get_page");
sajax_export("get_content");
sajax_export("get_summary");
sajax_export("get_title");
sajax_export("get_location");
sajax_export("set_editting");
sajax_export("set_uneditting");
sajax_export("delete_topic");
sajax_export("get_index");
sajax_export("upload_image");
sajax_export("update_image");
sajax_export("upload_user_info");

sajax_handle_client_request();*/
?>
<?php
	$photo = 0;
	if (strpos(selfURL(), "photo") > 0) {
		$photo = 1;
	}
	
	function get_dest() {
	 	global $numrow;
		global $index_id;
	 	$id = (isset($_GET["id"]))? $_GET["id"] : 3;
		$URL = selfURL();
		if (strpos($URL, "photo") > 0) {
			return (isset($_GET["dest_id"]))? $_GET["dest_id"] : 0;
		}
		elseif (strpos($URL, "about") > 0) {
			return 18;
		}
		elseif (isset($_GET["id"])) {
			$sql = "SELECT *
					FROM index_menu
					WHERE id = (SELECT index_id
								FROM posts
								WHERE post_id = $id)";
			$result = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_array($result);
			$index_id = $row["id"];
			return $row["dest_id"];
		}
		else {			
			 return $id;
		}
	 }
	 
	 if (isset($_GET["index_id"])) $index_id = $_GET["index_id"];

	 if (!isset($index_id)) {
		$destination = get_dest();
		if (!isset($index_id)) {
			$sql = "SELECT id
					FROM index_menu
					WHERE (dest_id = ".$destination.") AND (ord = 0)
					GROUP BY id";
			$q->query($sql);
			$selected_index = mysql_fetch_array($q->re);
			$index_id = $selected_index["id"];
		}
	}
	 else {
		$sql = "SELECT dest_id
				FROM index_menu
				WHERE id = ".$index_id;
		$q->query($sql);
		$r = mysql_fetch_array($q->re);
		$destination = $r["dest_id"];
	 }
?>
