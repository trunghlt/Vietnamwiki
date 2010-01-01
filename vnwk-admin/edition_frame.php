<?php
	include("db.php");
	include("common.php"); 	
	include("session.php");
	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");
?>
<html>
<frameset cols="20%,60%">
	<frame src="revisionmanagement.php">
	<frame src="" name="edition">
<noframes>Your's browser don't support Frame</noframes>
</html>