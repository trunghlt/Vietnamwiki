<?php
	include("db.php");
	include("common.php"); 
	include("session.php");
	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");
?>
<html>
	<frameset rows="80,*" border="1px" bordercolor="#003366">
		  <frame src="head.php" noresize scrolling="no" >
		  <frame src="body.php">
	</frameset>
</html>