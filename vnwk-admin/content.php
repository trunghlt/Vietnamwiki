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

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="mistake37">

	<title>Untitled 5</title>
</head>

<body>

Content

</body>
</html>