<?php
@ob_end_clean();
ob_start();
session_start();
//include file	
	include("class_user.php");
	include("common.php");
	include("session.php");
	include("../core/classes/Filter.php");
	include("../core/classes/Setting.php");
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
$setting = new Setting;
/*Set for function Update*/
function set_update($value,$s){
	$setting = new Setting;
	if($value==1)
		$str = 'property_value = 0';
	else $str = 'property_value = 1';
	$where = "property_name = '".$s."'";
	$setting->update($str,$where);	
}

//--------------------------------

		$type = isset($_GET['type']);
		if(isset($_GET['value']) && $type)
		{
			$type = Filter::filterInput($_GET['type'],"login.php",3);
			$value = Filter::filterInput($_GET['value'],"login.php",1);
			if($type == 'up_content'){
				set_update($value,'ALLOW_DIRECT_UPDATE');
			}
			else if($type == 'draft_restore'){
				set_update($value,'ALLOW_RESTORE_DRAFT');
			}
		}	
		$r = $setting->query_get_link();
?>
<p><a href="sign_out.php">Sign out</a></p>
<p><a href="destmenu.php" target="showframe">Destination & Index menu management</a></p>
<p><a href="topic.php" target="showframe">Topic management</a></p>
<p><a href="edition_frame.php" target="showframe">Revision management</a></p>
<p><a href="#">Slide management</a></p>
<p><a href="user_frame.php" target="showframe">User management</a></p>
<p><a href="map_frame.php" target="showframe">Map management</a></p>
<p><a href="link.php?value=<?php echo $r['ALLOW_DIRECT_UPDATE']?>&type=up_content" >
<?php 
	if($r['ALLOW_DIRECT_UPDATE']==1)
		echo 'Don\'t allow direct update';
	else
		echo 'Allow direct update';
?></a>
</p>
<p><a href="link.php?value=<?php echo $r['ALLOW_RESTORE_DRAFT']?>&type=draft_restore" >
<?php 
	if($r['ALLOW_RESTORE_DRAFT']==1)
		echo 'Don\'t allow Restore draft';
	else
		echo 'allow Restore draft';
?></a>
</p>
<p><a href="#">Back up</a></p>
</body>
</html>
<?php
ob_end_flush();
?>