<?php
@ob_end_clean();
ob_start();
session_start();
//include file	
	include("class_user.php");
	include("common.php");
	include("session.php");
	include(".././core/classes/filter.php");
//check logged user 
	process(session_id(), myip());
	if (!logged_in()) header("location: login.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="mistake37">

	<title>Untitled 6</title>
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>

<body id='link'>
<?php
		if(isset($_GET['value']))
		{
			$value = Filter::filterInput($_GET['value'],"login.php",1);
			if($value==1)
				$value = 0;
			else $value = 1;
			mysql_query("update setting set property_value = $value where property_name='ALLOW_DIRECT_UPDATE'");
			
		}	
		$re = mysql_query("select * from setting where property_name='ALLOW_DIRECT_UPDATE'");
		$row = mysql_fetch_assoc($re);
?>
<p><a href="sign_out.php">Sign out</a></p>
<p><a href="destmenu.php" target="showframe">Destination & Index menu management</a></p>
<p><a href="topic.php" target="showframe">Topic management</a></p>
<p><a href="edition_frame.php" target="showframe">Revision management</a></p>
<p><a href="#">Slide management</a></p>
<p><a href="user_frame.php" target="showframe">User management</a></p>
<p><a href="map_frame.php" target="showframe">Map management</a></p>
<p><a href="link.php?value=<?php echo $row['property_value']?>" >
<?php 
	if($row['property_value']==1)
		echo 'Don\'t allow direct update';
	else
		echo 'Allow direct update';
?></a>
</p>
<p><a href="#">Back up</a></p>
</body>
</html>
<?php
ob_end_flush();
?>