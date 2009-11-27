<?php
	include("db.php");
	include("common.php");
	include("session.php");

	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");
?>
<html>

	<frameset rows="10%,*">
		  <frame src="head.htm">
		  <frame src="body.htm">
	</frameset>

</html>