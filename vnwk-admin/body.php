<?php
//include file	
	include("class_user.php");
	include("common.php");
	include("session.php");
	include("../core/classes/Filter.php");
//check logged user 
	process(session_id(), myip());
	if (!logged_in()) header("location: login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<frameset cols="200,*" border="1px" bordercolor="#444444">

	<frame src="link.php" noresize scrolling="no">
	<frame src="content.php" name="showframe">

</frameset><noframes></noframes>

</html>