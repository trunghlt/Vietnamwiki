<?php
	include("db.php");
	include("common.php");
	include("session.php");

	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");
?>
<html>
<frameset cols="20%,20%,*">
	<frame src="control.php">
	<frame src="UntitledFrame-1" name="index_menu">
</html>