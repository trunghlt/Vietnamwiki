<?php
//URL
define("URL",  "http://www.vietnamwiki.net/");
define("ROOT", "http://www.vietnamwiki.net");

//image upload folder
define('UPLOAD_FOLDER', 'images/upload/');
define('MEDIUM_UPLOAD_FOLDER', 'images/upload/medium/');
define('SMALL_UPLOAD_FOLDER', 'images/upload/small/');

//image temporary upload folder
define('TEMP_UPLOAD_FOLDER', 'upload2/upload/');

//number of image columns & rows
define('COL', 3);
define('ROW', 3);

include('permalink.php');
include("APIs.php");
include('fbconnect.php');

#Initialise memcached
$memcache = new Memcache;
$memcache->connect("127.0.0.1", 11211);

function dieToInvalidInput() {
	header("location: /");		
}

function isNumeric($s) {
	return preg_match("/^[+-]?[0-9]+$/", $s);
}

function filterNumeric($s) {
	if (!isNumeric($s)) dieToInvalidInput();
	return $s;		
}

function dbquery($sql) {
	$re = mysql_query($sql) or die(mysql_error());
	return $re;
}


function chk_sql_injection($s) {
	return (HtmlSpecialChars($s) == $s);
}

function ajax_chk_sql_injection($s) {
}


/*------------------------------------------------------------------
Parse some values of properties stored in an array into an object
NON RECURSIVE
->0: if unsuccessful, 1: if successful
------------------------------------------------------------------*/
function array2object($data, &$object) {
   if(!is_array($data)) return 0;   
   if (is_array($data) && count($data) > 0) {
      foreach ($data as $name=>$value) {
         $name = strtolower(trim($name));
         if (!empty($name)) {
            $object->$name = $value;
         }
      }
   }   
   return 1;
}


function object2array($data){
   if(!is_object($data) && !is_array($data)) return $data;

   if(is_object($data)) $data = get_object_vars($data);

   return array_map('object2array', $data);
}

	
function logged_in() {
	$ip = myip();
	$sql = "SELECT *
			FROM sessions
			WHERE ip = '".$ip."'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	return $row["logged_in"];
}


//set new confirm_id	
function writer() {
	$page = get_session_page(myip());
	$username = myUsername(myip());
	$sql = "SELECT post_username
			FROM posts
			WHERE post_id = '".$page."'";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	return ($username == $row["post_username"]);
}
	
function die_to_index() {
	?> 
	<script type="text/javascript">
		window.location = "index.php";
	</script>	
	<?php
	die();
}

function login($id) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$sql = "UPDATE sessions
			SET user_id = '".$id."' , logged_in = '1'
			WHERE ip = '".$ip."'";
	$re = mysql_query($sql) or die(mysql_error());					
}

function set_new_confirm_id($session_id) {
    $s = '';
	for ($i=1; $i <= 32; $i++) {
		$c = chr(64 + rand(1, 26));
		$s .= $c;
	}
	
	function exist_confirm_code() {
		global $session_id;
		$sql = "SELECT * 
				FROM confirm_code
				WHERE session_id = '".$session_id."'";
		$result = mysql_query($sql) or die(mysql_error());
		return (mysql_num_rows($result)==0)? FALSE : TRUE;
	}
	
	if (!exist_confirm_code()) {
		//get id
		//insert into confirm_code table
		$sql = "INSERT INTO confirm_code
				(session_id, code)
				VALUE ('".$session_id."','".$s."')";
		mysql_query($sql) or die(mysql_error());
		$id = mysql_insert_id();
	}
	else {
		//update new code
		$sql = "UPDATE confirm_code
				SET code = '".$s."'
				WHERE session_id = '".$session_id."'";
		mysql_query($sql) or die(mysql_error());
	}
}


function get_session_id() {
	$sql = "SELECT id
			FROM sessions
			WHERE ip = '".myip()."'";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	return $row["id"];
}

function image_code() {
	$sql = "SELECT *
	      FROM confirm_code
		WHERE session_id = '".get_session_id()."'";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
      return $row["code"];
}
            
function check_confirm_image($code , $right_code){
	return ($code !== $right_code )? "The confirm number is invalid" : "ok";
}

function check_email($s){
	$str = "/[a-zA-Z0-9._]+\@[a-zA-Z0-9]{2,}\.[a-zA-Z]{2,}/";	
     if (!strpos($s, '@') || !preg_match($str,$s)) return "your email is invalid";
	 db_connect();
	 $result = mysql_query("SELECT * FROM users WHERE email='".$s."'") or die(mysql_error());
	 $t = mysql_num_rows($result);
	 if ($t !== 0) return "Your email has been used already";
	 mysql_free_result($result);
     return 'ok';
}

function check_confirm_password($s ,$x){
	 return ($s == $x)? "ok" : "Confirm password is different from the password";
}

function check_pw($s) {
 	if (htmlSpecialChars($s, ENT_QUOTES) != $s) {
	     return 2;
	}   
      elseif ((strlen($s) < 5)||(strlen($s) > 30)) {
           return 1;
      }
      return 0;
}

function check_password($s){
     $x = check_pw($s);
      if ($x == 1) {
            return "Password should have a length longer than 4 characters and shorter than 30 characters";
      }
	elseif ($x == 2) {
            return "the username should include alphabeta and numberic characters only";
      }
      else {
	     return "ok";
	}
}

function check_un($s){
	if (htmlSpecialChars($s, ENT_QUOTES) != $s) {
	     return 3;
	}
      elseif ((strlen($s) < 5)||(strlen($s) > 30)) {
		return 1;
      }
	else {
		db_connect();
		$result = mysql_query("SELECT * FROM users WHERE username='".$s."'") or die(mysql_error());
		$t = mysql_num_rows($result);
		if ($t !== 0) return 2;
            mysql_free_result($result);
            return 0;
	}
}

function check_username($s){
	$x = check_un($s);
	if ($x==1) return "username should have a length longer than 4 characters and shorter than 30 characetrs";
	if ($x==2) return "the username was taken already";
	if ($x==3) return "the username should include alphabeta and numberic characters only";
	if ($x==0) return "ok";
}

function myUsername($ip) {
	db_connect();
	$sql = "SELECT username
			FROM users
			WHERE id = (SELECT user_id
						FROM sessions
						WHERE ip = '".$ip."')";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	return $row["username"];
}

function myUser_id($ip) {
	db_connect();
	$sql = "SELECT user_id
			FROM sessions
			WHERE ip = '".$ip."'";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	return $row["user_id"];
}

function check_logged_in($ip) {
	db_connect();
	$sql = "SELECT logged_in
			FROM sessions
			WHERE ip = '".$ip."'";
	$result = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($result);
	return $row["logged_in"];
}

function myip() {
	return $_SERVER['REMOTE_ADDR'];
}


function smaller($a, $b) {
      if ($a == FALSE) return $b;
      if ($b == FALSE) return $a;
      if ($a > $b) return $b;
      return $a;
}

function MakeTextViewable($text) {
	  $s = HtmlSpecialChars($text);
	  $s = str_replace(chr(13), '</p><p>', $s);
	  $s = "<span style='margin-bottom:10px;margin-top:10px;'>" . $s . "</span>";
	  return $s;
}

function HeadCut($text) {
      $x = strpos($text, ".");
      $y = strpos($text, chr(13));
      $p = smaller($x, $y)+ 1;
      $x = strpos($text, ".", $p);
      $y = strpos($text, chr(13), $p);
      $p = smaller($x, $y) + 1;
      if ($p == 0) $p = strlen($text);
      $s = substr($text, 0, $p);
      return $s;
}
function selfURL() { 
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
} 

function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }

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
//--------------------------login admin-------------------
	function logged_in_admin() {
		$ip = myip();
		$sql = "SELECT *
				FROM admin_sessions
				WHERE ip = '".$ip."'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		return $row["logged_in"];
	}
	
	function filter_content_script($s){
		$str = str_replace('<script',' ',$s);
		$str = str_replace('</script>',' ',$str);
		$str = str_replace('<frameset',' ',$str);
		$str = str_replace('</frameset>',' ',$str);
		$str = str_replace('<iframe',' ',$str);
		$str = str_replace('</iframe>',' ',$str);
		$str = str_replace('<frame',' ',$str);
		$str = str_replace('</frame>',' ',$str);
		return $str;
	}			
?>

