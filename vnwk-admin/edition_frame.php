<?php
	include("db.php");
	include("common.php"); 	
	include("session.php");
	session_start();
	process(session_id(), myip());
	
	if (!logged_in()) header("location: login.php");
?>
<html>
<frameset cols="25%,*" border="1px" bordercolor="#444444">
	<frame src="revisionmanagement.php" noresize name='edit'>
	<frame src="" name="edition">
<noframes>Your's browser don't support Frame</noframes>
</html>