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
	<frame src="control.php" noresize>
	<frame src="" name="index_menu">
</frameset>
<noframes>Your browser doesn't support Frame</noframes>
</html>
