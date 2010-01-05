<?php
session_start();
include("db.php");
$sql = "DELETE FROM admin_sessions";
mysql_query($sql) or die(mysql_error());	
session_destroy();
?>
<script>
	top.location = "login.php";
</script>