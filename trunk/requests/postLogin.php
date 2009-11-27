<?php
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
function chkInject($s) {
	return (htmlSpecialChars($s, ENT_QUOTES) != $s);
}
	  
function userid($username, $password) {
	if (trim($password) == "") return -1;	
	$result = mysql_query('SELECT * FROM users WHERE username="'.$username.'" AND password="'.$password.'"');
	$n = mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	if ($n == 0) return -1;
	return $row["id"];
 }
  
$username = $_POST["username"];
$password = $_POST["password"];

function chkLogin() {
	global $username, $password;
	if (chkInject($username)||chkInject($password)) {
		return false;
	}
	else {
	  	$id = userid($username, $password);
	  	if ($id !== -1) {
			login($id);
			return true;
	  	}
		else return false;
	}
}

chkLogin();
?>
