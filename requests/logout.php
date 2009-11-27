<?php 
include("../core/common.php");
include("../core/init.php");
include("../core/classes/Db.php");
function signOut() {
	$ip = $_SERVER['REMOTE_ADDR'];
	db_connect();
	$sql = "UPDATE sessions
			SET user_id = '' , logged_in = '0'
			WHERE ip = '".$ip."'";
	$re = mysql_query($sql) or die(mysql_error());	
}
signOut();
?>