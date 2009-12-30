<?php
	include("db.php");
	include("common.php"); 	
	include("session.php");
	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");
?>
<html>
<frameset cols="30%,40%">
	<frame src="map.php">
	<frame src="" name="mostright">
<noframes>Your's browser don't support Frame</noframes>
</html>