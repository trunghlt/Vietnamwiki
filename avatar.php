<?php
	$sql = "SELECT avatar
			FROM users
			WHERE id='".$user_info["id"]."'";
	$q->query($sql);
	$row = mysql_fetch_array($q->re);
	$ava = $row["avatar"];
	if (isset($ava)) { 
		$fn = $ava;
	}
	else {
		$fn = "unkown.jpg";
	}
?>
<img width="100px" height="100px" src="images/avatars/medium/<?php echo $fn?>" />