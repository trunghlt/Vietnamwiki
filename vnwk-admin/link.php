<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="mistake37">

	<title>Untitled 6</title>
<style>
	a{
		text-decoration:none;
	}
	a:visited{
		color:#0000FF;
	}
	a:hover{
		color:#00FF00;
	}
</style>
</head>

<body>
<?php
	include("db.php");
	include(".././core/classes/filter.php");

		if(isset($_GET['value']))
		{
			$value = Filter::filterInput($_GET['value'],"login.php",1);
			if($value==1)
				$value = 2;
			else $value = 1;
			mysql_query("update feature set value = $value");
			
		}	
		$re = mysql_query("select * from feature");
		$row=mysql_fetch_assoc($re);
?>
<p>Sign out</p>
<p><a href="destmenu.php" target="showframe">Destination & Index menu management</a></p>
<p><a href="topic.php" target="showframe">Topic management</a></p>
<p><a href="edition_frame.php" target="showframe">Revision management</a></p>
<p>Slide management</p>
<p><a href="user_frame.php" target="showframe">User management</a></p>
<p><a href="map_frame.php" target="showframe">Map management</a></p>
<p><a href="link.php?value=<?php echo $row['value']?>" >
<?php 
	if($row['value']==1)
		echo 'Allowed_up_content';
	else
		echo 'Not_Allowed_up_content';
?></a>
</p>
<p>Back up</p>
</body>
</html>