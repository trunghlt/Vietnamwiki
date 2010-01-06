<?php
session_start();
include("db.php");
$ip = $_SERVER['REMOTE_ADDR'];
$sql = "UPDATE admin_sessions
		SET admin_id = '0' , logged_in = '0'
		WHERE ip = '".$ip."'";
mysql_query($sql) or die(mysql_error());	
session_destroy();
?>
<script>
	top.location = "login.php";
</script>